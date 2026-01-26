<?php

namespace App\Http\Requests\Engineer;

use Illuminate\Foundation\Http\FormRequest;

class StoreEngineerRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'shift' => 'required|string|max:100',
            'status' => 'nullable|string|max:100',
            'inactived_at' => 'nullable|date',
        ];
    }
}
