<?php

namespace App\Repositories\v1\Interface\Author\Payment;

interface IPayment
{
    public function get($limit);
    public function store($model);

}
