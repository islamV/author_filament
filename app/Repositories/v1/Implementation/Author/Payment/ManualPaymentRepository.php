<?php

namespace App\Repositories\v1\Implementation\Author\Payment;

use App\Models\AdminPaymentDetail;
use App\Models\AuthorRequestPayment;
use App\Models\ManualPayment;
use App\Models\PaymentGateway;
use App\Repositories\v1\Interface\Author\Payment\IManualPayment;
use App\Repositories\v1\Interface\Author\Payment\IPayment;
use Illuminate\Support\Facades\Auth;

class ManualPaymentRepository implements IManualPayment
{

    public function get($method)
    {
        if ($method == 'vodafone') {
            return AdminPaymentDetail::select('id', 'phone_number')->first();
        }
        elseif ($method == 'instaPay') {
            return AdminPaymentDetail::select('id', 'bank_name', 'account_number', 'receiver_name')->first();
        }
        else {
            return 'No payment method found';
        }
    }

    public function store($model)
    {
        return ManualPayment::create($model);
    }

}
