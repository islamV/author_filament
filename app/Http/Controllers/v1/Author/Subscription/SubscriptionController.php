<?php

namespace App\Http\Controllers\v1\Author\Subscription;

use Carbon\Carbon;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Models\PaymentGateway;
use App\Http\Controllers\Controller;
use App\Http\Resources\PlanResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\v1\Author\User\SubscriptionResource;

class SubscriptionController extends Controller
{
    use ResponseTrait;

    public function subscribe(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            //'payment_method' => 'required|string', // Stripe-related
            'payment_gateway_id' => 'required|exists:payment_gateways,id',
            'plan_id' => 'required|exists:plans,id',
        ]);

        $gateway = PaymentGateway::find($request->payment_gateway_id);

        if (!$gateway || !$gateway->is_active) {
            return $this->returnError('The selected payment gateway is not active', 422);
        }


        $existingSubscription = $user->subscriptions()->where('status', 'active')->first();

        if ($existingSubscription) {
            return $this->returnError('You already have an active subscription', 400);
        }

        $plan = Plan::find($request->plan_id);

        if (!$plan) {
            return $this->returnError('Invalid subscription', 400);
        }

        // Stripe-related code commented
        // $user->createOrGetStripeCustomer();
        // $user->updateDefaultPaymentMethod($request->payment_method);
        // $subscription = $user->newSubscription($plan->name, $price_id)
        //     ->create($request->payment_method)
        //     ->cancelAt(Carbon::now()->addDays(30));

        $subscription = Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'payment_gateway_id' => $request->payment_gateway_id,
            'status'  => 'pending',
            'end_date' => now()->addDays($plan->duration ?? 30),
        ]);

        return $this->returnData('Subscription created successfully', 200, new SubscriptionResource($subscription));
    }

    public function cancelSubscription()
    {
        $user = Auth::user();

        $subscription = $user->subscriptions()->where('status', 'active')->first();

        if ($subscription) {
            $subscription->update(['status' => 'cancelled']);
            // Stripe-related code commented
            // $user->subscription($subscription->plan->name)->cancelNow();
            return $this->success('Subscription canceled successfully', 200);
        }

        return $this->returnError('No active subscription to cancel', 400);
    }

    public function resumeSubscription()
    {
        $user = Auth::user();

        $subscription = $user->subscriptions()->where('status', 'cancelled')->first();

        if ($subscription) {
            $subscription->update(['status' => 'active']);
            // Stripe-related code commented
            // $user->subscription($subscription->plan->name)->resume();
            return $this->success('Subscription resumed successfully', 200);
        }

        return $this->returnError('No subscription to resume', 400);
    }

    public function checkSubscription()
    {
        $user = Auth::user();
        $subscription = $user->subscriptions()
            ->where('status', 'active')
            ->latest()
            ->first();

        $subscriptionData = [
            'subscribed'       => false,
            'plan_id'          => null,
            'end_date'         => null,
            'download_limit'   => null,
            'downloads_used'   => null,
            'subscription_can_download' => false,
        ];

        $trialData = [
            'is_trial_user'     => false,
            'trial_expires_at'  => null,
            'trial_can_download' => false,
        ];

        if ($user->is_trial_user && $user->trial_expires_at) {
            if (now()->lessThanOrEqualTo($user->trial_expires_at)) {
                $trialData['is_trial_user'] = true;
                $trialData['trial_expires_at'] = $user->trial_expires_at->toDateString();
                $trialData['trial_can_download'] = true;
            } elseif (now()->greaterThan($user->trial_expires_at)) {
                $user->update(['is_trial_user' => false]);
            }
        }

        if ($subscription) {
            $plan = $subscription->plan;

            $end_date = $subscription->end_date;

            if (now()->greaterThan($end_date) && $subscription->status === 'active') {
                $subscription->update(['status' => 'expired']);
            }

            if ($subscription->status === 'active') {
                $subscriptionData['subscribed'] = true;
                $subscriptionData['plan_id'] = $plan?->id;
                $subscriptionData['end_date'] = $end_date->toDateString();
                $subscriptionData['download_limit'] = $plan?->max_downloads;
                $subscriptionData['downloads_used'] = $subscription->downloads ?? 0;

                $subscriptionData['subscription_can_download'] = ($subscriptionData['downloads_used'] < $subscriptionData['download_limit']);
            }
        }

       
        $can_download = $trialData['trial_can_download'] || $subscriptionData['subscription_can_download'];

        $response = array_merge($subscriptionData, $trialData, ['can_download' => $can_download]);

        return $this->returnData('Subscription status', 200, $response);
    }







    public function plans()
    {
        $plans = Plan::all();
        return $this->returnData('Subscription plans', 200, PlanResource::collection($plans));
    }
}
