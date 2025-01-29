<?php

namespace App\Http\Requests;

use App\Traits\LockedDemoUser;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class UserUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'max:50'],
            'email' => ['required', 'max:50'],
            'role' => ['required'],
            'password' => ['sometimes'],
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
