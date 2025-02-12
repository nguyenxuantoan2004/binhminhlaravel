@extends('layouts.app')

@section('title', 'Trang Thông Tin Cá Nhân')

@section('content')
    <div class="container mt-3 profile">
        <nav aria-label="breadcrumb" class="pt-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}" class="text-decoration-none text-black">Trang chủ</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Thông tin cá nhân</li>
            </ol>
        </nav>
        <div class="row">
            <div class="col-lg-3 col-md-3">
                <ul class="list-group">
                    <li class="list-group-item list-group-item-action list-group-item-light active">
                        <a href="{{ route('home.profile.show') }}" class="d-block w-100 h-100 text-decoration-none text-black">Thông tin cá nhân</a>
                    </li>
                    <li class="list-group-item list-group-item-action list-group-item-light">
                        <a href="{{ route('home.order.show') }}" class="d-block w-100 h-100 text-decoration-none text-black">Đơn đặt xe</a>
                    </li>
                    <li class="list-group-item list-group-item-action list-group-item-light">
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
                <form action="{{ route('home.profile') }}" method="post" class="mt-3">
                    @csrf
                    @method('POST')
                    <div class="row mb-3 ">
                        <label for="name" class="col-sm-2 col-form-label">Họ và tên</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ old('name', $customer->name) }}" />
                            @error('name')
                                <div class="text-danger">{!! $message !!}</div>
                            @enderror
                        </div>

                    </div>
                    <div class="row mb-3 ">
                        <label for="email" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="email" name="email"
                                value="{{ old('email', $customer->email) }}" />
                            @error('email')
                                <div class="text-danger">{!! $message !!}</div>
                            @enderror
                        </div>

                    </div>
                    <div class="row mb-3 ">
                        <label for="phone_number" class="col-sm-2 col-form-label">Số điện thoại</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="phone_number" name="phone_number"
                                value="{{ old('phone_number', $customer->phone_number) }}" />
                            @error('phone_number')
                                <div class="text-danger">{!! $message !!}</div>
                            @enderror
                        </div>

                    </div>
                    <div class="row mb-3 ">
                        <label for="address" class="col-sm-2 col-form-label">Địa chỉ</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="address" name="address"
                                value="{{ old('address', $customer->address) }}" />
                            @error('address')
                                <div class="text-danger">{!! $message !!}</div>
                            @enderror
                        </div>

                    </div>
                    <div class="row mb-3 ">
                        <label for="id_card_number" class="col-sm-2 col-form-label">Căn cước công dân</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="id_card_number" name="id_card_number"
                                value="{{ old('id_card_number', $customer->id_card_number) }}" />
                            @error('id_card_number')
                                <div class="text-danger">{!! $message !!}</div>
                            @enderror
                        </div>

                    </div>
                    <div class="row mb-3 ">
                        <label for="driving_license_number" class="col-sm-2 col-form-label">Số bằng lái xe <br><span
                                class="text-secondary">(Có thể bỏ trống)</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="driving_license_number"
                                name="driving_license_number"
                                value="{{ old('driving_license_number', $customer->driving_license_number) }}" />
                            @error('driving_license_number')
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
