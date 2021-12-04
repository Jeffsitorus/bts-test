<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignUpRequest extends FormRequest
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
            'username' => 'required|string|min:5|max:50|unique:users',
            'password' => 'required|string|min:6',
            'email' => 'required|string|email|max:200|unique:users',
            'phone' => 'required|numeric|digits_between:8,20|unique:users',
            'country' => 'required|string|min:3|max:100',
            'city' => 'required|string|min:3|max:100',
            'postcode' => 'required|string|max:20',
            'name' => 'required|string|min:1|max:255',
            'address' => 'required|string',
        ];
    }
}
