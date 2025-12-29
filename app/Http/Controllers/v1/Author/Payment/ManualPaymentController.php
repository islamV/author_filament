<?php

namespace App\Http\Controllers\v1\Author\Payment;

use App\Http\Controllers\Controller;
use App\Services\v1\Author\Payment\ManualPaymentService;
use Illuminate\Http\Request;

class ManualPaymentController extends Controller
{
    protected ManualPaymentService $manualPaymentService;

    public function __construct(ManualPaymentService $manualPaymentService)
    {
        $this->manualPaymentService = $manualPaymentService;
    }

    /**
     * Store a new manual payment for a subscription.
     */
    public function store(Request $request)
    {
        $this->manualPaymentService->store($request);
        return $this->success(__('messages.payment.requested'), 200);
    }
}
