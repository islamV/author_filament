<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="user-management"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="تعديل الكتاب"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-body px-0 pb-2">
                            <div class="card-body p-3">
                                @if (session('status'))
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
                                <form method='POST' action='{{ route('books.update' , $book->id) }}' enctype="multipart/form-data">
                                    @csrf
                                    @method('PATCH')
                                    <div class="row">
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">{{ __('form.title') }}</label>
                                            <input type="text" name="title" class="form-control border border-2 p-2" value='{{ $book->title }}'>
                                            @error('title')
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                            @enderror
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">{{ __('form.description') }}</label>
                                            <textarea name="description" class="form-control border border-2 p-2">{{ $book->description }}
                                            </textarea>
                                            @error('description')
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                            @enderror
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">{{ __('form.image') }}</label>
                                            <input type="file" name="image" class="form-control border border-2 p-2" value=''>
                                            <div class="">
                                                <a href="{{ asset('storage/' .$book->image) }}">
                                                    <img src="{{ asset('storage/' .$book->image) }}"
                                                         class="avatar avatar-xxl me-3 border-radius-lg" alt="user1">
                                                </a>
                                            </div>
                                            @error('image')
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                            @enderror
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">{{ __('form.category') }}</label>
                                            <select name="category" class="form-control border border-2 p-2">
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" @selected($book->category_id == $category->id)>{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('category')
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                            @enderror
                                        </div>
                                    </div>
                                    <button type="submit" class="btn bg-gradient-dark">{{ __('form.update_button') }}</button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
{{--    <x-plugins></x-plugins>--}}

</x-layout>
