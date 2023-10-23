<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'name' =>'string|required|min:3|max:255',
            'slug' =>'string|required|min:3|max:255',
            'sku' =>'string|required|min:3|max:255',
            'brand_id' =>'numeric',
            'country_id' =>'numeric',
            'sub_category_id' =>'numeric',
            'child_sub_category_id' =>'numeric',
            'supplier_id' =>'numeric',
            'discount_fixed' =>'numeric',
            'discount_percent' =>'numeric',
            'category_id' =>'numeric',
            'cost' =>'numeric',
            'price' =>'numeric',
            'price_formula' =>'string',
            'field_limit' =>'string',
            'status' =>'numeric',
            'stock' =>'numeric',
            'isFeatured' =>'numeric',
            'isNew' =>'numeric',
            'isTrending' =>'numeric',
            'description' =>'max:1000',
            'attributes' =>'array',
            'specifications' =>'array',
        ];
    }
}
