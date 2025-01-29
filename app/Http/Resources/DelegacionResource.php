<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DelegacionResource extends JsonResource
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
            'leyenda' => $this->leyenda,
            'cover' => $this->cover,
            'principal' => $this->principal,
            'descripcion' => $this->descripcion,
            'id_delegacion_padre' => $this->id_delegacion_padre,
        ];
    }
}
