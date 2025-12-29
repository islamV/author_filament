<?php

namespace App\Http\Controllers\v1\Author\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Author\StoreRequestedPaymentRequest;
use App\Http\Resources\v1\Author\User\PaymentResource;
use App\Services\v1\Author\Payment\PaymentGatewayService;
use App\Services\v1\Author\Payment\PaymentService;
use Illuminate\Http\Request;


class PaymentController extends Controller
{
    protected PaymentGatewayService $gatewayService;
    protected PaymentService $paymentService;
    public function __construct(PaymentGatewayService $gatewayService,PaymentService $paymentService)
    {
        $this->gatewayService = $gatewayService;
        $this->paymentService = $paymentService;
    }

    public function index()
    {
        return $this->returnData(__( 'messages.payment.list'),200,
            $this->gatewayService->index());
    }

    public function getRequestedPayment(Request $request)
    {
        return $this->returnData(__( 'messages.payment.list'),200,
            PaymentResource::collection(
                $this->paymentService->index($request->get('per_page',10))));
    }

    public function store(StoreRequestedPaymentRequest $request)
    {
        $this->paymentService->store($request);
        return $this->success(__('messages.payment.requested'),200);
    }
}
