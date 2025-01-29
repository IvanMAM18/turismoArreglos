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

class AtractivoServicio extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'atractivos_servicios';
    protected $fillable = [
        'id_servicio',
        'id_atractivo',
    ];
    protected $appends = ['icono'];

    public function atractivo(): BelongsTo
    {
        return $this->belongsTo(Atractivo::class, 'id_atractivo', 'id');
    }

    public function servicio(): BelongsTo
    {
        return $this->belongsTo(Servicio::class, 'id_servicio', 'id');
    }

    public function getIconoAttribute()
    {
        // if relation is not loaded yet, load it first in case you don't use eager loading
        if (!array_key_exists('servicio', $this->relations))
            $this->load('servicio');

        $servicio = $this->getRelation('servicio');

        // then return the name directly
        if ($servicio)
            return $servicio->icono;
        return null;
    }
}
