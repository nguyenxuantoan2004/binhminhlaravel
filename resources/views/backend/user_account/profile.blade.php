@extends('layouts.admin')

@section('title', 'Trang Thông Tin Cá Nhân')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                <h5>Cập nhật nhân viên</h5>
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
                <form action="{{ route('profile.update', ['employee' => Auth::guard('web')->user()->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="name">Họ và tên</label>
                                <input class="form-control" type="text" name="name" id="name"
                                    value="{{ old('name', Auth::guard('web')->user()->name) }}">
                                @error('name')
                                    <div class="text-danger">{!! $message !!}</div>
                                @enderror
                            </div>
                            <br>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input class="form-control" type="text" name="email" id="email"
                                    value="{{ old('email', Auth::guard('web')->user()->email) }}">
                                @error('email')
                                    <div class="text-danger">{!! $message !!}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label for="address">Địa chỉ</label>
                                <input class="form-control" type="text" name="address" id="address"
                                    value="{{ old('address', Auth::guard('web')->user()->address) }}">
                                @error('address')
                                    <div class="text-danger">{!! $message !!}</div>
                                @enderror
                            </div>
                            <br>
                            <div class="form-group">
                                <label for="phone_number">Số điện thoại</label>
                                <input class="form-control" type="text" name="phone_number" id="phone_number"
                                    value="{{ old('phone_number', Auth::guard('web')->user()->phone_number) }}">
                                @error('phone_number')
                                    <div class="text-danger">{!! $message !!}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-6">
                            <br>
                            <div class="form-group">
                                <label for="id_card_number">Căn cước công dân</label>
                                <input class="form-control" type="text" name="id_card_number" id="id_card_number"
                                    value="{{ old('id_card_number', Auth::guard('web')->user()->id_card_number) }}">
                                @error('id_card_number')
                                    <div class="text-danger">{!! $message !!}</div>
                                @enderror
                            </div>
                        </div>

                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </form>
            </div>
        </div>
    </div>
@endsection
