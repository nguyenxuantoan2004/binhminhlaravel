@extends('layouts.app')

@section('title', 'Trang Đổi Mật Khẩu')

@section('content')
    <div class="container mt-3 profile">
        <nav aria-label="breadcrumb" class="pt-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}" class="text-decoration-none text-black">Trang chủ</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Đổi mật khẩu</li>
            </ol>
        </nav>
        <div class="row">
            <div class="col-lg-3 col-md-3">
                <ul class="list-group">
                    <li class="list-group-item list-group-item-action list-group-item-light">
                        <a href="{{ route('home.profile.show') }}" class="d-block w-100 h-100 text-decoration-none text-black">Thông tin cá nhân</a>
                    </li>
                    <li class="list-group-item list-group-item-action list-group-item-light">
                        <a href="{{ route('home.order.show') }}" class="d-block w-100 h-100 text-decoration-none text-black">Đơn đặt xe</a>
                    </li>
                    <li class="list-group-item list-group-item-action list-group-item-light active">
                        <a href="{{ route('home.pass.show') }}" class="d-block w-100 h-100 text-decoration-none text-black">Đổi mật khẩu</a>
                    </li>
                </ul>
            </div>

            <div class="col-lg-9 col-md-9">
                <h3 class="m-0 text-center mt-2 mb-3">Thông Tin Cá Nhân</h3>
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
                <form action="{{ route('home.pass') }}" method="post" class="mt-3">
                    @csrf
                    @method('POST')
                    <div class="row mb-3 ">
                        <label for="pass-old" class="col-sm-2 col-form-label">Mật khẩu hiện tại</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="pass-old" name="pass-old" value="{{old('pass-old')}}"/>
                            @error('pass-old')
                                <div class="text-danger">{!! $message !!}</div>
                            @enderror
                        </div>

                    </div>
                    <div class="row mb-3 ">
                        <label for="pass-new" class="col-sm-2 col-form-label">Mật khẩu mới</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="pass-new" name="pass-new" value="{{old('pass-new')}}"/>
                            @error('pass-new')
                                <div class="text-danger">{!! $message !!}</div>
                            @enderror
                        </div>

                    </div>
                    <div class="row mb-3 ">
                        <label for="pass-confirm" class="col-sm-2 col-form-label">Nhập lại mật khẩu</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="pass-confirm" name="pass-confirm" value="{{old('pass-confirm')}}"/>
                            @error('pass-confirm')
                                <div class="text-danger">{!! $message !!}</div>
                            @enderror
                        </div>

                    </div>
                    
                    <div class="row mb-3 w-100 d-flex justify-content-center">
                        <input type="submit" value="Cập nhật" class="btn btn-primary" style="width: 15%;">
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
