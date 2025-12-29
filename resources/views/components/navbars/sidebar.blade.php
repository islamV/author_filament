@props(['activePage'])

<aside
    class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark"
    id="sidenav-main">
    <div class="sidenav-header">

    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="w-auto  max-height-vh-100" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">قسم المستخدمين</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ $activePage == 'user-profile' ? ' active bg-gradient-primary' : '' }} "
                   href="{{ route('admins.profile') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">
                            account_circle
                        </i>
                    </div>
                    <span class="nav-link-text ms-1">الملف الشخصي</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ $activePage == 'user-management' ? ' active bg-gradient-primary' : '' }} "
                    href="{{ route('admins.list') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i  class="material-icons opacity-10">person</i>
                    </div>
                    <span class="nav-link-text ms-1">اداره المستخدمين</span>
                </a>
            </li>
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">الصفح</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ $activePage == 'category' ? ' active bg-gradient-primary' : '' }} "
                   href="{{ route('categories.list') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">category</i>
                    </div>
                    <span class="nav-link-text ms-1">الاقسام</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ $activePage == 'ads' ? ' active bg-gradient-primary' : '' }} "
                   href="{{ route('ads.list') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">campaign</i>
                    </div>
                    <span class="nav-link-text ms-1">الاعلانات</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ $activePage == 'books' ? ' active bg-gradient-primary' : '' }} "
                   href="{{ route('books.list') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">book</i>
                    </div>
                    <span class="nav-link-text ms-1">الكتب</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ $activePage == 'notifications' ? ' active bg-gradient-primary' : '' }}  "
                    href="{{ route('notifications') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">notifications</i>
                    </div>
                    <span class="nav-link-text ms-1">الاشعارات</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ $activePage == 'chats' ? ' active bg-gradient-primary' : '' }}  "
                    href="{{ route('chat') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">chat</i>
                    </div>
                    <span class="nav-link-text ms-1">المحادثات</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ $activePage == 'payment-requests' ? ' active bg-gradient-primary' : '' }}  "
                    href="{{ route('requested-payments') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">payment</i>
                    </div>
                    <span class="nav-link-text ms-1">طلبات السحب</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ $activePage == 'subscribers' ? ' active bg-gradient-primary' : '' }}  "
                    href="{{ route('subscribers.index') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">subscriptions</i>
                    </div>
                    <span class="nav-link-text ms-1">الاشتراكات</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ $activePage == 'payment_details' ? ' active bg-gradient-primary' : '' }} "
                   href="{{ route('payment.details') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">attach_money </i>
                    </div>
                    <span class="nav-link-text ms-1">تفاصيل الدفع</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ $activePage == 'manual_payments' ? ' active bg-gradient-primary' : '' }} "
                   href="{{ route('payment.manual') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">receipt</i>
                    </div>
                    <span class="nav-link-text ms-1">الدفع اليدوي</span>
                </a>
            </li>
        </ul>
    </div>
{{--    <div class="sidenav-footer position-absolute w-100 bottom-0 ">--}}

{{--    </div>--}}
</aside>
