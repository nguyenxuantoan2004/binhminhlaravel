@extends('layouts.admin')

@section('title', 'Trang Thêm Mới Khách Hàng')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                <h5>Thêm khách hàng</h5>
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
                <form action="{{ route('customer.store') }}" method="POST">
                    @csrf
                    @method('POST')
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="name">Họ và tên</label>
                                <input class="form-control" type="text" name="name" id="name"
                                    value="{{ old('name') }}">
                                @error('name')
                                    <div class="text-danger">{!! $message !!}</div>
                                @enderror
                            </div>
                            <br>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input class="form-control" type="text" name="email" id="email"
                                    value="{{ old('email') }}">
                                @error('email')
                                    <div class="text-danger">{!! $message !!}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label for="id_card_number">Căn cước công dân</label>
                                <input class="form-control" type="text" name="id_card_number" id="id_card_number"
                                    value="{{ old('id_card_number') }}">
                                @error('id_card_number')
                                    <div class="text-danger">{!! $message !!}</div>
                                @enderror
                            </div>
                            <br>
                            <div class="form-group">
                                <label for="driving_license_number">Số bằng lái xe <span class="text-secondary">(Có thể bỏ
                                        trống)</span></label>
                                <input class="form-control" type="text" name="driving_license_number"
                                    id="driving_license_number" value="{{ old('driving_license_number') }}">
                                @error('driving_license_number')
                                    <div class="text-danger">{!! $message !!}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-6">
                            <br>
                            <div class="form-group">
                                <label for="address">Địa chỉ</label>
                                <input class="form-control" type="text" name="address" id="address"
                                    value="{{ old('address') }}">
                                @error('address')
                                    <div class="text-danger">{!! $message !!}</div>
                                @enderror
                            </div>
                            <br>
                            <div class="form-group">
                                <label for="phone_number">Số điện thoại</label>
                                <input class="form-control" type="text" name="phone_number" id="phone_number"
                                    value="{{ old('phone_number') }}">
                                @error('phone_number')
                                    <div class="text-danger">{!! $message !!}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-6">
                            <br>
                            <div class="form-group">
                                <label for="password">Mật khẩu</label>
                                <input class="form-control" type="password" name="password" id="password"
                                    value="{{ old('password') }}">
                                @error('password')
                                    <div class="text-danger">{!! $message !!}</div>
                                @enderror
                            </div>
                            <br>
                            <div class="form-group">
                                <label for="status">Trạng thái</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">Chọn</option>
                                    <option value="active" {{old('status') == 'active' ? "selected" : ""}}>Hoạt động</option>
                                    <option value="temporary_lock" {{old('status') == 'temporary_lock' ? "selected" : ""}}>Khóa tạm thời</option>
                                    <option value="permanently_locked" {{old('status') == 'permanently_locked' ? "selected" : ""}}>Khóa vĩnh viễn</option>
                                </select>
                                @error('status')
                                    <div class="text-danger">{!! $message !!}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary">Thêm mới</button>
                </form>
            </div>
        </div>
    </div>
@endsection
