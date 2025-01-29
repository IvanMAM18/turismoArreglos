<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class UserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'max:50'],
            'email' => ['required'],
            'role' => ['required'],
            'password' => ['required','min:6'],
        ];
    }

    public function validated($key = null, $default = null)
    {
        if (!empty($this->password)) {
            return array_merge(parent::validated(), [
                'password' => Hash::make($this->password)
            ]);
        } else {
            return parent::validated();
        }
    }
}
