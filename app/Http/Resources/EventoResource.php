<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventoResource extends JsonResource
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
            'alt' => $this->alt,
            'imagen' => $this->imagen,
            'enlace' => $this->enlace,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
