<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    final public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    final public function rules(): array
    {
        return [
            'name' => 'required|min:3|max:50|string',
            'category_id' => 'required|numeric',
            'slug' => 'required|min:3|max:50|string|unique:sub_categories',
            'serial' => 'required|numeric',
            'status' => 'required|numeric',
            'description' => 'max:200|string'
        ];
    }
}
