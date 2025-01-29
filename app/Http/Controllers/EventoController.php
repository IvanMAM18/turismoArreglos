<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventoRequest;
use App\Http\Requests\UpdateEventoRequest;
use App\Http\Resources\EventoCollection;
use App\Http\Resources\EventoResource;
use App\Models\Evento;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class EventoController extends Controller
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
        return Inertia::render('Admin/Evento/Index', [
            'filters' => Request::all('search', 'trashed'),
            'eventos' => new EventoCollection(
                Evento::orderBy('id')
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
        return Inertia::render('Admin/Evento/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventoRequest $request)
    {
        Evento::create(
            $request->validated()
        );

        return Redirect::route('eventos')->with('success', 'Evento registrado.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Evento $evento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Evento $evento)
    {
        return Inertia::render('Admin/Evento/Edit', [
            'evento' => new  EventoResource($evento),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventoRequest $request, Evento $evento)
    {
        $validator = Validator::make($request->all(), []);
        if ($request->imagen != null) {
            $validator = Validator::make($request->all(), [
                'imagen' => 'image'
            ]);
        }
        if ($validator->passes()) {
            if ($request->imagen != null) {
                $evento->update(
                    $request->validated()
                );
            } else {
                $evento->update(
                    $request->safe()->except(['imagen'])
                );
            }
            return Redirect::back()->with('success', 'Evento actualizado.');
        }

        return Redirect::back()->withErrors($validator)->withInput();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Evento $evento)
    {
        $evento->delete();

        return Redirect::back()->with('success', 'Evento eliminado.');
    }
    public function restore(Evento $evento)
    {
        $evento->restore();

        return Redirect::back()->with('success', 'Evento restaurado.');
    }

}
