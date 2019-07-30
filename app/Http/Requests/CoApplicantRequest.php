<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CoApplicantRequest extends FormRequest
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
            'business_type' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'date_of_birth' => 'required',
            'home_phone' => 'required',
            'street_name' => 'required',
            'street_number' => 'required',
            'city' => 'required',
            'province' => 'required',
            'postal_code' => 'required',
            'relationship_to_applicant' => 'required'
        ];
    }
}
