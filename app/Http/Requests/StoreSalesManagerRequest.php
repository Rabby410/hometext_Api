<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class StoreSalesManagerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'address'=> 'required|min:3|max:255',
            'area_id'=> 'required|numeric',
            'district_id'=> 'required|numeric',
            'division_id'=> 'required|numeric',
            'name'=> 'required|min:3|max:255',
            'bio'=> 'max:1000',
            'email'=> 'required|email',
            'landmark'=> 'max:255',
            'photo'=> 'required',
            'nid_photo'=> 'required',
            'nid'=> 'required',
            'phone'=> 'required|numeric',
            'shop_id'=> 'required|numeric',
            'employee_type'=> 'required|numeric',
            'password'=> [
                'required',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ],
        ];
    }
}
