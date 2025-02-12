@extends('layouts.app')

@section('title', 'Bình Minh - Mẹo Du Lịch')

@section('content')
    <div class="container mt-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}" class="text-decoration-none text-black">Trang chủ</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Mẹo du lịch Đà Nẵng</li>
            </ol>
        </nav>

        <div class="list-post-title d-flex align-items-center">
            <h2 class="text-uppercase text-center text-1">Danh sách mẹo du lịch đà nẵng</h2>
        </div>

        <div class="list-post">
            <div class="row">
                @foreach ($posts as $post)
                    <div class="card-post col-lg-3 col-md-6 col-12 d-flex mt-4">
                        <a href="{{ route('home.post.detail', ['slug' => $post->slug, 'id' => $post->id]) }}"
                            class="text-decoration-none">
                            <div class="card h-100 w-100">
                                <img src="{{ asset('storage/images/posts/' . $post->thumbnail) }}" class="card-img-top"
                                    alt="{{ $post->thumbnail }}" />
                                <div class="card-body mt-3 d-flex flex-column">
                                    <h5 class="card-title">{{ $post->title }}</h5>
                                    <span class="post-create-date text-body-secondary"><i class="bi bi-clock"></i>
                                        {{ $post->created_at }}</span>
                                    <p class="post-desc card-text text-body-secondary">
                                        {{ $post->short_description }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="d-flex justify-content-end">
            {{ $posts->onEachSide(1)->links() }}
        </div>
    </div>
@endsection
