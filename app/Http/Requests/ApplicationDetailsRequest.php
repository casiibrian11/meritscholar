<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationDetailsRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'id' => 'nullable',
            'sy_id' => 'required|integer',
            'course_id' => 'required|integer',
            'year_level' => 'required|integer',
            'section' => 'nullable',
            'units_enrolled' => 'nullable',
            'gwa' => 'nullable'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'sy_id.required' => 'Invalid request. Please reload page.',
            'course_id.required' => 'You must select a course.',
        ];
    }
}
