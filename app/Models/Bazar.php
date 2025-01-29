<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bazar extends Model
{
    use HasFactory;

    protected $table = 'bazar';

    protected $fillable = [
        'nombre',
        'nombre_contacto',
        'puesto_contacto',
        'direccion',
        'ciudad',
        'estado',
        'pais',
        'cp',
        'telefono_contacto',
        'correo',
        'sitio_web',
        'tipo_participante',
        'giro_empresa',
        'tipo_exposicion',
        'expo_nombre',
        'expo_correo',
        'expo_puesto',
        'expo_materiales',
        'negocio_descripcion',
        'negocio_tipo_venta',
        'negocio_tipo_empresa',
        'negocio_servicios',
        'negocio_asociacion',
    ];
}
