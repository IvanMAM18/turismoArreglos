<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreActividadRequest;
use App\Http\Requests\UpdateActividadRequest;
use App\Http\Resources\ActividadCollection;
use App\Http\Resources\ActividadResource;
use App\Models\Actividad;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class ActividadController extends Controller
{

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isAdmin()) {
                return redirect()->route('dashboard')->with('error','Sin acceso.');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Admin/Actividad/Index', [
            'filters' => Request::all('search', 'trashed'),
            'actividades' => new ActividadCollection(
                Actividad::orderBy('nombre')
                    ->filter(Request::only('search', 'trashed'))
                    ->paginate()
                    ->appends(Request::all())
            )
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Actividad/Create', [

        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreActividadRequest $request)
    {
        Actividad::create(
            $request->validated()
        );

        return Redirect::route('actividades')->with('success', 'Actividad registrada.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Actividad $actividad)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Actividad $actividad)
    {
        return Inertia::render('Admin/Actividad/Edit', [
            'actividad' => new  ActividadResource($actividad),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateActividadRequest $request, Actividad $actividad)
    {
        $actividad->update(
            $request->validated()
        );

        return Redirect::back()->with('success', 'Actividad actualizada.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Actividad $actividad)
    {
        $actividad->delete();

        return Redirect::back()->with('success', 'Actividad eliminada.');
    }
    public function restore(Actividad $actividad)
    {
        $actividad->restore();

        return Redirect::back()->with('success', 'Actividad restaurada.');
    }
}
