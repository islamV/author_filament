<?php

namespace App\Http\Controllers\v1\Author\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ManualPayment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Laravel\Cashier\Subscription;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscribers = Subscription::with('user')->orderBy('created_at', 'desc')
//            ->where('stripe_status', 'active') // Fetch active subscriptions
            ->paginate(10);

        return view('subscriptions.index', compact('subscribers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:gold,silver',
            'payment_id' => 'required|exists:manual_payments,id', // Validate payment_id
        ]);

        // Find the specific payment
        $payment = ManualPayment::where('id', $validated['payment_id'])
            ->where('user_id', $validated['user_id'])
            ->where('status', 'pending')
            ->first();

        if (!$payment) {
            return back()->with('error', 'لم يتم العثور على هذه الدفعة المعلقة.');
        }

        // Create the subscription
        Subscription::create([
            'user_id' => $validated['user_id'],
            'type' => $validated['type'],
            'stripe_id' => null, // Since it's manual
            'stripe_status' => 'active',
            'ends_at' => Carbon::now()->addMonth(),
        ]);

        // Update this specific payment's status to approved
        $payment->update(['status' => 'approved']);

        return back()->with('success', 'تم إنشاء الاشتراك والموافقة على هذه الدفعة بنجاح.');
    }
}
