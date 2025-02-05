<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class UpdateDraftProductRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

    public function rules(): array
    {
        return [
            'id'=>'required|exists:draft_products,id',
            'product_name' => 'string|max:255',
            // 'product_code' => 'string|max:255|unique:draft_products,product_code',
            'manufacturer_name' => 'string|max:255',
            'mrp' => 'numeric|min:0',
            'molecules' => '',
            'category_id' => 'exists:categories,id',
            'is_banned' => 'boolean',
            'is_active' => 'boolean',
            'is_discontinued' => 'boolean',
            'is_assured' => 'boolean',
            'is_refrigerated' => 'boolean',
            'is_deleted' => 'boolean',
            'is_published' => 'boolean',
            'updated_by' => 'required|exists:users,id',
        ];
    }

    public function prepareForValidation()
    {
        if ($this->has('molecules')) {
            $this->merge([
                'molecules' => array_map('intval', explode(',', $this->molecules)),
            ]);
        }

        $this->merge([
            'updated_by' => auth()->user()->id,
        ]);
    }

    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator, response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'errors' => $validator->errors()
        ], 422));
    }
}
