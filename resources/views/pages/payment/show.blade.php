<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage="payment-requests"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-navbars.navs.auth titlePage="طلبات الدفع"></x-navbars.navs.auth>
        <div class="container-fluid py-4">
            @if (session('status'))
                <div class="alert alert-success alert-dismissible text-white" role="alert">
                    <span class="text-sm">{{ Session::get('status') }}</span>
                    <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-lg" dir="rtl">
                        <div class="card-header bg-primary text-white text-right">
                            <h5 class="mb-0">تفاصيل طلب الدفع</h5>
                        </div>
                        <div class="card-body text-right">
                            <div class="row mb-2">
                                <div class="col-6 text-muted">المبلغ المطلوب:</div>
                                <div class="col-6">${{ number_format($payment->requested_amount, 2) }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6 text-muted">طريقة الدفع:</div>
                                <div class="col-6">{{ $payment->payment_method }}</div>
                            </div>

                            @switch($payment->payment_method)
                                @case('vodafone')
                                @case('orange')
                                @case('etisalat')
                                    <div class="row mb-2">
                                        <div class="col-6 text-muted">رقم الهاتف:</div>
                                        <div class="col-6">{{ $payment->phone }}</div>
                                    </div>
                                    @break

                                @case('banking')
                                    <div class="row mb-2">
                                        <div class="col-6 text-muted">اسم المستفيد:</div>
                                        <div class="col-6">{{ $payment->beneficiary_name }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-6 text-muted">اسم البنك:</div>
                                        <div class="col-6">{{ $payment->bank_name }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-6 text-muted">IBAN:</div>
                                        <div class="col-6">{{ $payment->iban }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-6 text-muted">كود SWIFT:</div>
                                        <div class="col-6">{{ $payment->swift_bio_code }}</div>
                                    </div>
                                    @break

                                @case('binance')
                                @case('payoneer')
                                    <div class="row mb-2">
                                        <div class="col-6 text-muted">البريد الإلكتروني:</div>
                                        <div class="col-6">{{ $payment->email_binance }}</div>
                                    </div>
                                    @break

                                @case('instaPay')
                                    <div class="row mb-2">
                                        <div class="col-6 text-muted">اسم المحفظة:</div>
                                        <div class="col-6">{{ $payment->wallet_name }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-6 text-muted">معرف المحفظة:</div>
                                        <div class="col-6">{{ $payment->wallet_id }}</div>
                                    </div>
                                    @break

                                @case('payeer')
                                @case('perfect-money')
                                    <div class="row mb-2">
                                        <div class="col-6 text-muted">معرف المحفظة:</div>
                                        <div class="col-6">{{ $payment->wallet_id }}</div>
                                    </div>
                                    @break
                            @endswitch
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-layout>
