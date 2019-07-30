<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HomeOwnershipRequest extends FormRequest
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
            'residential_status' => 'required',
            'title_of_property' => 'required',
            'monthly_payment' => 'required'
        ];
    }
}
