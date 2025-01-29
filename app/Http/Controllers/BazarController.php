<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBazarRequest;
use App\Http\Resources\AtractivoCollection;
use App\Http\Resources\AtractivoResource;
use App\Http\Resources\BannerCollection;
use App\Http\Resources\DelegacionResource;
use App\Http\Resources\EventoCollection;
use App\Http\Resources\IdentidadCulturalCollection;
use App\Http\Resources\IdentidadCulturalResource;
use App\Http\Resources\NegocioResource;
use App\Models\Atractivo;
use App\Models\Banner;
use App\Models\Bazar;
use App\Models\Categoria;
use App\Models\CategoriaExperiencia;
use App\Models\Delegacion;
use App\Models\Evento;
use App\Models\IdentidadCultural;
use App\Models\Negocio;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Inertia\Inertia;

class BazarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return Inertia::render('Bazar/Welcome', []);
    }

    public function indexAdmin()
    {
        return Inertia::render('Admin/Bazar/Index', []);
    }

    public function export()
    {
        $csvFileName = 'bazar.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $csvFileName . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'id',
                'fecha',
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
            ]); // Add more headers as needed
            $products = Bazar::all();

            foreach ($products as $reg) {
                fputcsv($handle, [
                    $reg->id,
                    $reg->created_at,
                    $reg->nombre,
                    $reg->nombre_contacto,
                    $reg->puesto_contacto,
                    $reg->direccion,
                    $reg->ciudad,
                    $reg->estado,
                    $reg->pais,
                    $reg->cp,
                    $reg->telefono_contacto,
                    $reg->correo,
                    $reg->sitio_web,
                    $reg->tipo_participante,
                    $reg->giro_empresa,
                    $reg->tipo_exposicion,
                    $reg->expo_nombre,
                    $reg->expo_correo,
                    $reg->expo_puesto,
                    $reg->expo_materiales,
                    $reg->negocio_descripcion,
                    $reg->negocio_tipo_venta,
                    $reg->negocio_tipo_empresa,
                    $reg->negocio_servicios,
                    $reg->negocio_asociacion,
                ]); // Add more fields as needed
            }

            fclose($handle);
        }, 200, $headers);
    }

    public function registrado()
    {
        return Inertia::render('Bazar/Registrado', []);
    }

    public function store(StoreBazarRequest $request)
    {
        Bazar::create(
            $request->validated()
        );

        return Redirect::route('bazar-registrado')->with('success', 'Registro realizado con exito.');
    }
}
