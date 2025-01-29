<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAtractivoRequest;
use App\Http\Requests\UpdateAtractivoRequest;
use App\Http\Resources\ActividadCollection;
use App\Http\Resources\AtractivoCollection;
use App\Http\Resources\AtractivoResource;
use App\Http\Resources\CategoriaCollection;
use App\Http\Resources\DelegacionCollection;
use App\Http\Resources\ServicioCollection;
use App\Models\Actividad;
use App\Models\Atractivo;
use App\Models\Categoria;
use App\Models\Delegacion;
use App\Models\Servicio;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class AtractivoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Admin/Atractivo/Index', [
            'filters' => Request::all('search', 'trashed'),
            'atractivos' => new AtractivoCollection(
                Atractivo::orderBy('nombre')
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
        return Inertia::render('Admin/Atractivo/Create', [
            'servicios' => new ServicioCollection(
                Servicio::all()
            ),
            'actividades' => new ActividadCollection(
                Actividad::all()
            ),
            'categorias' => new CategoriaCollection(
                Categoria::all()
            ),
            'delegaciones' => new DelegacionCollection(
                Delegacion::all()
            ),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAtractivoRequest $request)
    {
        $atractivo = Atractivo::create(
            $request->validated()
        );

        $servicios = json_decode($request->input('servicios'));
        if ($servicios) {
            $servicios = array_map(fn ($q) => ['id_servicio' => $q->id, 'id_atractivo' => $atractivo->id], $servicios);
            $atractivo->servicios()->createMany($servicios);
        }

        $actividades = json_decode($request->input('actividades'));
        if ($actividades) {
            $actividades = array_map(fn ($q) => ['id_actividad' => $q->id, 'id_atractivo' => $atractivo->id], $actividades);
            $atractivo->actividades()->createMany($actividades);
        }


        $fotos = $request->file('fotos');
        if ($fotos) {
            $fotos = array_map(fn ($q) => ['foto' => $q, 'id_atractivo' => $atractivo->id], $fotos);
            $atractivo->fotos()->createMany($fotos);
        }


        return Redirect::route('atractivos')->with('success', 'Atractivo registrado.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Atractivo $atractivo)
    {
        return Inertia::render('Admin/Atractivo/Edit', [
            'atractivo' => new  AtractivoResource($atractivo->loadMissing(['servicios.servicio', 'actividades.actividad', 'fotos'])),
            'servicios' => new ServicioCollection(
                Servicio::all()
            ),
            'actividades' => new ActividadCollection(
                Actividad::all()
            ),
            'categorias' => new CategoriaCollection(
                Categoria::all()
            ),
            'delegaciones' => new DelegacionCollection(
                Delegacion::all()
            ),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAtractivoRequest $request, Atractivo $atractivo)
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
                $atractivo->update(
                    $request->validated()
                );
            } else {
                $atractivo->update(
                    $request->safe()->except($except)
                );
            }

            $atractivo->servicios()->forceDelete();
            $servicios = json_decode($request->input('servicios'));
            if ($servicios) {
                $servicios = array_map(fn ($q) => ['id_servicio' => $q, 'id_atractivo' => $atractivo->id], $servicios);
                $atractivo->servicios()->createMany($servicios);
            }

            $atractivo->actividades()->forceDelete();
            $actividades = json_decode($request->input('actividades'));
            if ($actividades) {
                $actividades = array_map(fn ($q) => ['id_actividad' => $q, 'id_atractivo' => $atractivo->id], $actividades);
                $atractivo->actividades()->createMany($actividades);
            }

            $fotos = $request->file('fotos');
            if ($fotos) {
                $fotos = array_map(fn ($q) => ['foto' => $q, 'id_atractivo' => $atractivo->id], $fotos);
                $atractivo->fotos()->createMany($fotos);
            }

            return Redirect::back()->with('success', 'Atractivo editado.');
        }
        return Redirect::back()->withErrors($validator)->withInput();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Atractivo $atractivo)
    {
        $atractivo->delete();

        return Redirect::back()->with('success', 'Atractivo eliminado.');
    }

    public function destroyFoto(Atractivo $atractivo, $foto)
    {
        $atractivo->fotos()->where('id', $foto)->delete();

        return Redirect::back()->with('success', 'Foto eliminada.');
    }
    public function restore(Atractivo $atractivo)
    {
        $atractivo->restore();

        return Redirect::back()->with('success', 'Atractivo restaurado.');
    }
    public function validar(Atractivo $atractivo)
    {
        $this->authorize('validar', Atractivo::class);

        $atractivo->validado = Carbon::now();
        $atractivo->save();

        return Redirect::back()->with('success', 'Atractivo validado.');
    }
    public function novalidar(Atractivo $atractivo)
    {
        $this->authorize('validar', Atractivo::class);

        $atractivo->validado = null;
        $atractivo->save();

        return Redirect::back()->with('success', 'Atractivo no validado.');
    }
    public function refreshSlugs()
    {
        foreach (Atractivo::all() as $at) {
           $at->touch();
        }
        return Redirect::route('atractivos')->with('success', 'Atractivo refreshSlugs.');
    }
}
