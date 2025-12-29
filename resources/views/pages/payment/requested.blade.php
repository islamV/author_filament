<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage="payment-requests"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-navbars.navs.auth titlePage="طلبات الدفع"></x-navbars.navs.auth>
        <div class="container-fluid py-4">
            @if (session('status'))
                <div class="alert alert-success alert-dismissible text-white" role="alert">
                    <span class="text-sm">{{ Session::get('status') }}</span>
                    <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-header pb-0">
                            <h6>طلبات الدفع</h6>
                        </div>
                        <div class="card-body px-1 pb-0">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">الرقم التعريفي</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">المبلغ المطلوب</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">المستخدم</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">طريقة الدفع</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">الحالة</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">تاريخ الطلب</th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($payments as $payment)
                                        <tr>
                                            <td><p class="mb-0 text-sm">{{ $payment->id }}</p></td>
                                            <td><p class="mb-0 text-sm">${{ number_format($payment->requested_amount, 2) }}</p></td>
                                            <td><p class="mb-0 text-sm">{{ $payment->user->first_name }} {{ $payment->user->last_name }}</p></td>
                                            <td><p class="mb-0 text-sm">{{ $payment->payment_method }}</p></td>
                                            <td>
                                                <span class="badge badge-sm bg-{{ $payment->status == 'approved' ? 'success' : ($payment->status == 'pending' ? 'warning' : 'danger') }}">
                                                    {{ $payment->status == 'approved' ? 'موافق' : ($payment->status == 'pending' ? 'قيد الانتظار' : 'مرفوض') }}
                                                </span>
                                            </td>
                                            <td><p class="mb-0 text-sm">{{ $payment->created_at->format('Y-m-d') }}</p></td>
                                            <td class="align-middle">
                                                @if($payment->status == 'pending')
                                                    <button class="btn btn-success btn-link" data-bs-toggle="modal" data-bs-target="#approveModal{{ $payment->id }}" title="موافقه">
                                                        <i class="material-icons">check</i>
                                                    </button>
                                                    <button class="btn btn-danger btn-link" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $payment->id }}" title="رفض">
                                                        <i class="material-icons">close</i>
                                                    </button>
                                                @endif
                                                <a class="btn btn-info btn-link mx-1" href="{{route("payment.show",$payment->id)}}" title="عرض التفاصيل">
                                                    <i class="material-icons">visibility</i>
                                                </a>
                                            </td>
                                        </tr>

                                        <!-- Approve Modal -->
                                        <div class="modal fade" id="approveModal{{ $payment->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">تأكيد الموافقة</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">هل أنت متأكد أنك تريد الموافقة على هذا الطلب؟</div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                                        <form action="{{ route('requested-payments.approve', $payment->id) }}" method="get">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success">موافقة</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Reject Modal -->
                                        <div class="modal fade" id="rejectModal{{ $payment->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">تأكيد الرفض</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">هل أنت متأكد أنك تريد رفض هذا الطلب؟</div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                                        <form action="{{ route('requested-payments.reject', $payment->id) }}" method="GET">
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger">رفض</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{ $payments->links('vendor.pagination.bootstrap-5') }}
        </div>
    </main>
</x-layout>
