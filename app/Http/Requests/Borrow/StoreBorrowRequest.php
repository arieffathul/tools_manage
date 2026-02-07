<?php

namespace App\Http\Requests\Borrow;

use Illuminate\Foundation\Http\FormRequest;

class StoreBorrowRequest extends FormRequest
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
            'engineer_id' => 'nullable|exists:engineers,id',
            'job_reference' => 'required|string|max:255',
            'is_completed' => 'nullable|integer',
            'image' => 'nullable|image|max:2048',
            'note' => 'nullable|string',
            'details' => 'required|array',
            'details.*.tool_id' => 'required|exists:tools,id',
            'details.*.quantity' => 'required|integer|min:1',
        ];
    }
}
