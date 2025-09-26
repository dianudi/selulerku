<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExpenseRequest extends FormRequest
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
            'description' => 'required|string',
            'category' => 'required|string',
            'amount' => 'required|numeric',
            'expense_date' => 'required|date',
            'payment_method' => 'nullable|string',
            'receipt_image_path' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}
