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

class AtractivoActividad extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'atractivos_actividades';
    protected $fillable = [
        'id_actividad',
        'id_atractivo',
    ];
    protected $appends = ['icono'];

    public function atractivo(): BelongsTo
    {
        return $this->belongsTo(Atractivo::class, 'id_atractivo', 'id');
    }

    public function actividad(): BelongsTo
    {
        return $this->belongsTo(Actividad::class, 'id_actividad', 'id');
    }

    public function getIconoAttribute()
    {
        // if relation is not loaded yet, load it first in case you don't use eager loading
        if (!array_key_exists('actividad', $this->relations))
            $this->load('actividad');

        $actividad = $this->getRelation('actividad');

        // then return the name directly
        if ($actividad)
            return $actividad->icono;
        return null;
    }
}
