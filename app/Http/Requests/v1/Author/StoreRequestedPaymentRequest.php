<?php

namespace App\Http\Requests\v1\Author;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequestedPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'requested_amount' => ['required', 'numeric', 'min:0'],
            'payment_method' => [
                'required',
                'string',
                'in:instaPay,vodafone,banking,payoneer,binance,perfect-money,payeer,orange,etisalat'
            ],
            'phone' => ['required_if:payment_method,vodafone,orange,etisalat','nullable', 'string'],

            'beneficiary_name' => ['required_if:payment_method,banking', 'nullable', 'string', 'max:255'],
            'bank_name' => ['required_if:payment_method,banking', 'nullable', 'string', 'max:255'],
            'iban' => ['required_if:payment_method,banking', 'nullable', 'string', 'regex:/^[A-Z0-9]{15,34}$/'],
            'swift_bio_code' => ['required_if:payment_method,banking', 'nullable', 'string', 'size:8'],
            'beneficiary_address' => ['nullable', 'string', 'max:255'],

            'email_binance' => ['required_if:payment_method,binance,payoneer', 'nullable', 'email', 'max:255'],

            'wallet_id' => ['required_if:payment_method,instaPay,payeer,perfect-money', 'nullable', 'string', 'max:255'],
            'wallet_name' => ['required_if:payment_method,instaPay', 'nullable', 'string', 'max:255'],

        ];
    }

    public function messages()
    {
        return [
            'requested_amount.required' => __('messages.requested_amount.required'),
            'requested_amount.numeric' => __('messages.requested_amount.numeric'),
            'requested_amount.min' => __('messages.requested_amount.min'),

            'payment_method.required' => __('messages.payment_method.required'),
            'payment_method.string' => __('messages.payment_method.string'),
            'payment_method.in' => __('messages.payment_method.in'),

            'phone.required_if' => __('messages.phone.required_if'),
            'phone.string' => __('messages.phone.string'),

            'beneficiary_name.required_if' => __('messages.beneficiary_name.required_if'),
            'beneficiary_name.string' => __('messages.beneficiary_name.string'),
            'beneficiary_name.max' => __('messages.beneficiary_name.max'),
            'bank_name.required_if' => __('messages.bank_name.required_if'),
            'bank_name.string' => __('messages.bank_name.string'),
            'bank_name.max' => __('messages.bank_name.max'),

            'iban.required_if' => __('messages.iban.required_if'),
            'iban.string' => __('messages.iban.string'),
            'iban.regex' => __('messages.iban.regex'),

            'swift_bio_code.required_if' => __('messages.swift_bio_code.required_if'),
            'swift_bio_code.string' => __('messages.swift_bio_code.string'),
            'swift_bio_code.size' => __('messages.swift_bio_code.size'),

            'beneficiary_address.string' => __('messages.beneficiary_address.string'),
            'beneficiary_address.max' => __('messages.beneficiary_address.max'),

            'email_binance.required_if' => __('messages.email_binance.required_if'),
            'email_binance.email' => __('messages.email_binance.email'),
            'email_binance.max' => __('messages.email_binance.max'),

            'wallet_id.required_if' => __('messages.wallet_id.required_if'),
            'wallet_id.string' => __('messages.wallet_id.string'),
            'wallet_id.max' => __('messages.wallet_id.max'),

            'wallet_name.required_if' => __('messages.wallet_name.required_if'),
            'wallet_name.string' => __('messages.wallet_name.string'),
            'wallet_name.max' => __('messages.wallet_name.max'),

        ];
    }

}
