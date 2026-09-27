<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShippingBoxSizeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'length'  => 'required|integer',
            'height'  => 'required|integer',
            'breadth' => 'required|integer',
            'courier_type' => 'required|string'
        ];
    }

    public function messages()
    {
        return [
            'courier_type.required' => translate('The courier type is required.'),
            'courier_type.string' => translate('The courier type must be a string'),
            'length.required' => translate('The box length is required.'),
            'length.integer' => translate('The box length  must be a integer.'),
            'height.required' => translate('The box height is required.'),
            'height.integer' => translate('The box height  must be a integer.'),
            'breadth.required' => translate('The box breadth is required.'),
            'breadth.integer' => translate('The box breadth  must be a integer.'),
        ];
    }
}
