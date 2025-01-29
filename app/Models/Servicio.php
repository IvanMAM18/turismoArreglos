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

class Servicio extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'servicios_turismo';
    protected $fillable = [
        'nombre',
        'icono',
    ];

    protected function icono(): Attribute
    {
        return Attribute::make(
            set: function ($photo) {
                if (!$photo) return;
                return ['icon_path' => $photo instanceof UploadedFile ? $photo->store('servicios') : $photo];
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

    public function atractivo(): BelongsTo
    {
        return $this->belongsTo(Atractivo::class);
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
