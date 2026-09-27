<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ColorRequest extends FormRequest
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
        $id = $this->route('id');
        return [
            'name' => ['required', 'max:100'],
            'code' => 'required|unique:colors,code,' . $id,
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
            'name.required' => translate('Color name is required.'),
            'name.max' => translate('Color name may not exceed 100 characters.'),
            'code.required' => translate('Color code is required.'),
            'code.unique' => translate('This color code already exists.'),
            'code.max' => translate('Color code may not exceed 20 characters.'),
        ];
    }

}
