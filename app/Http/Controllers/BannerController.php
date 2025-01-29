<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBannerRequest;
use App\Http\Requests\UpdateBannerRequest;
use App\Http\Resources\BannerCollection;
use App\Http\Resources\BannerResource;
use App\Models\Banner;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class BannerController extends Controller
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
        return Inertia::render('Admin/Banner/Index', [
            'filters' => Request::all('search', 'trashed'),
            'banners' => new BannerCollection(
                Banner::orderBy('id')
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
        return Inertia::render('Admin/Banner/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBannerRequest $request)
    {
        Banner::create(
            $request->validated()
        );

        return Redirect::route('banners')->with('success', 'Banner registrado.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Banner $banner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Banner $banner)
    {
        return Inertia::render('Admin/Banner/Edit', [
            'banner' => new  BannerResource($banner),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBannerRequest $request, Banner $banner)
    {
        $validator = Validator::make($request->all(), []);
        if ($request->imagen != null) {
            $validator = Validator::make($request->all(), [
                'imagen' => 'image'
            ]);
        }
        if ($validator->passes()) {
            if ($request->imagen != null) {
                $banner->update(
                    $request->validated()
                );
            } else {
                $banner->update(
                    $request->safe()->except(['imagen'])
                );
            }
            return Redirect::back()->with('success', 'Banner actualizado.');
        }

        return Redirect::back()->withErrors($validator)->withInput();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $banner)
    {
        $banner->delete();

        return Redirect::back()->with('success', 'Banner eliminado.');
    }
    public function restore(Banner $banner)
    {
        $banner->restore();

        return Redirect::back()->with('success', 'Banner restaurado.');
    }

}
