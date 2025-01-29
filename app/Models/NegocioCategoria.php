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

class NegocioCategoria extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'negocios_categorias';
    protected $fillable = [
        'id_categoria_negocio',
        'id_negocio',
    ];
    protected $appends = ['icono'];

    public function negocio(): BelongsTo
    {
        return $this->belongsTo(Negocio::class, 'id_negocio', 'id');
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(CategoriaNegocio::class, 'id_categoria_negocio', 'id');
    }

    public function getIconoAttribute()
    {
        // if relation is not loaded yet, load it first in case you don't use eager loading
        if (!array_key_exists('categoria', $this->relations))
            $this->load('categoria');

        $categoria = $this->getRelation('categoria');

        // then return the name directly
        if ($categoria)
            return $categoria->icono;
        return null;
    }
}
