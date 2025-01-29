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

class Negocio extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'negocios';
    protected $fillable = [
        'nombre',
        'direccion',
        'descripcion',
        'cover',
        'principal',
        'id_delegacion',
        'id_categoria_experiencia',
        'latitud',
        'longitud',
        'redes_sociales',
        'paginaweb',
        'contacto_telefono',
        'contacto_persona',
        'contacto_correo',
        'contacto_puesto',
        'id_comercio',
        'slug'
    ];
    protected function cover(): Attribute
    {
        return Attribute::make(
            set: function ($photo) {
                if (!$photo) return;
                return ['cover_path' => $photo instanceof UploadedFile ? $photo->store('negocios') : $photo];
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
                return ['principal_path' => $photo instanceof UploadedFile ? $photo->store('negocios') : $photo];
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
        return $this->hasMany(NegocioFoto::class, 'id_negocio', 'id');
    }
    public function delegacion(): HasOne
    {
        return $this->hasOne(Delegacion::class, 'id', 'id_delegacion');
    }
    public function categoriaExperiencia(): HasOne
    {
        return $this->hasOne(CategoriaExperiencia::class, 'id', 'id_categoria_experiencia');
    }
    public function categorias()
    {
        return $this->hasMany(NegocioCategoria::class, 'id_negocio', 'id');
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

        static::created(function ($negocio) {
            $negocio->slug = $negocio->createSlug($negocio->nombre, $negocio->id);
            $negocio->save();
        });
        static::updated(function ($negocio) {
            static::withoutEvents(function () use ($negocio) {
                $negocio->slug = $negocio->createSlug($negocio->nombre, $negocio->id);
                $negocio->save();
            });
        });
    }

    private function createSlug($nombre, $id)
    {
        $reg = static::whereSlug($slug = Str::slug($nombre))->where('id', '!=', $id)->exists();
        if ($reg) {
            return "{$slug}-{$id}";
        }
        return $slug;
    }
}
