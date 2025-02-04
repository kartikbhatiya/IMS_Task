<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

use App\http\Controllers\ResponseTrait;

use Illuminate\Validation\ValidationException;

class StoreDraftProductRequest extends FormRequest
{
    use ResponseTrait;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_name' => 'required|string|max:255',
            'product_code' => 'required|string|max:255|unique:draft_products,product_code',
            'manufacturer_name' => 'required|string|max:255',
            'mrp' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'is_banned' => 'boolean',
            'is_active' => 'boolean',
            'is_discontinued' => 'boolean',
            'is_assured' => 'boolean',
            'is_refrigerated' => 'boolean',
            'is_deleted' => 'boolean',
            'is_published' => 'boolean',
            'created_by' => 'required|exists:users,id',
            'updated_by' => 'nullable|exists:users,id',
            'deleted_by' => 'nullable|exists:users,id',
            'molecules' => 'required|string',
        ];
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