<x-layout bodyClass="g-sidenav-show bg-gray-200">

    <x-navbars.sidebar activePage="user-profile"></x-navbars.sidebar>
    <div class="main-content position-relative bg-gray-100 max-height-vh-100 h-100">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage='ملف المستخدم'></x-navbars.navs.auth>

        <div class="">
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
            <!-- End Navbar -->
            <div class="container-fluid px-2 px-md-4">
                <div class="page-header min-height-300 border-radius-xl mt-4"
                     style="background-image: url('https://images.unsplash.com/photo-1531512073830-ba890ca4eba2?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');">
                    <span class="mask  bg-gradient-primary  opacity-6"></span>
                </div>
                <div class="card card-body mx-3 mx-md-4 mt-n6">
                    <div class="row gx-4 mb-2">
                        <div class="col-auto">
                            <div class="avatar avatar-xl position-relative">
                                <a href="{{asset('storage/' . auth()->user()->image)  }}">
                                    <img src="{{ asset('storage/' . auth()->user()->image) }}" alt="profile_image"
                                         class="w-100 border-radius-lg shadow-sm">
                                </a>
                            </div>
                        </div>
                        <div class="col-auto my-auto">
                            <div class="h-100">
                                <h5 class="mb-1">
                                    {{ auth()->user()->first_name . ' ' . auth()->user()->last_name }}
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="card card-plain h-100">
                        <div class="card-header pb-0 p-3">
                            <div class="row">
                                <div class="col-md-8 d-flex align-items-center">
                                    <h6 class="mb-3">معلومات الملف الشخصي</h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            @if (session('success'))
                                <div class="row">
                                    <div class="alert alert-success alert-dismissible text-white" role="alert">
                                        <span class="text-sm">{{ Session::get('success') }}</span>
                                        <button type="button" class="btn-close text-lg py-3 opacity-10"
                                                data-bs-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                </div>
                            @endif
                            @if (Session::has('danger'))
                                <div class="row">
                                    <div class="alert alert-danger alert-dismissible text-white" role="alert">
                                        <span class="text-sm">{{ Session::get('danger') }}</span>
                                        <button type="button" class="btn-close text-lg py-3 opacity-10"
                                                data-bs-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                </div>
                            @endif
                            <form method='POST' action='{{route("profile.update")}}' enctype="multipart/form-data">
                                @method("PATCH")
                                @csrf
                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">صورة الملف الشخصي</label>
                                        <input type="file" name="image" class="form-control border border-2 p-2">
                                        @error('image')
                                        <p class='text-danger inputerror'>{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">عنوان البريد الإلكتروني</label>
                                        <input type="email" name="email" class="form-control border border-2 p-2"
                                               value='{{ old('email', auth()->user()->email) }}'>
                                        @error('email')
                                        <p class='text-danger inputerror'>{{ $message }} </p>
                                        @enderror
                                    </div>

                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">الاسم الأول</label>
                                        <input type="text" name="first_name" class="form-control border border-2 p-2"
                                               value='{{ old('first_name', auth()->user()->first_name) }}'>
                                        @error('first_name')
                                        <p class='text-danger inputerror'>{{ $message }} </p>
                                        @enderror
                                    </div>

                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">اسم العائلة</label>
                                        <input type="text" name="last_name" class="form-control border border-2 p-2"
                                               value='{{ old('last_name', auth()->user()->last_name) }}'>
                                        @error('last_name')
                                        <p class='text-danger inputerror'>{{ $message }} </p>
                                        @enderror
                                    </div>

                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">الهاتف</label>
                                        <input type="number" name="phone" class="form-control border border-2 p-2"
                                               value='{{ old('phone', auth()->user()->phone) }}'>
                                        @error('phone')
                                        <p class='text-danger inputerror'>{{ $message }} </p>
                                        @enderror
                                    </div>
                                </div>
                                <button type="submit" class="btn bg-gradient-dark">إرسال</button>
                            </form>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-layout>
