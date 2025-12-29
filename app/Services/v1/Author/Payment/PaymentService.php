<?php

namespace App\Services\v1\Author\Payment;


use App\Repositories\v1\Interface\Author\Payment\IPayment;

class PaymentService
{
    protected IPayment $payment;
    public function __construct(IPayment $payment)
    {
        $this->payment = $payment;
    }
    public function index($limit)
    {
        return $this->payment->get($limit);
    }

    public function store($request)
    {
        $payment = [
            'requested_amount' => $request->requested_amount,
            'payment_method' => $request->payment_method,
            'phone' => $request->phone,
            'beneficiary_name' => $request->beneficiary_name,
            'bank_name' => $request->bank_name,
            'iban' => $request->iban,
            'wallet_id' => $request->wallet_id,
            'wallet_name' => $request->wallet_name,
            'email_binance' => $request->email_binance,
            'beneficiary_address' => $request->beneficiary_address,
            'swift_bio_code' => $request->swift_bio_code,
        ];
        $this->payment->store($payment);
    }
}
