<x-layout bodyClass="g-sidenav-show">
    <x-navbars.sidebar activePage="book-parts"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-navbars.navs.auth titlePage="إدارة أجزاء الكتاب"></x-navbars.navs.auth>
        <div class="container-fluid py-4">
            @if (session('status'))
                <div class="alert alert-success alert-dismissible text-white fade show" role="alert">
                    <span class="text-sm">{{ Session::get('status') }}</span>
                    <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if (Session::has('demo'))
                <div class="alert alert-danger alert-dismissible text-white fade show" role="alert">
                    <span class="text-sm">{{ Session::get('demo') }}</span>
                    <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

                <div class="accordion" id="bookPartsAccordion">
                    @foreach($book_parts as $key => $part)
                        <div class="accordion-item border-0 mb-3">
                            <h2 class="accordion-header" id="heading-{{ $key }}">
                                <button class="accordion-button collapsed bg-black shadow-sm px-4 py-3 d-flex justify-content-between align-items-center w-100"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapse-{{ $key }}"
                                        aria-expanded="false"
                                        aria-controls="collapse-{{ $key }}">
                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                        <div>
                                            <h5 class="mb-0 fw-normal">{{ $part->chapter }}</h5>
                                            <p class="text-gray-400 font-medium mb-0"></p>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge {{ $part->is_published ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $part->is_published ? 'تم النشر' : 'لم يتم النشر' }}
                                            </span>
                                            <span class="badge bg-info text-end">{{ date_format($part->created_at , 'Y-m-d') }}</span>
                                        </div>
                                    </div>
                                </button>
                            </h2>
                            <div id="collapse-{{ $key }}"
                                 class="accordion-collapse collapse"
                                 aria-labelledby="heading-{{ $key }}"
                                 data-bs-parent="#bookPartsAccordion">
                                <div class="accordion-body bg-gray-800 text-white px-4 py-3">
                                    <div class="mb-4">
                                        <h4 class="text-white text-end"><strong>:محتوى الفصول</strong></h4>
                                        @foreach($part->bookPages as $page)
                                            <div class="mb-3 p-3 border rounded bg-gray-700">
                                                <h6 class="text-light text-end">صفحة {{ $page->page_number }}</h6>
                                                <p class="text-light text-end">{{ $page->content }}</p>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="mt-3">
                                        @if($part->is_published)
                                            <form action="{{ route('books.parts.publish', $part->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-light btn-sm">إلغاء النشر</button>
                                            </form>
                                        @else
                                            <form action="{{ route('books.parts.publish', $part->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-info btn-sm">نشر</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                {{ $book_parts->withQueryString()->links('vendor.pagination.bootstrap-5') }}
        </div>
    </main>
</x-layout>
