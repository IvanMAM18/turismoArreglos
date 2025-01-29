<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBazarRequest extends FormRequest
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
            'nombre_contacto' => ['required', 'max:255'],
            'puesto_contacto' => ['required', 'max:255'],
            'direccion' => ['required', 'max:255'],
            'ciudad' => ['required', 'max:255'],
            'estado' => ['required', 'max:255'],
            'pais' => ['required', 'max:255'],
            'cp' => ['required', 'max:255'],
            'telefono_contacto' => ['required', 'max:255'],
            'correo' => ['required', 'max:255'],
            'sitio_web' => ['max:255'],
            'tipo_participante' => ['required', 'max:255'],
            'giro_empresa' => ['max:255'],
            'tipo_exposicion' => ['max:255'],
            'expo_nombre' => ['max:255'],
            'expo_correo' => ['max:255'],
            'expo_puesto' => ['max:255'],
            'expo_materiales' => ['max:255'],
            'negocio_descripcion' => ['max:500'],
            'negocio_tipo_venta' => ['max:255'],
            'negocio_tipo_empresa' => ['max:255'],
            'negocio_servicios' => ['max:255'],
            'negocio_asociacion' => ['max:500'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this['sitio_web'] == null) {
            $this['sitio_web'] = "";
        }
        if ($this['tipo_participante'] == "negocio") {
            $this->merge([
                'giro_empresa' => null,
                'tipo_exposicion' => null,
                'expo_nombre' => null,
                'expo_correo' => null,
                'expo_puesto' => null,
                'expo_materiales' => null,
            ]);
        } else {
            $this->merge([
                'negocio_descripcion' => null,
                'negocio_tipo_venta' => null,
                'negocio_tipo_empresa' => null,
                'negocio_servicios' => null,
                'negocio_asociacion' => null,
            ]);
        }
    }
}
