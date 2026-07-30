<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'city' => 'nullable|string|max:255',
            'area' => 'nullable|string|max:255',
            'upazila' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'address' => 'required|string|max:1000',
            'payment_method' => 'required|string|in:cash_on_delivery,sslcommerz,card,mobile_banking',
            'shipping_method' => 'nullable|string|in:standard,express,store_pickup',
            'billing_same_as_shipping' => 'nullable|boolean',
            'save_address' => 'nullable|boolean',
            'notes' => 'nullable|string|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_name.required' => 'Please enter your full name.',
            'phone.required' => 'Please enter your phone number.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'division.required' => 'Please select your division.',
            'district.required' => 'Please enter your district.',
            'address.required' => 'Please enter your delivery address.',
            'payment_method.required' => 'Please select a payment method.',
            'payment_method.in' => 'Please select a valid payment method.',
        ];
    }
}
