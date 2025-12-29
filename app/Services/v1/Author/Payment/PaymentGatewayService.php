<?php

namespace App\Services\v1\Author\Payment;

use App\Repositories\v1\Interface\Author\Payment\IPaymentGateway;

class PaymentGatewayService
{
    protected IPaymentGateway $paymentGateway;

    public function __construct(IPaymentGateway $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    public function index()
    {
        return $this->paymentGateway->get();
    }
}
