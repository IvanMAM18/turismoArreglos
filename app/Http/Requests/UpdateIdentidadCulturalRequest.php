<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateIdentidadCulturalRequest extends FormRequest
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
            'descripcion' => ['required', 'max:3000'],
            'historia' => ['nullable', 'max:5000'],
            'leyenda' => ['nullable', 'max:5000'],
            'recomendaciones' => ['nullable', 'max:3000'],
            'cover' => ['sometimes'],
            'principal' => ['sometimes'],
            'fotos' => ['sometimes'],
            'id_delegacion' => ['required'],
        ];
    }
}
