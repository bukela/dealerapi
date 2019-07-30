<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'name' => 'required',
            'role' => 'required',
            // 'email' => 'required|email|unique:users,email,'.$this->id,
            'email' => 'required|email',
            'password'   => 'sometimes|confirmed',
            // 'password' => 'sometimes|confirmed|min:8'
        ];
    }
}
