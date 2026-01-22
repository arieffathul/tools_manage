<?php

namespace App\Http\Requests\Tool;

use Illuminate\Foundation\Http\FormRequest;

class UpdateToolRequest extends FormRequest
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
            'code' => 'required|string|max:100|unique:tools,code,'.$this->route('tool')->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'spec' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'quantity' => 'required|integer|min:0',
            'locator' => 'nullable|string|max:255',
            'current_quantity' => 'nullable|integer',
            'current_locator' => 'required|string|max:255',
            'last_audited_at' => 'nullable|date',
        ];
    }
}
