<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use League\Glide\Server;

class IdentidadCulturalFoto extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'identidad_cultural_fotos';
    protected $fillable = [
        'foto',
    ];
    protected $appends = ['foto'];

    protected function foto(): Attribute
    {
        return Attribute::make(
            set: function ($photo) {
                if (!$photo) return;
                return ['path' => $photo instanceof UploadedFile ? $photo->store('identidad_cultural_fotos') : $photo];
            },
            get: function () {
                if ($this->path) {
                    return URL::to("/img/" . App::make(Server::class)->makeImage($this->path, ['w' => 1000, 'h' => 600, 'fit' => 'contain']));
                }
            }
        );
    }
    public function identidadCultural(): BelongsTo
    {
        return $this->belongsTo(IdentidadCultural::class, 'id_identidad_cultural', 'id');
    }
}
