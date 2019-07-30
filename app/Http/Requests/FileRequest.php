<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileRequest extends FormRequest
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
            // 'files.*' => 'mimes:pdf,docx,doc,png,jpg,jpeg,gif,odt|max:6000|required'
            'files' => 'mimes:pdf,docx,doc,png,jpg,jpeg,gif,odt|max:6000|required'
        ];
    }

    public function messages()
    {
        return [
            'files.*'.'max'=> __('Maximum upload file size is 6MB'),
            'files.*'.'mimes' => __('Unsupported File Type'),
            // 'file.max'=> __('Maximum upload file size is 6MB'),
            // 'file.mimes' => __('Unsupported File Type'),
            'files.required' => __('File is required')

        ];
    }
}
