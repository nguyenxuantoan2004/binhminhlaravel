@extends('layouts.admin')

@section('title', 'Trang Thêm Mới Chi Nhánh')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                <h5>Thêm chi nhánh</h5>
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
                <form action="{{ route('branch.store') }}" method="POST">
                    @csrf
                    @method('POST')
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="name">Tên chi nhánh</label>
                                <input class="form-control" type="text" name="name" id="name"
                                    value="{{ old('name') }}">
                                @error('name')
                                    <div class="text-danger">{!! $message !!}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="phone_number">Số điện thoại</label>
                                <input class="form-control" type="text" name="phone_number" id="phone_number"
                                    value="{{ old('phone_number') }}">
                                @error('phone_number')
                                    <div class="text-danger">{!! $message !!}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="address">Địa Chỉ</label>
                                <input class="form-control" type="text" name="address" id="address"
                                    value="{{ old('address') }}">
                                @error('address')
                                    <div class="text-danger">{!! $message !!}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="status">Trạng thái</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">Chọn</option>
                                    <option value="draft">Nháp</option>
                                    <option value="pending">Chờ duyệt</option>
                                    <option value="public">Công khai</option>
                                    <option value="private">Không công khai</option>
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
