<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceHistoryRequest extends FormRequest
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
            'customer_id' => 'required|integer|exists:customers,id',
            'invoice_number' => 'required|string|unique:service_histories,invoice_number',
            'warranty_expired_at' => 'required|date',
            'status' => 'required|string|in:pending,on_process,done',
            'details' => 'required|array',
            'details.*.kind' => 'required|string',
            'details.*.description' => 'required|string',
            'details.*.price' => 'required|integer',
        ];
    }
}
