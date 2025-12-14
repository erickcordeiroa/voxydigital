<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
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
            'tenant_id' => 'required|exists:tenants,id',
            'customer_name' => 'required|string|max:255',   
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'delivery_address' => 'required|string',
            'note' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|integer|min:1',
            'items.*.variation_id' => 'nullable|exists:product_variations,id',
            'total' => 'required|integer|min:1',
            'tax_fixed' => 'nullable|integer|min:0',
            'payment_method' => 'required|in:pix,credit_card',
            'card_data' => 'required_if:payment_method,credit_card|array',
            'card_data.card_number' => 'required_if:payment_method,credit_card|string',
            'card_data.cardholder_name' => 'required_if:payment_method,credit_card|string',
            'card_data.cardholder_cpf' => 'required_if:payment_method,credit_card|string|size:11',
            'card_data.expiration_month' => 'required_if:payment_method,credit_card|string|size:2',
            'card_data.expiration_year' => 'required_if:payment_method,credit_card|string|min:2|max:4',
            'card_data.security_code' => 'required_if:payment_method,credit_card|string|min:3|max:4',
        ];
    }
}
