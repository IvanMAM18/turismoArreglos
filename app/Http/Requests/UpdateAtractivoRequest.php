<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAtractivoRequest extends FormRequest
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
            'horarios' => ['nullable', 'max:255'],
            'historia' => ['nullable', 'max:5000'],
            'leyenda' => ['nullable', 'max:5000'],
            'subtitulo' => ['nullable', 'max:255'],
            'latitud' => ['required', 'max:255'],
            'longitud' => ['required', 'max:255'],
            'tipo_acceso' => ['nullable', 'max:255'],
            'recomendaciones' => ['nullable', 'max:3000'],
            'cover' => ['sometimes'],
            'principal' => ['sometimes'],
            'fotos' => ['sometimes'],
            'servicios' => ['nullable'],
            'actividades' => ['nullable'],
            'id_categoria' => ['required'],
            'id_delegacion' => ['required'],
        ];
    }
}
