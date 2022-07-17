<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
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
            'price'       => 'numeric:min:1',
            'include_vat' => 'numeric',
            'name'        => 'array',
            'description' => 'array',
            'display'     => 'boolean'
        ];
    }
}
