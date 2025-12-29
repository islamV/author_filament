<?php

namespace App\Repositories\v1\Implementation\Author\Payment;

use App\Models\PaymentGateway;
use App\Repositories\v1\Interface\Author\Payment\IPaymentGateway;

class PaymentGatewayRepository implements IPaymentGateway
{

    public function get()
    {
        return PaymentGateway::get();
    }

}
