<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationGeneralRequest extends FormRequest
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
            'first_name' => 'required',
            'last_name' => 'required',
            'martial_status' => 'required',
            'dependents' => 'required',
            'date_of_birth' => 'required',
            'home_phone' => 'required',
            'mobile_phone' => 'required',
            'email_address' => 'required'
        ];
    }
}
