<?php

namespace App\Http\Requests\Broken;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBrokenToolsRequest extends FormRequest
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
            'tool_id' => 'required|exists:tools,id',
            'reported_by' => 'required|exists:engineers,id',
            'handled_by' => 'nullable|exists:engineers,id',
            'quantity' => 'required|integer|min:1',
            'locator' => 'nullable|string|max:255',
            'status' => 'required|string|max:50',
            'image' => 'nullable|image|max:2048',
            'last_used' => 'nullable|string|max:255',
            'issue' => 'nullable|string|max:255',
            'action' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'resolved_at' => 'nullable|date',
        ];
    }
}
