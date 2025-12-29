<?php

namespace App\Services\v1\Author\Payment;

use App\Models\ManualPayment;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\ImageTrait;
use Carbon\Carbon;

class ManualPaymentService
{
    use ImageTrait;

    public function store(Request $request)
    {
        $request->validate([
            'subscription_id' => 'required|exists:subscriptions,id',
            'payment_screen_shot' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'amount' => 'required|numeric|min:0',
            'payment_date' =>  'required|date',
            'sender_number' => 'required|string|max:255',
            'payment_address' => 'nullable|string|max:255',
        ]);

        $subscription = Subscription::with('paymentGateway')
            ->where('id', $request->subscription_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $gateway = $subscription->paymentGateway;

        $paymentScreenShot = $this->saveImage(
            $request,
            'payment_screen_shot',
            'manual_payments'
        );

        $paymentDate = Carbon::parse($request->payment_date)->toDateString();

        ManualPayment::updateOrCreate(
            ['subscription_id' => $subscription->id],
            [
                'user_id' => Auth::id(),
                'subscription_id' => $subscription->id,
                'payment_screen_shot' => $paymentScreenShot,
                'amount' => $request->amount,
                'payment_date' => $paymentDate,
                'sender_number' => $request->sender_number,
                'payment_address' => $request->payment_address,

                // بيانات جاية من PaymentGateway
                'bank_name' => $gateway->bank_name,
                'account_number' => $gateway->account_number,
                'beneficiary_name' => $gateway->receiver_name,
                'wallet_id' => $gateway->phone_number,
                'wallet_name' => $gateway->payment_gateway,
            ]
        );
    }
}
