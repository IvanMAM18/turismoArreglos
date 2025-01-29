<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class IdentidadCulturalResource extends JsonResource
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
            'descripcion' => $this->descripcion,
            'historia' => $this->historia,
            'leyenda' => $this->leyenda,
            'recomendaciones' => $this->recomendaciones,
            'id_delegacion' => $this->id_delegacion,
            'cover' => $this->cover,
            'principal' => $this->principal,
            'deleted_at' => $this->deleted_at,
            'validado' => $this->validado,
            'fotos' => $this->whenLoaded('fotos')->map(function ($item) {
                return collect($item)->only(['id','foto'])->all();
            }),
        ];
    }
}
