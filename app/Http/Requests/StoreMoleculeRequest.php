<?php

namespace App\Http\Requests;

use App\Http\Controllers\ResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\ValidationException;

class StoreMoleculeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    use ResponseTrait;
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
            'name' => ['required', 'string', 'max:255', 'unique:molecules,name'],
            'slug' => ['required', 'string', 'max:255', 'unique:molecules,slug'],
        ];
    }

    public function messages(){
        return [
            'name.unique' => 'Name already exists',
            'slug.unique' => 'Name already exists',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'slug' =>preg_replace('/[\(\)%&\/\+_ ]/', '', strtolower($this->name)),
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
