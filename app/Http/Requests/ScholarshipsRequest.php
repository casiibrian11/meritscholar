<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScholarshipsRequest extends FormRequest
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
            'description' => 'required|string',
            'requirements' => 'nullable|array',
            'privilege' => 'nullable|integer',
            'is_per_semester' => 'nullable',
            'scholarship_category_id' => 'nullable|integer',
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
            'scholarship_category_id' => 'Select from scholarship categories.',
        ];
    }
}
