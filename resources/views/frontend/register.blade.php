@extends('layouts.app')

@section('title', 'Trang Đăng Ký')

@section('content')
    <div class="container mt-3">
        <nav aria-label="breadcrumb" class="pt-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}" class="text-decoration-none text-black">Trang chủ</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Đăng ký</li>
            </ol>
        </nav>
        <div class="container w-100 d-flex justify-content-center">
            <form action="{{ route('home.register') }}" method="post" class="p-3 border rounded my-5"
                style="width: 40%">
                @csrf
                @method('POST')
                <h3 class="m-0 text-center mt-2 mb-3">Đăng Ký Hệ Thống</h3>
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
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="floatingInput" placeholder="Họ Và Tên" name="name"
                        value="{{ old('name') }}" />
                    <label for="floatingInput">Họ Và Tên</label>
                    @error('name')
                        <div class="text-danger">{!! $message !!}</div>
                    @enderror
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="floatingInput" placeholder="Email" name="email"
                        value="{{ old('email') }}" />
                    <label for="floatingInput">Email</label>
                    @error('email')
                        <div class="text-danger">{!! $message !!}</div>
                    @enderror
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="floatingInput" placeholder="Căn cước công dân" name="id_card_number"
                        value="{{ old('id_card_number') }}" />
                    <label for="floatingInput">Căn cước công dân</label>
                    @error('id_card_number')
                        <div class="text-danger">{!! $message !!}</div>
                    @enderror
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="floatingInput" placeholder="phone_number" name="phone_number"
                        value="{{ old('phone_number') }}" />
                    <label for="floatingInput">Số điện thoại</label>
                    @error('phone_number')
                        <div class="text-danger">{!! $message !!}</div>
                    @enderror
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="floatingInput" placeholder="Mật Khẩu" name="password"
                        value="{{ old('password') }}" />
                    <label for="floatingInput">Mật Khẩu</label>
                    @error('password')
                        <div class="text-danger">{!! $message !!}</div>
                    @enderror
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="floatingInput" placeholder="Nhập Lại Mật Khẩu"
                        name="confirm-password" value="{{ old('confirm-password') }}" />
                    <label for="floatingInput">Nhập Lại Mật Khẩu</label>
                    @error('confirm-password')
                        <div class="text-danger">{!! $message !!}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100 p-2">Đăng ký</button>
                <div class="d-flex justify-content-center align-items-center my-3">
                    <div class="line me-3 flex-grow-1"></div>
                    <p class="m-0">Bạn đã Có Tài Khoản?</p>
                    <div class="line ms-3 flex-grow-1"></div>

                </div>
                <a href="{{ route('home.login.show') }}" class="btn btn-danger p-2 w-100 text-decoration-none">Đăng nhập
                    ngay</a>
            </form>
        </div>
    </div>
@endsection
