<?php

namespace App\Http\Requests;

use App\Rules\ValidMolecule;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class UpdateDraftProductMoleculeRequest extends FormRequest
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
            'molecules' => 'required|array',
            'molecules.*' => function ($attribute, $value, $fail) {
                $rule = new ValidMolecule($value);
                if (!$rule->passes($attribute, $value)) {
                    $fail($rule->message());
                }
            },
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
