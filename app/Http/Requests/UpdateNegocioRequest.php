<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNegocioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'nombre' => ['required', 'max:255'],
            'direccion' => ['required', 'max:255'],
            'descripcion' => ['required', 'max:3000'],
            'latitud' => ['required', 'max:255'],
            'longitud' => ['required', 'max:255'],
            'categorias' => ['nullable'],
            'redes_sociales' => ['nullable', 'max:255'],
            'paginaweb' => ['nullable', 'max:255'],
            'contacto_telefono' => ['nullable', 'max:255'],
            'contacto_persona' => ['nullable', 'max:255'],
            'contacto_correo' => ['nullable', 'max:255'],
            'contacto_puesto' => ['nullable', 'max:255'],
            'id_comercio' => ['required', 'integer'],
            'cover' => ['sometimes'],
            'principal' => ['sometimes'],
            'fotos' => ['sometimes'],
            'id_delegacion' => ['required'],
            'id_categoria_experiencia' => ['sometimes'],
        ];
    }
}