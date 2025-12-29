<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage="subscribers"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-navbars.navs.auth titlePage="المشتركين"></x-navbars.navs.auth>
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-header pb-0">
                            <h6>قائمة المشتركين</h6>
                        </div>
                        <div class="card-body px-1 pb-0">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">المعرف</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">المستخدم</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">البريد الإلكتروني</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">الخطة</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">تاريخ الاشتراك</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">الحالة</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($subscribers as $subscription)
                                        <tr>
                                            <td>{{ $subscription->id }}</td>
                                            <td>{{ $subscription->user->first_name }} {{ $subscription->user->last_name }}</td>
                                            <td>{{ $subscription->user->email }}</td>
                                            <td>{{ ucfirst($subscription->type) }}</td>
                                            <td>{{ $subscription->created_at->format('Y-m-d') }}</td>
                                            <td>
                                                <span class="badge badge-sm bg-{{ $subscription->stripe_status == 'active' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($subscription->stripe_status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    {{ $subscribers->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </main>
</x-layout>
