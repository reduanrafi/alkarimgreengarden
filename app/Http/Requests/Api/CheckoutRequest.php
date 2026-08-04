<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'division' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'upazila' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'address' => 'required|string|max:1000',
            'payment_method' => 'required|string|in:cash_on_delivery',
            'notes' => 'nullable|string|max:2000',
        ];
    }
}
