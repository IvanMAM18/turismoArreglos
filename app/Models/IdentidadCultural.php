<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use League\Glide\Server;
use Illuminate\Support\Str;

class IdentidadCultural extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'identidad_cultural';
    protected $fillable = [
        'nombre',
        'descripcion',
        'historia',
        'leyenda',
        'recomendaciones',
        'cover',
        'principal',
        'id_delegacion',
        'slug'
    ];
    protected function cover(): Attribute
    {
        return Attribute::make(
            set: function ($photo) {
                if (!$photo) return;
                return ['cover_path' => $photo instanceof UploadedFile ? $photo->store('atractivos') : $photo];
            },
            get: function () {
                if ($this->cover_path) {
                    return URL::to("/img/" . App::make(Server::class)->makeImage($this->cover_path, ['w' => 1000, 'h' => 600, 'fit' => 'contain']));
                }
            }
        );
    }

    protected function principal(): Attribute
    {
        return Attribute::make(
            set: function ($photo) {
                if (!$photo) return;
                return ['principal_path' => $photo instanceof UploadedFile ? $photo->store('atractivos') : $photo];
            },
            get: function () {
                if ($this->principal_path) {
                    return URL::to("/img/" . App::make(Server::class)->makeImage($this->principal_path, ['w' => 1000, 'h' => 600, 'fit' => 'contain']));
                }
            }
        );
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->whereRaw('upper(nombre) like ?' , '%' . strtoupper($search) . '%');
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }
   
    public function fotos()
    {
        return $this->hasMany(IdentidadCulturalFoto::class, 'id_identidad_cultural', 'id');
    }
    public function delegacion(): HasOne
    {
        return $this->hasOne(Delegacion::class, 'id', 'id_delegacion');
    }

    public static function withoutEvents(callable $callback)
    {
        $dispatcher = static::getEventDispatcher();
        if ($dispatcher) {
            static::unsetEventDispatcher();
        }
        try {
            return $callback();
        } finally {
            if ($dispatcher) {
                static::setEventDispatcher($dispatcher);
            }
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($atractivo) {
            $atractivo->slug = $atractivo->createSlug($atractivo->nombre,$atractivo->id);
            $atractivo->save();
        });
        static::updated(function ($atractivo) {
            static::withoutEvents(function () use ($atractivo) {
                $atractivo->slug = $atractivo->createSlug($atractivo->nombre,$atractivo->id);
                $atractivo->save();
            });
        });
    }

    private function createSlug($nombre,$id)
    {
        if (static::whereSlug($slug = Str::slug($nombre))->exists()) {
            return "{$slug}-{$id}";
        }
        return $slug;
    }
}
