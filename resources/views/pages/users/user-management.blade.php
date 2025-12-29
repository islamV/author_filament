<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="user-management"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="إداره المستخدمين"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            @if (session('status'))
                <div class="row">
                    <div class="alert alert-success alert-dismissible text-white" role="alert">
                        <span class="text-sm">{{ Session::get('status') }}</span>
                        <button type="button" class="btn-close text-lg py-3 opacity-10"
                                data-bs-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            @endif
            @if (Session::has('demo'))
                <div class="row">
                    <div class="alert alert-danger alert-dismissible text-white" role="alert">
                        <span class="text-sm">{{ Session::get('demo') }}</span>
                        <button type="button" class="btn-close text-lg py-3 opacity-10"
                                data-bs-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">

                        <div class=" me-3 my-3 text-end">
                            <a class="btn bg-gradient-dark mb-0" href="{{ route('admins.add') }}">
                                اضافه مستخدم<i class="material-icons text-sm">add</i>&nbsp;</a>
                        </div>
                        <form class="ms-2" method='get' action='{{ route('admins.list') }}' enctype="multipart/form-data" id="filter-form">
                            @csrf
                            <div class="row">
                                <div class="mb-3 col-md-2">
                                    <label class="form-label"> بحث</label>
                                    <input type="text" name="search" class="form-control border border-2 p-2" value='{{ request('search') }}'>
                                    @error('search')
                                    <p class='text-danger inputerror'>{{ $message }} </p>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-2">
                                    <label class="form-label">{{ __('form.status') }}</label>
                                    <select name="status" class="form-control border border-2 p-2">
                                        <option value="" @selected(request('status') == "")>-- اختر الحالة--</option>
                                        <option value="active" @selected( request('status') == "active")>{{ __('form.statuses.active') }}</option>
                                        <option value="pending" @selected( request('status') == "pending")>{{ __('form.statuses.pending') }}</option>
                                        <option value="suspended" @selected( request('status') == "suspended")>{{ __('form.statuses.suspended') }}</option>
                                    </select>
                                    @error('status')
                                    <p class='text-danger inputerror'>{{ $message }} </p>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-2">
                                    <label class="form-label">الدور</label>
                                    <select name="role_id" class="form-control border border-2 p-2">
                                        <option value="" @selected(request('role_id') == "")>-- حدد الدور --</option>
                                        <option value="1" @selected( request('role_id') == "1")>{{ __('form.roles.admin') }}</option>
                                        <option value="2" @selected( request('role_id') == "2")>{{ __('form.roles.verified_author') }}</option>
                                        <option value="3" @selected( request('role_id') == "3")>{{ __('form.roles.author') }}</option>
                                        <option value="4" @selected( request('role_id') == "4")>{{ __('form.roles.user') }}</option>
                                    </select>
                                    @error('status')
                                    <p class='text-danger inputerror'>{{ $message }} </p>
                                    @enderror
                                </div>
                            </div>
                            <button type="submit" class="btn bg-gradient-dark">فلتر</button>
                            <button type="button" onclick="resetFilters()" class="btn bg-gradient-secondary">إعادة تعيين</button>
                        </form>
                        <div class="card-body px-1 pb-0">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                الرقم التعريفي
                                            </th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                الصوره</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                الاسم</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                البيرد الالكتروني</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                الدور</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                تاريخ الانشاء
                                            </th>
                                            <th class="text-secondary opacity-7"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <p class="mb-0 text-sm">{{ $user->id }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div>
                                                        <a href="{{ asset('storage/' .$user->image) }}">
                                                        <img src="{{ asset('storage/' .$user->image) }}"
                                                            class="avatar avatar-sm me-3 border-radius-lg" alt="user1">
                                                        </a>
                                                    </div>

                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{$user->first_name}}  {{$user->last_name}}</h6>

                                                </div>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <p class="text-xs text-secondary mb-0">{{$user->email}}
                                                </p>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{$user->role->name}}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{ $user->created_at }}</span>
                                            </td>
                                            <td class="align-middle">
                                                <a rel="tooltip" class="btn btn-success btn-link"
                                                    href="{{ route('admins.edit' , $user->id) }}" data-original-title=""
                                                    title="">
                                                    <i class="material-icons">edit</i>
                                                    <div class="ripple-container"></div>
                                                </a>
                                                <form action="{{ route('admins.delete', $user->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-link" data-original-title="" title="">
                                                        <i class="material-icons">close</i>
                                                        <div class="ripple-container"></div>
                                                    </button>
                                                </form>
                                                <a rel="notification" class="btn btn-info btn-link"
                                                   href="{{ route('notification.user' , $user->id) }}" data-original-title=""
                                                   title="">
                                                    <i class="material-icons">notifications</i>
                                                    <div class="ripple-container"></div>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{ $users->withQueryString()->links('vendor.pagination.bootstrap-5') }}
        </div>
    </main>
    <script>
        function resetFilters() {
            // Get the form by id
            const form = document.getElementById('filter-form');

            // Reset all input fields
            const inputs = form.querySelectorAll('input');
            inputs.forEach(input => {
                input.value = '';
            });

            // Reset all select elements to their first option
            const selects = form.querySelectorAll('select');
            selects.forEach(select => {
                select.selectedIndex = 0;
            });

            // Submit the form to refresh the results
            form.submit();
        }
    </script>
</x-layout>
