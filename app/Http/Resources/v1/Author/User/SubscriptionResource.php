<?php

namespace App\Http\Resources\v1\Author\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id, // Subscription ID
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
            ],
            'plan' => [
                'id' => $this->plan->id,
                'name' => $this->plan->name,
                'price' => $this->plan->price,
                'max_downloads' => $this->plan->max_downloads,
            ],
            'status' => $this->status,
            'payment_gateway' => $this->paymentGateway ? [
                'id' => $this->paymentGateway->id,
                'name' => $this->paymentGateway->payment_gateway,
            ] : null,
            'downloads' => $this->downloads,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
           // 'name' => $this->type, // Subscription type (e.g., gold, silver)
           // 'stripe_id' => $this->stripe_id, // Stripe subscription ID
           // 'stripe_status' => $this->stripe_status, // Status of the subscription on Stripe (e.g., active, past_due)
           // 'start_date' => $this->created_at->toDateString(), // Start date
           // 'end_date' => $this->ends_at ? $this->ends_at->toDateString() : null, // End date or null if ongoing
           // 'is_active' => $this->active(), // Check if the subscription is active
           // 'is_on_grace_period' => $this->onGracePeriod(), // If the subscription is in a grace period
            //'price' => $this->price, // Subscription price
//            'is_cancelled' => $this->cancelled(),
        ];
    }
}
