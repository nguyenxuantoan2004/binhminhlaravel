@extends('layouts.app')

@section('title', 'Trang Quên Mật Khẩu')

@section('content')
    <div class="container mt-3">
        <nav aria-label="breadcrumb" class="pt-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}" class="text-decoration-none text-black">Trang chủ</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Đặt Lại Mật Khẩu</li>
            </ol>
        </nav>
        <div class="container w-100 d-flex justify-content-center">
            <form action="{{ route('password.update') }}" method="post" class="p-3 border rounded my-5" style="width: 40%">
                @csrf
                @method('POST')
                <h3 class="m-0 text-center mt-2 mb-3">Đặt Lại Mật Khẩu</h3>
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
                <input type="hidden" name="email" value="{{ request()->email }}">
                <input type="hidden" name="token" value="{{ request()->token }}">

                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="password" placeholder="Mật Khẩu Mới" name="password" />
                    <label for="password">Mật Khẩu Mới</label>
                    @error('password')
                        <div class="text-danger">{!! $message !!}</div>
                    @enderror
                </div>

                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="password_confirmation" placeholder="Nhập Lại Mật Khẩu"
                        name="password_confirmation" />
                    <label for="password_confirmation">Nhập Lại Mật Khẩu</label>
                    @error('password_confirmation')
                        <div class="text-danger">{!! $message !!}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100">Cập Nhật Mật Khẩu</button>
                <div class="d-flex justify-content-center mt-3">
                    <a href="{{ route('home.login.show') }}" class="btn btn-link p-0 text-decoration-none">Đăng nhập</a>
                    <span class="mx-1">/</span>
                    <a href="{{ route('home.register.show') }}" class="btn btn-link p-0 text-decoration-none">Đăng ký</a>
                </div>
            </form>
        </div>
    </div>
@endsection
