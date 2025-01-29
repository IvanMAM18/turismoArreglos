<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNegocioRequest;
use App\Http\Requests\UpdateNegocioRequest;
use App\Http\Resources\ActividadCollection;
use App\Http\Resources\NegocioCollection;
use App\Http\Resources\NegocioResource;
use App\Http\Resources\CategoriaCollection;
use App\Http\Resources\DelegacionCollection;
use App\Http\Resources\NegocioCategoriaCollection;
use App\Http\Resources\ServicioCollection;
use App\Models\Actividad;
use App\Models\Negocio;
use App\Models\Categoria;
use App\Models\CategoriaExperiencia;
use App\Models\CategoriaNegocio;
use App\Models\Delegacion;
use App\Models\Servicio;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class NegocioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Admin/Negocio/Index', [
            'filters' => Request::all('search', 'trashed'),
            'negocios' => new NegocioCollection(
                Negocio::orderBy('nombre')
                    ->filter(Request::only('search', 'trashed'))
                    ->paginate()
                    ->appends(Request::all())
            ),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Negocio/Create', [
            'delegaciones' => new DelegacionCollection(
                Delegacion::all()
            ),
            'categorias' => new NegocioCategoriaCollection(
                CategoriaNegocio::all()
            ),
            'categoriasExperiencia' => new NegocioCategoriaCollection(
                CategoriaExperiencia::all()
            ),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNegocioRequest $request)
    {
        $vs = [];
        if ($request->cover != null) {
            $vs['cover'] = 'image';
        }
        if ($request->principal != null) {
            $vs['principal'] = 'image';
        }
        if ($request->fotos != null) {
            $vs['fotos.*'] = 'image';
        }
        $validator = Validator::make($request->all(), $vs);
        if ($validator->passes()) {
            $except = [];

            if ($request->cover == null) {
                $except[] = 'cover';
            }
            if ($request->principal == null) {
                $except[] = 'principal';
            }

            if (count($except) == 0) {
                $negocio = Negocio::create(
                    $request->validated()
                );
            } else {
                $negocio = Negocio::create(
                    $request->safe()->except($except)
                );
            }
            
            $categorias = json_decode($request->input('categorias'));
            if ($categorias) {
                $categorias = array_map(fn ($q) => ['id_categoria_negocio' => $q->id, 'id_negocio' => $negocio->id], $categorias);
                $negocio->categorias()->createMany($categorias);
            }

            $fotos = $request->file('fotos');
            if ($fotos) {
                $fotos = array_map(fn ($q) => ['foto' => $q, 'id_negocio' => $negocio->id], $fotos);
                $negocio->fotos()->createMany($fotos);
            }

            return Redirect::route('negocios')->with('success', 'Negocio registrado.');
        }
        return Redirect::back()->withErrors($validator)->withInput();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Negocio $negocio)
    {
        return Inertia::render('Admin/Negocio/Edit', [
            'negocio' => new  NegocioResource($negocio->loadMissing(['categorias', 'fotos'])),
            'delegaciones' => new DelegacionCollection(
                Delegacion::all()
            ),
            'categorias' => new NegocioCategoriaCollection(
                CategoriaNegocio::all()
            ),
            'categoriasExperiencia' => new NegocioCategoriaCollection(
                CategoriaExperiencia::all()
            ),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNegocioRequest $request, Negocio $negocio)
    {
        $vs = [];
        if ($request->cover != null) {
            $vs['cover'] = 'image';
        }
        if ($request->principal != null) {
            $vs['principal'] = 'image';
        }
        if ($request->fotos != null) {
            $vs['fotos.*'] = 'image';
        }
        $validator = Validator::make($request->all(), $vs);
        if ($validator->passes()) {
            $except = [];

            if ($request->cover == null) {
                $except[] = 'cover';
            }
            if ($request->principal == null) {
                $except[] = 'principal';
            }

            if (count($except) == 0) {
                $negocio->update(
                    $request->validated()
                );
            } else {
                $negocio->update(
                    $request->safe()->except($except)
                );
            }

            $negocio->categorias()->forceDelete();
            $categorias = json_decode($request->input('categorias'));
            if ($categorias) {
                $categorias = array_map(fn ($q) => ['id_categoria_negocio' => $q, 'id_negocio' => $negocio->id], $categorias);
                $negocio->categorias()->createMany($categorias);
            }

            $fotos = $request->file('fotos');
            if ($fotos) {
                $fotos = array_map(fn ($q) => ['foto' => $q, 'id_negocio' => $negocio->id], $fotos);
                $negocio->fotos()->createMany($fotos);
            }

            return Redirect::back()->with('success', 'Negocio editado.');
        }
        return Redirect::back()->withErrors($validator)->withInput();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Negocio $negocio)
    {
        $negocio->delete();

        return Redirect::back()->with('success', 'Negocio eliminado.');
    }

    public function destroyFoto(Negocio $negocio, $foto)
    {
        $negocio->fotos()->where('id', $foto)->delete();

        return Redirect::back()->with('success', 'Foto eliminada.');
    }
    public function restore(Negocio $negocio)
    {
        $negocio->restore();

        return Redirect::back()->with('success', 'Negocio restaurado.');
    }
    public function validar(Negocio $negocio)
    {
        $this->authorize('validar', Negocio::class);

        $negocio->validado = Carbon::now();
        $negocio->save();

        return Redirect::back()->with('success', 'Negocio validado.');
    }
    public function novalidar(Negocio $negocio)
    {
        $this->authorize('validar', Negocio::class);

        $negocio->validado = null;
        $negocio->save();

        return Redirect::back()->with('success', 'Negocio no validado.');
    }
    public function refreshSlugs()
    {
        foreach (Negocio::all() as $at) {
            $at->touch();
        }
        return Redirect::route('negocios')->with('success', 'Negocio refreshSlugs.');
    }
    public function getNegociosComercio()
    {
        $response = Http::get('https://comercio.lapaz.gob.mx/api/turismo/negocios');
        $negocios = $response->json();

        $negociosRegistrados = Negocio::all()->toArray();

        $filterNegocios = array_filter($negocios, function ($v) use ($negociosRegistrados) {
            return !array_reduce($negociosRegistrados, static function ($carry, $item) use ($v) {
                return $carry === false && $item['id_comercio'] === $v['id'] ? $item : $carry;
            }, false);
        });

        return array_values($filterNegocios);
    }
}
