<?php

namespace App\Http\Controllers;

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
use Inertia\Inertia;

use function PHPSTORM_META\map;

class WelcomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Delegacion::orderBy('orden')->get();
        foreach ($data as $element) {
            $element['cover'] = $element->cover;
            $element['principal'] = $element->principal;
        }

        $temp['c'] = '-';
        $temp['f'] = '-';

        if (Cache::has('temperatura')) {
            $temp = Cache::get('temperatura');
        } else {
            //https://api.open-meteo.com/v1/forecast?latitude=&longitude=&daily=weathercode,temperature_2m_max,temperature_2m_min&current_weather=true&forecast_days=1&timezone=auto
            try {
                $response = Http::withUrlParameters([
                    'endpoint' => 'https://api.open-meteo.com/v1',
                    'page' => 'forecast',
                    'latitude' => '24.1422',
                    'longitude' => '-110.3131',
                ])->get('{+endpoint}/{page}?latitude={latitude}&longitude={longitude}&daily=weathercode,temperature_2m_max,temperature_2m_min&current_weather=true&forecast_days=1&timezone=auto');

                if ($response->ok()) {
                    $temp['c'] = $response->json()['current_weather']['temperature'];
                    $temp['f'] = round($temp['c'] * 1.8 + 32, 2);

                    Cache::put('temperatura', $temp, 10 * 60);
                }
            } catch (ConnectionException $ex) {
            }
        }

        $fecha = Carbon::now()->locale('es')->isoFormat('dddd, DD [de] MMMM');
        $hora = Carbon::now()->locale('es')->isoFormat('h:mm a');

        return Inertia::render('Welcome', [
            'delegaciones' => $data,
            'temperatura' => $temp,
            'fecha' => $fecha,
            'hora' => $hora,
        ]);
    }

    public function getBanners()
    {
        return new BannerCollection(
            Banner::all()
        );
    }
    public function getEventos()
    {
        return  new EventoCollection(
            Evento::all()
        );
    }
    public function showAviso()
    {
        $data = Delegacion::orderBy('orden')->get();
        foreach ($data as $element) {
            $element['cover'] = $element->cover;
            $element['principal'] = $element->principal;
        }

        $temp['c'] = '-';
        $temp['f'] = '-';

        if (Cache::has('temperatura')) {
            $temp = Cache::get('temperatura');
        } else {
            //https://api.open-meteo.com/v1/forecast?latitude=&longitude=&daily=weathercode,temperature_2m_max,temperature_2m_min&current_weather=true&forecast_days=1&timezone=auto
            try {
                $response = Http::withUrlParameters([
                    'endpoint' => 'https://api.open-meteo.com/v1',
                    'page' => 'forecast',
                    'latitude' => '24.1422',
                    'longitude' => '-110.3131',
                ])->get('{+endpoint}/{page}?latitude={latitude}&longitude={longitude}&daily=weathercode,temperature_2m_max,temperature_2m_min&current_weather=true&forecast_days=1&timezone=auto');

                if ($response->ok()) {
                    $temp['c'] = $response->json()['current_weather']['temperature'];
                    $temp['f'] = round($temp['c'] * 1.8 + 32, 2);

                    Cache::put('temperatura', $temp, 10 * 60);
                }
            } catch (ConnectionException $ex) {
            }
        }

        $fecha = Carbon::now()->locale('es')->isoFormat('dddd, DD [de] MMMM');
        $hora = Carbon::now()->locale('es')->isoFormat('h:mm a');

        return Inertia::render('Aviso', [
            'delegaciones' => $data,
            'temperatura' => $temp,
            'fecha' => $fecha,
            'hora' => $hora,
        ]);
    }

    public function showTerminos()
    {
        $data = Delegacion::orderBy('orden')->get();
        foreach ($data as $element) {
            $element['cover'] = $element->cover;
            $element['principal'] = $element->principal;
        }

        $temp['c'] = '-';
        $temp['f'] = '-';

        if (Cache::has('temperatura')) {
            $temp = Cache::get('temperatura');
        } else {
            //https://api.open-meteo.com/v1/forecast?latitude=&longitude=&daily=weathercode,temperature_2m_max,temperature_2m_min&current_weather=true&forecast_days=1&timezone=auto
            try {
                $response = Http::withUrlParameters([
                    'endpoint' => 'https://api.open-meteo.com/v1',
                    'page' => 'forecast',
                    'latitude' => '24.1422',
                    'longitude' => '-110.3131',
                ])->get('{+endpoint}/{page}?latitude={latitude}&longitude={longitude}&daily=weathercode,temperature_2m_max,temperature_2m_min&current_weather=true&forecast_days=1&timezone=auto');

                if ($response->ok()) {
                    $temp['c'] = $response->json()['current_weather']['temperature'];
                    $temp['f'] = round($temp['c'] * 1.8 + 32, 2);

                    Cache::put('temperatura', $temp, 10 * 60);
                }
            } catch (ConnectionException $ex) {
            }
        }

        $fecha = Carbon::now()->locale('es')->isoFormat('dddd, DD [de] MMMM');
        $hora = Carbon::now()->locale('es')->isoFormat('h:mm a');

        return Inertia::render('Terminos', [
            'delegaciones' => $data,
            'temperatura' => $temp,
            'fecha' => $fecha,
            'hora' => $hora,
        ]);
    }
    /**
     * Display the specified resource.
     */
    public function showDelegacion(Delegacion $delegacion)
    {
        $temp['c'] = '-';
        $temp['f'] = '-';
        if (Cache::has('temperatura')) {
            $temp = Cache::get('temperatura');
        } else {
            //https://api.open-meteo.com/v1/forecast?latitude=&longitude=&daily=weathercode,temperature_2m_max,temperature_2m_min&current_weather=true&forecast_days=1&timezone=auto
            try {
                $response = Http::withUrlParameters([
                    'endpoint' => 'https://api.open-meteo.com/v1',
                    'page' => 'forecast',
                    'latitude' => '24.1422',
                    'longitude' => '-110.3131',
                ])->get('{+endpoint}/{page}?latitude={latitude}&longitude={longitude}&daily=weathercode,temperature_2m_max,temperature_2m_min&current_weather=true&forecast_days=1&timezone=auto');

                if ($response->ok()) {
                    $temp['c'] = $response->json()['current_weather']['temperature'];
                    $temp['f'] = round($temp['c'] * 1.8 + 32, 2);

                    Cache::put('temperatura', $temp, 10 * 60);
                }
            } catch (ConnectionException $ex) {
            }
        }

        $fecha = Carbon::now()->locale('es')->isoFormat('dddd, DD [de] MMMM');
        $hora = Carbon::now()->locale('es')->isoFormat('h:mm a');


        return Inertia::render('Delegacion', [
            'delegacion' => new  DelegacionResource($delegacion),
            'delegaciones' =>  Delegacion::all(),
            'temperatura' => $temp,
            'fecha' => $fecha,
            'hora' => $hora,
        ]);
    }


    public function showHoteles(Delegacion $delegacion)
    {
        $filtrados = $delegacion->negocios()
            ->whereHas('categorias', function ($q) {
                $q->where('id_categoria_negocio', 1);
            })->get();

        $result = [];
        foreach ($filtrados as $element) {
            if ($element->validado != null) {
                $element['cover'] = $element->cover;
                $element['principal'] = $element->principal;
                $result[] = $element;
            }
        }

        $temp['c'] = '-';
        $temp['f'] = '-';

        if (Cache::has('temperatura')) {
            $temp = Cache::get('temperatura');
        } else {
            //https://api.open-meteo.com/v1/forecast?latitude=&longitude=&daily=weathercode,temperature_2m_max,temperature_2m_min&current_weather=true&forecast_days=1&timezone=auto
            try {
                $response = Http::withUrlParameters([
                    'endpoint' => 'https://api.open-meteo.com/v1',
                    'page' => 'forecast',
                    'latitude' => '24.1422',
                    'longitude' => '-110.3131',
                ])->get('{+endpoint}/{page}?latitude={latitude}&longitude={longitude}&daily=weathercode,temperature_2m_max,temperature_2m_min&current_weather=true&forecast_days=1&timezone=auto');

                if ($response->ok()) {
                    $temp['c'] = $response->json()['current_weather']['temperature'];
                    $temp['f'] = round($temp['c'] * 1.8 + 32, 2);

                    Cache::put('temperatura', $temp, 10 * 60);
                }
            } catch (ConnectionException $ex) {
            }
        }

        $fecha = Carbon::now()->locale('es')->isoFormat('dddd, DD [de] MMMM');
        $hora = Carbon::now()->locale('es')->isoFormat('h:mm a');

        return Inertia::render('Negocios', [
            'negocios' => $result,
            'titulo' => "Hoteles",
            'delegaciones' => Delegacion::all(),
            'delegacion' => new DelegacionResource($delegacion),
            'temperatura' => $temp,
            'fecha' => $fecha,
            'hora' => $hora,
        ]);
    }

    public function showRestaurantes(Delegacion $delegacion)
    {
        $filtrados = $delegacion->negocios()
            ->whereHas('categorias', function ($q) {
                $q->where('id_categoria_negocio', 2);
            })->get();

        $result = [];
        foreach ($filtrados as $element) {
            if ($element->validado != null) {
                $element['cover'] = $element->cover;
                $element['principal'] = $element->principal;
                $result[] = $element;
            }
        }

        $temp['c'] = '-';
        $temp['f'] = '-';

        if (Cache::has('temperatura')) {
            $temp = Cache::get('temperatura');
        } else {
            //https://api.open-meteo.com/v1/forecast?latitude=&longitude=&daily=weathercode,temperature_2m_max,temperature_2m_min&current_weather=true&forecast_days=1&timezone=auto
            try {
                $response = Http::withUrlParameters([
                    'endpoint' => 'https://api.open-meteo.com/v1',
                    'page' => 'forecast',
                    'latitude' => '24.1422',
                    'longitude' => '-110.3131',
                ])->get('{+endpoint}/{page}?latitude={latitude}&longitude={longitude}&daily=weathercode,temperature_2m_max,temperature_2m_min&current_weather=true&forecast_days=1&timezone=auto');

                if ($response->ok()) {
                    $temp['c'] = $response->json()['current_weather']['temperature'];
                    $temp['f'] = round($temp['c'] * 1.8 + 32, 2);

                    Cache::put('temperatura', $temp, 10 * 60);
                }
            } catch (ConnectionException $ex) {
            }
        }

        $fecha = Carbon::now()->locale('es')->isoFormat('dddd, DD [de] MMMM');
        $hora = Carbon::now()->locale('es')->isoFormat('h:mm a');

        return Inertia::render('Negocios', [
            'negocios' => $result,
            'titulo' => "Restaurantes",
            'delegaciones' => Delegacion::all(),
            'delegacion' => new DelegacionResource($delegacion),
            'temperatura' => $temp,
            'fecha' => $fecha,
            'hora' => $hora,
        ]);
    }

    public function showExperiencias(Delegacion $delegacion)
    {
        $filtrados = $delegacion->negocios()
            ->whereHas('categorias', function ($q) {
                $q->where('id_categoria_negocio', 3);
            })->get();


        $result = [];
        foreach ($filtrados as $element) {
            if ($element->validado != null) {
                $element['cover'] = $element->cover;
                $element['principal'] = $element->principal;
                if (!isset($element->categoriaExperiencia)) {
                    $result[''][] = $element;
                } else {
                    $result[$element->categoriaExperiencia->nombre][] = $element;
                }
            }
        }
        $resultOrdenado = [];

        $categorias = CategoriaExperiencia::all();
        foreach ($categorias as $element) {
            $filterResult = array_filter($result, function ($k) use ($element) {
                return $k == $element->nombre;
            }, ARRAY_FILTER_USE_KEY);
            if ($filterResult) {
                $resultOrdenado[$element->nombre] = $filterResult[$element->nombre];
            }
        }

        $temp['c'] = '-';
        $temp['f'] = '-';

        if (Cache::has('temperatura')) {
            $temp = Cache::get('temperatura');
        } else {
            //https://api.open-meteo.com/v1/forecast?latitude=&longitude=&daily=weathercode,temperature_2m_max,temperature_2m_min&current_weather=true&forecast_days=1&timezone=auto
            try {
                $response = Http::withUrlParameters([
                    'endpoint' => 'https://api.open-meteo.com/v1',
                    'page' => 'forecast',
                    'latitude' => '24.1422',
                    'longitude' => '-110.3131',
                ])->get('{+endpoint}/{page}?latitude={latitude}&longitude={longitude}&daily=weathercode,temperature_2m_max,temperature_2m_min&current_weather=true&forecast_days=1&timezone=auto');

                if ($response->ok()) {
                    $temp['c'] = $response->json()['current_weather']['temperature'];
                    $temp['f'] = round($temp['c'] * 1.8 + 32, 2);

                    Cache::put('temperatura', $temp, 10 * 60);
                }
            } catch (ConnectionException $ex) {
            }
        }

        $fecha = Carbon::now()->locale('es')->isoFormat('dddd, DD [de] MMMM');
        $hora = Carbon::now()->locale('es')->isoFormat('h:mm a');

        return Inertia::render('Negocios', [
            'negocios' => $result,
            'titulo' => "Experiencias",
            'delegaciones' => Delegacion::all(),
            'delegacion' => new DelegacionResource($delegacion),
            'temperatura' => $temp,
            'fecha' => $fecha,
            'hora' => $hora,
        ]);
    }

    public function showArtesanias(Delegacion $delegacion)
    {
        $filtrados = $delegacion->negocios()
            ->whereHas('categorias', function ($q) {
                $q->where('id_categoria_negocio', 4);
            })->get();

        $result = [];
        foreach ($filtrados as $element) {
            if ($element->validado != null) {
                $element['cover'] = $element->cover;
                $element['principal'] = $element->principal;
                $result[] = $element;
            }
        }

        $temp['c'] = '-';
        $temp['f'] = '-';

        if (Cache::has('temperatura')) {
            $temp = Cache::get('temperatura');
        } else {
            //https://api.open-meteo.com/v1/forecast?latitude=&longitude=&daily=weathercode,temperature_2m_max,temperature_2m_min&current_weather=true&forecast_days=1&timezone=auto
            try {
                $response = Http::withUrlParameters([
                    'endpoint' => 'https://api.open-meteo.com/v1',
                    'page' => 'forecast',
                    'latitude' => '24.1422',
                    'longitude' => '-110.3131',
                ])->get('{+endpoint}/{page}?latitude={latitude}&longitude={longitude}&daily=weathercode,temperature_2m_max,temperature_2m_min&current_weather=true&forecast_days=1&timezone=auto');

                if ($response->ok()) {
                    $temp['c'] = $response->json()['current_weather']['temperature'];
                    $temp['f'] = round($temp['c'] * 1.8 + 32, 2);

                    Cache::put('temperatura', $temp, 10 * 60);
                }
            } catch (ConnectionException $ex) {
            }
        }

        $fecha = Carbon::now()->locale('es')->isoFormat('dddd, DD [de] MMMM');
        $hora = Carbon::now()->locale('es')->isoFormat('h:mm a');

        return Inertia::render('Negocios', [
            'negocios' => $result,
            'titulo' => "Artesanias",
            'delegaciones' => Delegacion::all(),
            'delegacion' => new DelegacionResource($delegacion),
            'temperatura' => $temp,
            'fecha' => $fecha,
            'hora' => $hora,
        ]);
    }

    public function showAtractivos(Delegacion $delegacion)
    {
        $result = [];
        foreach ($delegacion->atractivos as $element) {
            if ($element->validado != null) {
                $element['cover'] = $element->cover;
                $element['principal'] = $element->principal;
                $result[$element->categoria->nombre][] = $element;
            }
        }
        $resultOrdenado = [];

        $categorias = Categoria::orderBy('orden')->get();
        foreach ($categorias as $element) {
            $filterResult = array_filter($result, function ($k) use ($element) {
                return $k == $element->nombre;
            }, ARRAY_FILTER_USE_KEY);
            if ($filterResult) {
                $resultOrdenado[$element->nombre] = $filterResult[$element->nombre];
            }
        }

        $temp['c'] = '-';
        $temp['f'] = '-';

        if (Cache::has('temperatura')) {
            $temp = Cache::get('temperatura');
        } else {
            //https://api.open-meteo.com/v1/forecast?latitude=&longitude=&daily=weathercode,temperature_2m_max,temperature_2m_min&current_weather=true&forecast_days=1&timezone=auto
            try {
                $response = Http::withUrlParameters([
                    'endpoint' => 'https://api.open-meteo.com/v1',
                    'page' => 'forecast',
                    'latitude' => '24.1422',
                    'longitude' => '-110.3131',
                ])->get('{+endpoint}/{page}?latitude={latitude}&longitude={longitude}&daily=weathercode,temperature_2m_max,temperature_2m_min&current_weather=true&forecast_days=1&timezone=auto');

                if ($response->ok()) {
                    $temp['c'] = $response->json()['current_weather']['temperature'];
                    $temp['f'] = round($temp['c'] * 1.8 + 32, 2);

                    Cache::put('temperatura', $temp, 10 * 60);
                }
            } catch (ConnectionException $ex) {
            }
        }

        $fecha = Carbon::now()->locale('es')->isoFormat('dddd, DD [de] MMMM');
        $hora = Carbon::now()->locale('es')->isoFormat('h:mm a');

        return Inertia::render('Atractivos', [
            'atractivos' => $resultOrdenado,
            'delegaciones' => Delegacion::all(),
            'delegacion' => new DelegacionResource($delegacion),
            'temperatura' => $temp,
            'fecha' => $fecha,
            'hora' => $hora,
        ]);
    }

    public function showAtractivo(Atractivo $atractivo)
    {
        if ($atractivo->validado != null) {
            $temp['c'] = '-';
            $temp['f'] = '-';

            if (Cache::has('temperatura')) {
                $temp = Cache::get('temperatura');
            } else {
                //https://api.open-meteo.com/v1/forecast?latitude=&longitude=&daily=weathercode,temperature_2m_max,temperature_2m_min&current_weather=true&forecast_days=1&timezone=auto
                try {
                    $response = Http::withUrlParameters([
                        'endpoint' => 'https://api.open-meteo.com/v1',
                        'page' => 'forecast',
                        'latitude' => '24.1422',
                        'longitude' => '-110.3131',
                    ])->get('{+endpoint}/{page}?latitude={latitude}&longitude={longitude}&daily=weathercode,temperature_2m_max,temperature_2m_min&current_weather=true&forecast_days=1&timezone=auto');

                    if ($response->ok()) {
                        $temp['c'] = $response->json()['current_weather']['temperature'];
                        $temp['f'] = round($temp['c'] * 1.8 + 32, 2);

                        Cache::put('temperatura', $temp, 10 * 60);
                    }
                } catch (ConnectionException $ex) {
                }
            }

            $fecha = Carbon::now()->locale('es')->isoFormat('dddd, DD [de] MMMM');
            $hora = Carbon::now()->locale('es')->isoFormat('h:mm a');


            return Inertia::render('Atractivo', [
                'atractivo' => new  AtractivoResource($atractivo->loadMissing(['servicios.servicio', 'actividades.actividad', 'fotos'])),
                'delegaciones' =>  Delegacion::all(),
                'relacionados' =>  new AtractivoCollection(Atractivo::where('id_categoria', $atractivo->id_categoria)->whereNotNull('validado')->take(6)->get()),
                'temperatura' => $temp,
                'fecha' => $fecha,
                'hora' => $hora,
            ]);
        }
        abort(404);
    }

    public function showNegocio(Negocio $negocio)
    {
        if ($negocio->validado != null) {
            $temp['c'] = '-';
            $temp['f'] = '-';

            if (Cache::has('temperatura')) {
                $temp = Cache::get('temperatura');
            } else {
                //https://api.open-meteo.com/v1/forecast?latitude=&longitude=&daily=weathercode,temperature_2m_max,temperature_2m_min&current_weather=true&forecast_days=1&timezone=auto
                try {
                    $response = Http::withUrlParameters([
                        'endpoint' => 'https://api.open-meteo.com/v1',
                        'page' => 'forecast',
                        'latitude' => '24.1422',
                        'longitude' => '-110.3131',
                    ])->get('{+endpoint}/{page}?latitude={latitude}&longitude={longitude}&daily=weathercode,temperature_2m_max,temperature_2m_min&current_weather=true&forecast_days=1&timezone=auto');

                    if ($response->ok()) {
                        $temp['c'] = $response->json()['current_weather']['temperature'];
                        $temp['f'] = round($temp['c'] * 1.8 + 32, 2);

                        Cache::put('temperatura', $temp, 10 * 60);
                    }
                } catch (ConnectionException $ex) {
                }
            }

            $fecha = Carbon::now()->locale('es')->isoFormat('dddd, DD [de] MMMM');
            $hora = Carbon::now()->locale('es')->isoFormat('h:mm a');


            return Inertia::render('Negocio', [
                'negocio' => new  NegocioResource($negocio->loadMissing(['categorias', 'fotos'])),
                'delegaciones' =>  Delegacion::all(),
                'temperatura' => $temp,
                'fecha' => $fecha,
                'hora' => $hora,
            ]);
        }
        abort(404);
    }

    public function showIdentidadCulturals(Delegacion $delegacion)
    {
        $result = [];
        foreach ($delegacion->identidadcultural as $element) {
            if ($element->validado != null) {
                $element['cover'] = $element->cover;
                $element['principal'] = $element->principal;
                $result[] = $element;
            }
        }

        $temp['c'] = '-';
        $temp['f'] = '-';

        if (Cache::has('temperatura')) {
            $temp = Cache::get('temperatura');
        } else {
            //https://api.open-meteo.com/v1/forecast?latitude=&longitude=&daily=weathercode,temperature_2m_max,temperature_2m_min&current_weather=true&forecast_days=1&timezone=auto
            try {
                $response = Http::withUrlParameters([
                    'endpoint' => 'https://api.open-meteo.com/v1',
                    'page' => 'forecast',
                    'latitude' => '24.1422',
                    'longitude' => '-110.3131',
                ])->get('{+endpoint}/{page}?latitude={latitude}&longitude={longitude}&daily=weathercode,temperature_2m_max,temperature_2m_min&current_weather=true&forecast_days=1&timezone=auto');

                if ($response->ok()) {
                    $temp['c'] = $response->json()['current_weather']['temperature'];
                    $temp['f'] = round($temp['c'] * 1.8 + 32, 2);

                    Cache::put('temperatura', $temp, 10 * 60);
                }
            } catch (ConnectionException $ex) {
            }
        }

        $fecha = Carbon::now()->locale('es')->isoFormat('dddd, DD [de] MMMM');
        $hora = Carbon::now()->locale('es')->isoFormat('h:mm a');

        return Inertia::render('IdentidadCulturals', [
            'identidad_cultural' => $result,
            'delegaciones' => Delegacion::all(),
            'delegacion' => new DelegacionResource($delegacion),
            'temperatura' => $temp,
            'fecha' => $fecha,
            'hora' => $hora,
        ]);
    }


    public function showIdentidadCultural(IdentidadCultural $identidad_cultural)
    {
        if ($identidad_cultural->validado != null) {
            $temp['c'] = '-';
            $temp['f'] = '-';

            if (Cache::has('temperatura')) {
                $temp = Cache::get('temperatura');
            } else {
                //https://api.open-meteo.com/v1/forecast?latitude=&longitude=&daily=weathercode,temperature_2m_max,temperature_2m_min&current_weather=true&forecast_days=1&timezone=auto
                try {
                    $response = Http::withUrlParameters([
                        'endpoint' => 'https://api.open-meteo.com/v1',
                        'page' => 'forecast',
                        'latitude' => '24.1422',
                        'longitude' => '-110.3131',
                    ])->get('{+endpoint}/{page}?latitude={latitude}&longitude={longitude}&daily=weathercode,temperature_2m_max,temperature_2m_min&current_weather=true&forecast_days=1&timezone=auto');

                    if ($response->ok()) {
                        $temp['c'] = $response->json()['current_weather']['temperature'];
                        $temp['f'] = round($temp['c'] * 1.8 + 32, 2);

                        Cache::put('temperatura', $temp, 10 * 60);
                    }
                } catch (ConnectionException $ex) {
                }
            }

            $fecha = Carbon::now()->locale('es')->isoFormat('dddd, DD [de] MMMM');
            $hora = Carbon::now()->locale('es')->isoFormat('h:mm a');


            return Inertia::render('IdentidadCultural', [
                'identidad_cultural' => new IdentidadCulturalResource($identidad_cultural->loadMissing(['fotos'])),
                'delegaciones' =>  Delegacion::all(),
                'relacionados' =>  new IdentidadCulturalCollection(IdentidadCultural::whereNotNull('validado')->where('id', '!=', $identidad_cultural->id)->take(6)->get()),
                'temperatura' => $temp,
                'fecha' => $fecha,
                'hora' => $hora,
            ]);
        }
        abort(404);
    }
}
