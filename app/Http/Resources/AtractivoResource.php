<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AtractivoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'nombre' => $this->nombre,
            'direccion' => $this->direccion,
            'descripcion' => $this->descripcion,
            'horarios' => $this->horarios,
            'historia' => $this->historia,
            'leyenda' => $this->leyenda,
            'subtitulo' => $this->subtitulo,
            'recomendaciones' => $this->recomendaciones,
            'id_categoria' => $this->id_categoria,
            'id_delegacion' => $this->id_delegacion,
            'latitud' => $this->latitud,
            'longitud' => $this->longitud,
            'tipo_acceso' => $this->tipo_acceso,
            'cover' => $this->cover,
            'principal' => $this->principal,
            'deleted_at' => $this->deleted_at,
            'validado' => $this->validado,
            'servicios' => AtractivoServicioResource::collection($this->whenLoaded('servicios')),
            'serviciosFull' => $this->whenLoaded('servicios')->map(function ($item) {
                return collect($item)->only(['id_servicio','icono'])->merge(collect($item['servicio'])->only(['nombre']))->all();
            }),
            'actividades' => AtractivoActividadResource::collection($this->whenLoaded('actividades')),
            'actividadesFull' => $this->whenLoaded('actividades')->map(function ($item) {
                return collect($item)->only(['id_actividad','icono'])->merge(collect($item['actividad'])->only(['nombre']))->all();
            }),
            'fotos' => $this->whenLoaded('fotos')->map(function ($item) {
                return collect($item)->only(['id','foto'])->all();
            }),
        ];
    }
}
