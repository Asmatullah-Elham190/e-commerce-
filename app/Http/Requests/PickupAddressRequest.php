<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PickupAddressRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'courier_type' => 'required|string',
            'address_nickname' => 'required|string|max:100',
        ];
    }

    public function messages()
    {
        return [
            'courier_type.required' => translate('The courier type is required.'),
            'courier_type.string' => translate('The courier type must be a string'),
            'address_nickname.required' => translate('The address nickname is required.'),
            'address_nickname.string' => translate('The address nickname must be a string'),
            'address_nickname.max' => translate('The address nickname may not be greater than 100 characters.'),
        ];
    }
}
