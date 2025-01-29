<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use League\Glide\Server;
use Illuminate\Support\Str;

class Delegacion extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'delegaciones';
    protected $fillable = [
        'nombre',
        'leyenda',
        'descripcion',
        'cover',
        'principal',
        'id_delegacion_padre',
        'slug'
    ];

    protected function cover(): Attribute
    {
        return Attribute::make(
            set: function ($photo) {
                if (!$photo) return;
                return ['cover_path' => $photo instanceof UploadedFile ? $photo->store('delegaciones') : $photo];
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
                return ['principal_path' => $photo instanceof UploadedFile ? $photo->store('delegaciones') : $photo];
            },
            get: function () {
                if ($this->principal_path) {
                    return URL::to("/img/" . App::make(Server::class)->makeImage($this->principal_path, ['w' => 600, 'h' => 1000, 'fit' => 'contain']));
                }
            }
        );
    }

    public function atractivos()
    {
        return $this->hasMany(Atractivo::class, 'id_delegacion', 'id');
    }
    public function negocios()
    {
        return $this->hasMany(Negocio::class, 'id_delegacion', 'id');
    }

    public function delegacion(): HasOne
    {
        return $this->hasOne(Delegacion::class, 'id', 'id_delegacion_padre');
    }
    public function identidadcultural()
    {
        return $this->hasMany(IdentidadCultural::class, 'id_delegacion', 'id');
    }


    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('nombre', 'like', '%' . $search . '%');
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }

    public static function withoutEvents(callable $callback) {
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

        static::created(function ($delegacion) {
            $delegacion->slug = $delegacion->createSlug($delegacion->nombre,$delegacion->id);
            $delegacion->save();
        });
        static::updated(function ($delegacion) {
            static::withoutEvents(function () use ($delegacion) {
                $delegacion->slug = $delegacion->createSlug($delegacion->nombre,$delegacion->id);
                $delegacion->save();
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
