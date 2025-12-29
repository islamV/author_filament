<?php

namespace App\Repositories\v1\Implementation\Author\Payment;

use App\Models\AuthorRequestPayment;
use App\Repositories\v1\Interface\Author\Payment\IPayment;
use Illuminate\Support\Facades\Auth;

class PaymentRepository implements IPayment
{

    public function get($limit)
    {
        return AuthorRequestPayment::where('user_id',Auth::user()->id)->get();
    }

    public function store($model)
    {
        return AuthorRequestPayment::create($model);
    }

}
