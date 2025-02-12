@extends('layouts.admin')

@section('title', 'Trang Cập Nhật Bài Viết')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                <h5>Cập nhật bài viết</h5>
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
                <form action="{{ route('post.update', ['post' => $post->id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="title">Tiêu đề bài viết</label>
                                <input class="form-control" type="text" name="title" id="title"
                                    value="{{ old('title', $post->title) }}">
                                @error('title')
                                    <div class="text-danger">{!! $message !!}</div>
                                @enderror
                            </div>
                        </div>


                        <div class="col-12">
                            <br>
                            <div class="form-group">
                                <label for="short_description">Mô tả ngắn</label>
                                <textarea class="form-control" name="short_description">{{ old('short_description', $post->short_description) }}</textarea>
                                @error('short_description')
                                    <div class="text-danger">{!! $message !!}</div>
                                @enderror
                            </div>
                        </div>


                        <div class="col-12">
                            <br>
                            <div class="form-group">
                                <label for="content">Nội dung</label>
                                <textarea name="content" id="editor">{{ old('content', $post->content) }}</textarea>
                                @error('content')
                                    <div class="text-danger">{!! $message !!}</div>
                                @enderror
                            </div>
                        </div>


                        <div class="col-6">
                            <br>
                            <div class="form-group">
                                <label for="status">Trạng thái</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">Chọn</option>
                                    <option value="draft" {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>
                                        Nháp</option>
                                    <option value="pending"
                                        {{ old('status', $post->status) == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                                    <option value="public" {{ old('status', $post->status) == 'public' ? 'selected' : '' }}>
                                        Công khai</option>
                                    <option value="private"
                                        {{ old('status', $post->status) == 'private' ? 'selected' : '' }}>Không công khai
                                    </option>
                                </select>
                                @error('status')
                                    <div class="text-danger">{!! $message !!}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-6">
                            <br>
                            <div class="form-group">
                                <label for="thumbnail">Hình ảnh</label>
                                <input class="form-control" type="file" name="thumbnail" id="thumbnail" accept="image/*">
                                @error('thumbnail')
                                    <div class="text-danger">{!! $message !!}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12" style="margin-top: 10px;">
                            <br>
                            <div id="preview">
                                <img src="{{ asset('storage/images/posts/' . $post->thumbnail) }}" class="rounded"
                                    style="max-width: 800px;" alt="">
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
    <script src="{{ asset('js/post.js') }}"></script>
@endpush

@push('css')
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/jodit.min.css') }}" />
@endpush
