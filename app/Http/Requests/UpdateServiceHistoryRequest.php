<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceHistoryRequest extends FormRequest
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
            'total_revision' => 'required|integer',
            'status' => 'required|string|in:pending,on_process,done',
            'details' => 'required|array',
            'details.*.id' => 'required|exists:service_details,id',
            'details.*.kind' => 'required|string',
            'details.*.description' => 'required|string',
            'details.*.price' => 'required|integer',
            'details.*.cost_price' => 'required|integer|min:0',
            'details.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
}
