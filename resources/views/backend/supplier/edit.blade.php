@extends('layouts.admin')

@section('title', 'Trang Cập Nhật Nhà Cung Cấp')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
               <h5>Cập nhật nhà cung cấp</h5>
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
                <form action="{{ route('supplier.update', ['supplier' => $supplier->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="name">Tên nhà cung cấp</label>
                                <input class="form-control" type="text" name="name" id="name"
                                    value="{{ old('name', $supplier->name) }}">
                                @error('name')
                                    <div class="text-danger">{!! $message !!}</div>
                                @enderror
                            </div>
                            <br>
                            <div class="form-group">
                                <label for="address">Địa chỉ</label>
                                <input class="form-control" type="text" name="address" id="address"
                                    value="{{ old('address',$supplier->address) }}">
                                @error('address')
                                    <div class="text-danger">{!! $message !!}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label for="phone_number">Số điện thoại</label>
                                <input class="form-control" type="text" name="phone_number" id="phone_number"
                                    value="{{ old('phone_number', $supplier->phone_number) }}">
                                @error('phone_number')
                                    <div class="text-danger">{!! $message !!}</div>
                                @enderror
                            </div>
                            <br>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input class="form-control" type="text" name="email" id="email"
                                    value="{{ old('email', $supplier->email) }}">
                                @error('email')
                                    <div class="text-danger">{!! $message !!}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-6">
                            <br>
                            <div class="form-group">
                                <label for="status">Trạng thái</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">Chọn</option>
                                    <option value="draft"
                                        {{ old('status', $supplier->status ?? '') == 'draft' ? 'selected' : '' }}>Nháp</option>
                                    <option value="pending"
                                        {{ old('status', $supplier->status ?? '') == 'pending' ? 'selected' : '' }}>Chờ duyệt
                                    </option>
                                    <option value="public"
                                        {{ old('status', $supplier->status ?? '') == 'public' ? 'selected' : '' }}>Công khai
                                    </option>
                                    <option value="private"
                                        {{ old('status', $supplier->status ?? '') == 'private' ? 'selected' : '' }}>Không công
                                        khai</option>
                                </select>
                                </select>
                                @error('status')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary">Cập Nhật</button>
                </form>
            </div>
        </div>
    </div>
@endsection
