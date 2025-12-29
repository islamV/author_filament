<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage="manual_payments"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-navbars.navs.auth titlePage="المدفوعات اليدوية"></x-navbars.navs.auth>
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-header pb-0">
                            <h6>قائمة المدفوعات اليدوية</h6>
                        </div>
                        <div class="card-body px-1 pb-0">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">المعرف</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">المستخدم</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">طريقة الدفع</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">المبلغ</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">تاريخ الدفع</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">إيصال الدفع</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">الحالة</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">الإجراء</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($payments as $payment)
                                        <tr>
                                            <td>{{ $payment->id }}</td>
                                            <td>{{ $payment->user->first_name }} {{ $payment->user->last_name }}</td>
                                            <td>{{ ucfirst($payment->payment_method) }}</td>
                                            <td>{{ number_format($payment->amount, 2) }} ج.م</td>
                                            <td>{{ $payment->payment_date }}</td>
                                            <td>
                                                <a href="{{ asset('storage/' . $payment->payment_screen_shot) }}" target="_blank">
                                                    عرض الإيصال
                                                </a>
                                            </td>
                                            <td>
                                                <span class="badge badge-sm bg-{{ $payment->status == 'approved' ? 'success' : ($payment->status == 'rejected' ? 'danger' : 'warning') }}">
                                                    {{ ucfirst($payment->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($payment->status == 'pending')
                                                    <!-- Gold Subscription Button -->
                                                    <form action="{{ route('subscriptions.store') }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        <input type="hidden" name="user_id" value="{{ $payment->user_id }}">
                                                        <input type="hidden" name="payment_id" value="{{ $payment->id }}">
                                                        <input type="hidden" name="type" value="gold">
                                                        <button type="submit" class="btn btn-sm btn-warning">اشتراك ذهبي</button>
                                                    </form>

                                                    <!-- Silver Subscription Button -->
                                                    <form action="{{ route('subscriptions.store') }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        <input type="hidden" name="user_id" value="{{ $payment->user_id }}">
                                                        <input type="hidden" name="payment_id" value="{{ $payment->id }}">
                                                        <input type="hidden" name="type" value="silver">
                                                        <button type="submit" class="btn btn-sm btn-secondary">اشتراك فضي</button>
                                                    </form>

                                                    <!-- Reject Payment Button -->
                                                    <form action="{{ route('payments.reject', $payment->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-danger">رفض الدفع</button>
                                                    </form>
                                                @endif
                                            </td>

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    {{ $payments->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </main>
</x-layout>
