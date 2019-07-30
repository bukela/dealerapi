<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmploymentRequest extends FormRequest
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
            'employment_status' => 'required',
            'company_name' => 'required',
            'company_address' => 'required',
            'company_city' => 'required',
            'company_province' => 'required',
            'company_telephone' => 'required',
            'company_type_of_business' => 'required',
            'company_salary' => 'required',
            'company_salary_type' => 'required',
            'company_years_of_employment' => 'required',
            'company_position' => 'required'
        ];
    }
}
