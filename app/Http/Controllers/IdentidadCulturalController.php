<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIdentidadCulturalRequest;
use App\Http\Requests\UpdateIdentidadCulturalRequest;
use App\Http\Resources\ActividadCollection;
use App\Http\Resources\IdentidadCulturalCollection;
use App\Http\Resources\IdentidadCulturalResource;
use App\Http\Resources\CategoriaCollection;
use App\Http\Resources\DelegacionCollection;
use App\Http\Resources\ServicioCollection;
use App\Models\Actividad;
use App\Models\IdentidadCultural;
use App\Models\Categoria;
use App\Models\Delegacion;
use App\Models\Servicio;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class IdentidadCulturalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Admin/IdentidadCultural/Index', [
            'filters' => Request::all('search', 'trashed'),
            'identidad_cultural' => new IdentidadCulturalCollection(
                IdentidadCultural::orderBy('nombre')
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
        return Inertia::render('Admin/IdentidadCultural/Create', [
            'delegaciones' => new DelegacionCollection(
                Delegacion::all()
            ),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIdentidadCulturalRequest $request)
    {
        $identidad_cultural = IdentidadCultural::create(
            $request->validated()
        );

        $fotos = $request->file('fotos');
        if ($fotos) {
            $fotos = array_map(fn ($q) => ['foto' => $q, 'id_identidad_cultural' => $identidad_cultural->id], $fotos);
            $identidad_cultural->fotos()->createMany($fotos);
        }


        return Redirect::route('identidad-cultural')->with('success', 'IdentidadCultural registrado.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(IdentidadCultural $identidad_cultural)
    {
        return Inertia::render('Admin/IdentidadCultural/Edit', [
            'identidad_cultural' => new  IdentidadCulturalResource($identidad_cultural->loadMissing(['fotos'])),
            'delegaciones' => new DelegacionCollection(
                Delegacion::all()
            ),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIdentidadCulturalRequest $request, IdentidadCultural $identidad_cultural)
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
                $identidad_cultural->update(
                    $request->validated()
                );
            } else {
                $identidad_cultural->update(
                    $request->safe()->except($except)
                );
            }

            $fotos = $request->file('fotos');
            if ($fotos) {
                $fotos = array_map(fn ($q) => ['foto' => $q, 'id_identidad_cultural' => $identidad_cultural->id], $fotos);
                $identidad_cultural->fotos()->createMany($fotos);
            }

            return Redirect::back()->with('success', 'IdentidadCultural editado.');
        }
        return Redirect::back()->withErrors($validator)->withInput();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IdentidadCultural $identidad_cultural)
    {
        $identidad_cultural->delete();

        return Redirect::back()->with('success', 'IdentidadCultural eliminado.');
    }

    public function destroyFoto(IdentidadCultural $identidad_cultural, $foto)
    {
        $identidad_cultural->fotos()->where('id', $foto)->delete();

        return Redirect::back()->with('success', 'Foto eliminada.');
    }
    public function restore(IdentidadCultural $identidad_cultural)
    {
        $identidad_cultural->restore();

        return Redirect::back()->with('success', 'IdentidadCultural restaurado.');
    }
    public function validar(IdentidadCultural $identidad_cultural)
    {
        $this->authorize('validar', IdentidadCultural::class);

        $identidad_cultural->validado = Carbon::now();
        $identidad_cultural->save();

        return Redirect::back()->with('success', 'IdentidadCultural validado.');
    }
    public function novalidar(IdentidadCultural $identidad_cultural)
    {
        $this->authorize('validar', IdentidadCultural::class);

        $identidad_cultural->validado = null;
        $identidad_cultural->save();

        return Redirect::back()->with('success', 'IdentidadCultural no validado.');
    }
    public function refreshSlugs()
    {
        foreach (IdentidadCultural::all() as $at) {
           $at->touch();
        }
        return Redirect::route('identidad-cultural')->with('success', 'IdentidadCultural refreshSlugs.');
    }
}
