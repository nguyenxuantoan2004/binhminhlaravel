@extends('layouts.app')

@section('title', 'Trang Đăng Nhập')

@section('content')
    <div class="container mt-3">
        <nav aria-label="breadcrumb" class="pt-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}" class="text-decoration-none text-black">Trang chủ</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Đăng nhập</li>
            </ol>
        </nav>
        <div class="container w-100 d-flex justify-content-center">
            <form action="{{ route('home.login') }}" method="post" class="p-3 border rounded my-5"
                style="width: 40%">
                @csrf
                @method('POST')
                <h3 class="m-0 text-center mt-2 mb-3">Đăng Nhập Hệ Thống</h3>
                @if (session('code') == 'success')
                    <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                        {!! session('status') !!}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('code') == 'error')
                    <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                        {!! session('status') !!}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

                    </div>
                @endif
                <fieldset>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="floatingInput" placeholder="Tên Tài Khoản" name="email"/>
                        <label for="floatingInput">Email</label>
                        @error('email')
                        <div class="text-danger">{!! $message !!}</div>
                    @enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="floatingInput" placeholder="Mật Khẩu" name="password"/>
                        <label for="floatingInput">Mật Khẩu</label>
                        @error('password')
                        <div class="text-danger">{!! $message !!}</div>
                    @enderror
                    </div>
                    <div class="mb-3 form-check d-flex justify-content-between">
                        <div>
                            <input type="checkbox" class="form-check-input" id="exampleCheck1" name="remember"/>
                            <label class="form-check-label" for="exampleCheck1">Ghi nhớ tôi</label>
                        </div>
                        <a href="{{ route('home.forgot.show') }}" class="btn btn-link p-0 text-decoration-none">Quên mật khẩu?</a>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 p-2">Đăng nhập</button>
                    <div class="d-flex justify-content-center align-items-center my-3">
                        <div class="line me-3 flex-grow-1"></div>
                        <p class="m-0">Bạn Chưa Có Tài Khoản?</p>
                        <div class="line ms-3 flex-grow-1"></div>

                    </div>
                    <a href="{{ route('home.register.show') }}" class="btn btn-danger p-2 w-100 text-decoration-none">Đăng
                        ký ngay</a>
                </fieldset>
            </form>
        </div>
    </div>
@endsection
