<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use League\Glide\Server;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoriaNegocio extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'categorias_negocio';
    protected $fillable = [
        'nombre',
        'slug',
        'icono',
    ];

    protected function icono(): Attribute
    {
        return Attribute::make(
            set: function ($photo) {
                if (!$photo) return;
                return ['icon_path' => $photo instanceof UploadedFile ? $photo->store('categorias_negocio') : $photo];
            },
            get: fn () => $this->iconoUrl(['w' => 200, 'h' => 200, 'fit' => 'crop'])
        );
    }

    public function iconoUrl(array $attributes)
    {
        if ($this->icon_path) {
            return URL::to("/img/".App::make(Server::class)->makeImage($this->icon_path, $attributes));
        }
    }

    public function negocio(): BelongsTo
    {
        return $this->belongsTo(Negocio::class);
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
}
