@extends('layouts.app')

@section('title', $post->title . ' - Mẹo Du Lịch')

@section('content')
    <div class="container mt-3">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}" class="text-decoration-none text-black">Trang chủ</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('home.post.index') }}" class="text-decoration-none text-black">Mẹo du lịch Đà Nẵng</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ $post->title }}</li>
            </ol>
        </nav>

        <!-- Bài viết -->
        <div class="row">
            <div class="col-8">
                <div class="post-detail">
                    <div class="post-header">
                        <h4 class="text-uppercase">{{ $post->title }}</h4>
                        <p class="text-body-secondary" style="font-size: 14px">
                            <i class="bi bi-clock"></i> Đăng ngày: {{ $post->created_at->format('d/m/Y') }}
                        </p>
                    </div>
                    <div class="post-desc">
                        <p>{{ $post->short_description }}</p>
                    </div>

                    {{-- <div class="post-thumbnail text-center mt-4">
                        <img src="{{ asset('storage/images/posts/' . $post->thumbnail) }}" alt="{{ $post->title }}"
                            class="img-fluid rounded">
                    </div> --}}

                    <div class="post-content mt-4">
                        {!! $post->content !!}
                    </div>
                </div>
            </div>
            <div class="col-4 sticky-top" style="top: 100px; max-height: calc(5 * 150px); overflow-y: auto;">
                <h2 class="text-uppercase text-1">Bài viết liên quan</h2>
                @foreach ($relatedPosts as $related)
                    <div class="related-post col-12 d-flex mt-4">
                        <a href="{{ route('home.post.detail', ['slug' => $related->slug, 'id' => $related->id]) }}"
                            class="d-flex text-decoration-none">
                            <div class="related-post-thumbnail">
                                <img src="{{ asset('storage/images/posts/' . $related->thumbnail) }}"
                                    alt="{{ $related->title }}" class="img-fluid rounded"
                                    style="width: 120px; height: 100px; object-fit: cover;">
                            </div>
                            <div class="related-post-content ms-3">
                                <h6 class="mb-1 text-dark">{{ Str::limit($related->title, 50) }}</h6>
                                <p class="mb-0 text-muted" style="font-size: 0.875rem;">
                                    {{ $related->created_at->format('d/m/Y') }}</p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Bài viết liên quan -->
        {{-- <div class="related-posts mt-5">
            
            <div class="row mt-3">
                @foreach ($relatedPosts as $related)
                    <div class="card-post col-lg-3 col-md-6 col-12 d-flex mt-4">
                        <a href="{{ $related->slug }}" class="text-decoration-none">
                            <div class="card h-100 w-100">
                                <img src="{{ asset('storage/images/posts/' . $related->thumbnail) }}" 
                                     class="card-img-top" 
                                     alt="{{ $related->title }}" />
                                <div class="card-body mt-3 d-flex flex-column">
                                    <h5 class="card-title">{{ $related->title }}</h5>
                                    <span class="post-create-date text-body-secondary">
                                        <i class="bi bi-clock"></i> {{ $related->created_at->format('d/m/Y') }}
                                    </span>
                                    <p class="post-desc card-text text-body-secondary">
                                        {{ $related->short_description }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div> --}}
    </div>
@endsection
