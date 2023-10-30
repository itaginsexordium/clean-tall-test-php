<?php

namespace App\Http\Requests;

use App\Rules\CustomIPValidation;
use Illuminate\Foundation\Http\FormRequest;

class HomeRequest extends FormRequest
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
            'ip' => 'nullable|ip',
            // 'as_json' => 'nullable|boolean',
            // 'ip' => ['required', new CustomIPValidation],
        ];
    }
}
