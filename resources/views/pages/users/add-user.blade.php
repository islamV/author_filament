<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="user-management"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="إضافة مستخدم"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">

{{--                        <div class=" me-3 my-3 text-end">--}}
{{--                            <a class="btn bg-gradient-dark mb-0" href="javascript:;"><i--}}
{{--                                    class="material-icons text-sm">add</i>&nbsp;&nbsp;Add New--}}
{{--                                User</a>--}}
{{--                        </div>--}}
                        <div class="card-body px-0 pb-2">
                            <div class="card-body p-3">
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
                                <form method='POST' action='{{ route('admins.store') }}' enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label"> {{ __('form.first_name') }}</label>
                                            <input type="text" name="first_name" class="form-control border border-2 p-2" value=''>
                                            @error('first_name')
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                            @enderror
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label"> {{ __('form.last_name') }}</label>
                                            <input type="text" name="last_name" class="form-control border border-2 p-2" value=''>
                                            @error('last_name')
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                            @enderror
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">{{ __('form.email') }}</label>
                                            <input type="email" name="email" class="form-control border border-2 p-2" value=''>
                                            @error('email')
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                            @enderror
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">{{ __('form.phone') }}</label>
                                            <input type="number" name="phone" class="form-control border border-2 p-2" value=''>
                                            @error('phone')
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                            @enderror
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">{{ __('form.image') }}</label>
                                            <input type="file" name="image" class="form-control border border-2 p-2" value=''>
                                            @error('image')
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">{{ __('form.address') }}</label>
                                            <input type="text" name="address" class="form-control border border-2 p-2" value=''>
                                            @error('address')
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                            @enderror
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">{{ __('form.status') }}</label>
                                            <select name="status" class="form-control border border-2 p-2">
                                                <option value="active">{{ __('form.statuses.active') }}</option>
                                                <option value="pending">{{ __('form.statuses.pending') }}</option>
                                                <option value="suspended">{{ __('form.statuses.suspended') }}</option>
                                            </select>
                                            @error('status')
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">الدور</label>
                                            <select name="role" class="form-control border border-2 p-2">
                                                <option value="1">{{ __('form.roles.admin') }}</option>
                                                <option value="2">{{ __('form.roles.verified_author') }}</option>
                                                <option value="3">{{ __('form.roles.author') }}</option>
                                                <option value="4">{{ __('form.roles.user') }}</option>
                                            </select>
                                            @error('status')
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                            @enderror
                                        </div>
                                    </div>
                                    <button type="submit" class="btn bg-gradient-dark">{{ __('form.submit_button') }}</button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

</x-layout>
