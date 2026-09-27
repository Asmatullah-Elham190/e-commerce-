<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BrandRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->route('brand'); 
        return [
            'name' => 'required|max:50',
            'slug' => 'nullable|unique:brands,slug,' . $id,
            'logo' => 'nullable|integer',
            'meta_title' => 'nullable|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|max:255',
            'lang' => 'sometimes|required|string|max:10',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => translate('The brand name is required.'),
            'name.max' => translate('The brand name may not be greater than 50 characters.'),
            'slug.unique' => translate('The slug has already been taken.'),
            'logo.integer' => translate('The logo must be an image'),
            'meta_title.max' => translate('The meta title may not be greater than 255 characters.'),
            'meta_description.string' => translate('The meta description must be a string.'),
            'meta_keywords.max' => translate('The meta keywords may not be greater than 255 characters.'),
            'lang.required' => translate('The language field is required.'),
            'lang.string' => translate('The language must be a string.'),
            'lang.max' => translate('The language may not be greater than 10 characters.'),
        ];
    }
}
