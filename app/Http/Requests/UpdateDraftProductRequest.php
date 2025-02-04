<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'product_name' => 'string|max:255',
            'product_code' => 'string|max:255|unique:draft_products,product_code',
            'manufacturer_name' => 'string|max:255',
            'mrp' => 'numeric|min:0',
            'category_id' => 'exists:categories,id',
            'is_banned' => 'boolean',
            'is_active' => 'boolean',
            'is_discontinued' => 'boolean',
            'is_assured' => 'boolean',
            'is_refrigerated' => 'boolean',
            'is_deleted' => 'boolean',
            'is_published' => 'boolean',
            'created_by' => 'exists:users,id',
            'updated_by' => 'nullable|exists:users,id',
            'deleted_by' => 'nullable|exists:users,id',
            'molecules' => '',
        ];
    }
}
