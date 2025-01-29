<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use League\Glide\Server;

class Banner extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'banners';
    protected $fillable = [
        'alt',
        'imagen',
        'enlace',
    ];

    protected function imagen(): Attribute
    {
        return Attribute::make(
            set: function ($photo) {
                if (!$photo) return;
                return ['imagen_path' => $photo instanceof UploadedFile ? $photo->store('banners') : $photo];
            },
            get: fn () => $this->imagenUrl(['w' => 1200, 'h' => 800, 'fit' => 'contain'])
        );
    }

    public function imagenUrl(array $attributes)
    {
        if ($this->imagen_path) {
            return URL::to("/img/".App::make(Server::class)->makeImage($this->imagen_path, $attributes));
        }
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('alt', 'like', '%' . $search . '%');
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }

}
