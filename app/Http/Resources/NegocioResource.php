<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NegocioResource extends JsonResource
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
            'redes_sociales' => $this->redes_sociales,
            'paginaweb' => $this->paginaweb,
            'contacto_telefono' => $this->contacto_telefono,
            'contacto_persona' => $this->contacto_persona,
            'contacto_correo' => $this->contacto_correo,
            'contacto_puesto' => $this->id_catcontacto_puestoegoria,
            'id_delegacion' => $this->id_delegacion,
            'id_categoria_experiencia' => $this->id_categoria_experiencia,
            'latitud' => $this->latitud,
            'longitud' => $this->longitud,
            'tipo' => $this->tipo,
            'id_comercio' => $this->id_comercio,
            'cover' => $this->cover,
            'principal' => $this->principal,
            'deleted_at' => $this->deleted_at,
            'validado' => $this->validado,
            'categorias' => NegocioCategoriaResource::collection($this->whenLoaded('categorias')),
            'categoriasFull' => $this->whenLoaded('categorias')->map(function ($item) {
                return collect($item)->only(['id_categoria_negocio','icono'])->merge(collect($item['categoria'])->only(['nombre']))->all();
            }),
            'fotos' => $this->whenLoaded('fotos')->map(function ($item) {
                return collect($item)->only(['id','foto'])->all();
            }),
        ];
    }
}
