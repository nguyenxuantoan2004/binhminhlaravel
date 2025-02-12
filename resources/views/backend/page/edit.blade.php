@extends('layouts.admin')

@section('title', 'Trang Cập Nhật Trang')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                <h5>Cập nhật trang</h5>
            </div>

            @if (session('code') == 'success')
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

                </div>
            @endif

            @if (session('code') == 'error')
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

                </div>
            @endif

            <div class="card-body">
                <form action="{{ route('page.update', ['page' => $page->id]) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="name">Tên trang</label>
                                <input class="form-control" type="text" name="name" id="name"
                                    value="{{ old('name', $page->name) }}">
                                @error('name')
                                    <div class="text-danger">{!! $message !!}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label for="status">Trạng thái</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">Chọn</option>
                                    <option value="draft" {{ old('status', $page->status) == 'draft' ? 'selected' : '' }}>
                                        Nháp</option>
                                    <option value="pending"
                                        {{ old('status', $page->status) == 'pending' ? 'selected' : '' }}>Chờ duyệt
                                    </option>
                                    <option value="public" {{ old('status', $page->status) == 'public' ? 'selected' : '' }}>
                                        Công khai
                                    </option>
                                    <option value="private"
                                        {{ old('status', $page->status) == 'private' ? 'selected' : '' }}>Không công
                                        khai</option>
                                </select>
                                @error('status')
                                    <div class="text-danger">{!! $message !!}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <br>
                            <div class="form-group">
                                <label for="content">Nội dung</label>
                                <textarea name="content" id="editor">{{ old('content', $page->content) }}</textarea>
                                @error('content')
                                    <div class="text-danger">{!! $message !!}</div>
                                @enderror
                            </div>
                        </div>
            </div>
            <br>
            <button type="submit" class="btn btn-primary">Cập Nhật</button>
            </form>
        </div>
    </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('js/jodit-elfinder/jodit.min.js') }}"></script>
    <script src="{{ asset('js/jodit-elfinder/jquery-ui.js') }}"></script>
    <script>
        var elFinderUrl = "{{ url('elfinder/elfinder.html') }}";
    </script>
    <script src="{{ asset('js/jodit-elfinder/app.js') }}"></script>
@endpush

@push('css')
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/jodit.min.css') }}" />
@endpush
