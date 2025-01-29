<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAtractivoRequest;
use App\Http\Requests\StoreDelegacionRequest;
use App\Http\Requests\UpdateAtractivoRequest;
use App\Http\Requests\UpdateDelegacionRequest;
use App\Http\Resources\AtractivoCollection;
use App\Http\Resources\AtractivoResource;
use App\Http\Resources\CategoriaCollection;
use App\Http\Resources\DelegacionCollection;
use App\Http\Resources\DelegacionResource;
use App\Http\Resources\ServicioCollection;
use App\Models\Atractivo;
use App\Models\AtractivoServicio;
use App\Models\Categoria;
use App\Models\Delegacion;
use App\Models\Servicio;
use Carbon\Carbon;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class DelegacionController extends Controller
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
        return Inertia::render('Admin/Delegacion/Index', [
            'filters' => Request::all('search', 'trashed'),
            'delegaciones' => new AtractivoCollection(
                Delegacion::orderBy('nombre')
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
        return Inertia::render('Admin/Delegacion/Create', [
            'delegaciones' => Delegacion::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDelegacionRequest $request)
    {
        Delegacion::create(
            $request->validated()
        );

        return Redirect::route('delegaciones')->with('success', 'Delegacion registrada.');
    }

   

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Delegacion $delegacion)
    {
        return Inertia::render('Admin/Delegacion/Edit', [
            'delegacion' => new  DelegacionResource($delegacion),
            'delegaciones' => Delegacion::all(),

        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDelegacionRequest $request, Delegacion $delegacion)
    {
        $vs = [];
        if ($request->cover != null) {
            $vs['cover'] = 'image';
        }
        if ($request->principal != null) {
            $vs['principal'] = 'image';
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
                $delegacion->update(
                    $request->validated()
                );
            } else {
                $delegacion->update(
                    $request->safe()->except($except)
                );
            }
            return Redirect::back()->with('success', 'Delegacion actualizada.');
        }

        return Redirect::back()->withErrors($validator)->withInput();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Delegacion $delegacion)
    {
        $delegacion->delete();

        return Redirect::back()->with('success', 'Delegacion eliminado.');
    }
    public function restore(Delegacion $delegacion)
    {
        $delegacion->restore();

        return Redirect::back()->with('success', 'Delegacion restaurado.');
    }
}
