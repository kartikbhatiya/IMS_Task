<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\ValidMolecule;
use PHPUnit\Framework\Constraint\IsTrue;

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
}
