<?php

namespace App\Http\Requests\Request;

use Illuminate\Foundation\Http\FormRequest;

class StoreReturnRequest extends FormRequest
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
            'borrow_id' => 'nullable|exists:borrows,id',
            'returner_id' => 'required|exists:engineers,id',
            'job_reference' => 'nullable|string|max:255',
            'note' => 'nullable|string',
            'details' => 'required|array',
            'details.*.tool_id' => 'required|exists:tools,id',
            'details.*.quantity' => 'required|integer|min:1',
            'details.*.image' => 'nullable|image|max:2048',
            'details.*.locator' => 'string|max:255',
        ];
    }
}
