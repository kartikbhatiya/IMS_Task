<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

use App\http\Controllers\ResponseTrait;

use Illuminate\Validation\ValidationException;

use App\Rules\ValidMolecule;

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
            'product_name' => 'required|string|max:255|unique:draft_products,product_name',
            'product_code' => 'required|string|max:255|unique:draft_products,product_code',
            'manufacturer_name' => 'required|string|max:255',
            'mrp' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'molecules' => 'required|array',
            'molecules.*' => function ($attribute, $value, $fail) {
                $rule = new ValidMolecule($value);
                if (!$rule->passes($attribute, $value)) {
                    $fail($rule->message());
                }
            },
            'is_banned' => 'boolean',
            'is_refrigerated' => 'boolean',
            'is_active' => 'boolean',
            'is_discontinued' => 'boolean',
            'is_assured' => 'boolean',
            'is_deleted' => 'boolean',
            'is_published' => 'boolean',
            'created_by' => 'required|exists:users,id',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('molecules')) {
            $this->merge([
                'molecules' => array_map('intval', explode(',', $this->molecules)),
            ]);
        }

        $this->merge([
            'created_by' => auth()->user()->id,
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