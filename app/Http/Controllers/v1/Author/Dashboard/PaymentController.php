<?php

namespace App\Http\Controllers\v1\Author\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Author\Auth\RegisterAuthorRequest;
use App\Models\AdminPaymentDetail;
use App\Models\AuthorRequestPayment;
use App\Models\ManualPayment;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function getRequestedPayment(Request $request)
    {
        $payments = AuthorRequestPayment::paginate(10);
        return view('pages.payment.requested' , get_defined_vars());
    }

    public function show(AuthorRequestPayment $payment)
    {
        return view('pages.payment.show' , compact("payment"));
    }

    public function approve(AuthorRequestPayment $payment)
    {
        $payment->update([
            'status' => 'approved'
        ]);
        return redirect()->back()->with('success' , 'Payment approved successfully');
    }

    public function reject(AuthorRequestPayment $payment)
    {
        $payment->update([
            'status' => 'rejected'
        ]);
        return redirect()->back()->with('success' , 'Payment rejected successfully');
    }



    // Payment details
    public function payment_details(Request $request)
    {
        $gateway = $request->query('gateway');

        // Define columns based on gateway
        $gatewayColumns = [
            'vodafone' => ['id', 'phone_number'],
            'instaPay' => ['id', 'receiver_name', 'account_number', 'bank_name'],
        ];

        // Check if the gateway exists in the array
        if (array_key_exists($gateway, $gatewayColumns)) {
            $details = AdminPaymentDetail::select($gatewayColumns[$gateway])->first();
        } else {
            $details = null; // Handle unknown gateway
        }
        return view('pages.payment.details', compact('details' , 'gateway'));
    }

    public function update_gateways_details(Request $request, $id)
    {
        $gateway = $request->query('gateway'); // Get the gateway from the request
        $details = AdminPaymentDetail::findOrFail($id); // Find the existing record

        // Validation rules based on the gateway
        $rules = ($gateway == 'vodafone')
            ? ['phone_number' => 'required|string|max:15']
            : [
                'receiver_name'   => 'required|string|max:255',
                'account_number'  => 'required|string|max:20',
                'bank_name'       => 'required|string|max:255',
            ];

        $validatedData = $request->validate($rules);

        // Update the existing record based on the gateway
        if ($gateway === 'vodafone') {
            if ($request->filled('phone_number')) {
                $details->update([
                    'phone_number' => $validatedData['phone_number'],
                ]);
            }
        } elseif ($gateway === 'instaPay') {
            $updateData = [];

            if ($request->filled('receiver_name')) {
                $updateData['receiver_name'] = $validatedData['receiver_name'];
            }
            if ($request->filled('account_number')) {
                $updateData['account_number'] = $validatedData['account_number'];
            }
            if ($request->filled('bank_name')) {
                $updateData['bank_name'] = $validatedData['bank_name'];
            }

            if (!empty($updateData)) {
                $details->update($updateData);
            }
        }

        return redirect()->route('payment.details', ['gateway' => $gateway])
            ->with('success', 'Payment details updated successfully.');
    }

    public function listManualPayments()
    {
        $payments = ManualPayment::paginate(10);
        return view('pages.payment.manual', compact('payments'));
    }

    public function reject_manual(ManualPayment $payment)
    {
        if ($payment->status !== 'pending') {
            return back()->with('error', 'لا يمكن رفض الدفع الذي تمت معالجته بالفعل.');
        }

        // Update payment status to 'rejected'
        $payment->update([
            'status' => 'rejected',
        ]);

        return back()->with('success', 'تم رفض الدفع بنجاح.');
    }
}
