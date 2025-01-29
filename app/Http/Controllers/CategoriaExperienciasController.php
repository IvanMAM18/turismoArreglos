<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoriaRequest;
use App\Http\Requests\UpdateCategoriaRequest;
use App\Http\Resources\CategoriaCollection;
use App\Http\Resources\CategoriaResource;
use App\Models\Categoria;
use App\Models\CategoriaExperiencia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Illuminate\Support\Facades\Request;

class CategoriaExperienciasController extends Controller
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
        return Inertia::render('Admin/CategoriaExperiencia/Index', [
            'filters' => Request::all('search', 'trashed'),
            'categorias' => new CategoriaCollection(
                CategoriaExperiencia::orderBy('nombre')
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
        return Inertia::render('Admin/CategoriaExperiencia/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoriaRequest $request)
    {
        CategoriaExperiencia::create(
            $request->validated()
        );

        return Redirect::route('categorias-experiencia')->with('success', 'Categoria registrada.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CategoriaExperiencia $categoria)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CategoriaExperiencia $categoria)
    {
        return Inertia::render('Admin/CategoriaExperiencia/Edit', [
            'categoria' => new CategoriaResource($categoria),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoriaRequest $request, CategoriaExperiencia $categoria)
    {
        $categoria->update(
            $request->validated()
        );

        return Redirect::back()->with('success', 'Categoria actualizada.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CategoriaExperiencia $categoria)
    {
        $categoria->delete();

        return Redirect::back()->with('success', 'Categoria eliminada.');
    }
    public function restore(CategoriaExperiencia $categoria)
    {
        $categoria->restore();

        return Redirect::back()->with('success', 'Categoria restaurada.');
    }
}
