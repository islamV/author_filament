<?php

namespace App\Repositories\v1\Interface\Author\Payment;

interface IManualPayment
{
    public function get($method);
    public function store($model);

}
