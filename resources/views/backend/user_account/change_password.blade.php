@extends('layouts.admin')

@section('title', 'Trang Thông Tin Cá Nhân')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                <h5>Thay đổi mật khẩu</h5>
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
                                <label for="pass-current">Mật khẩu</label>
                                <input class="form-control" type="password" name="pass-current" id="pass-current"
                                    value="{{ old('pass-current') }}">
                                @error('pass-current')
                                    <div class="text-danger">{!! $message !!}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="pass-new">Mật khẩu mới</label>
                                <input class="form-control" type="password" name="pass-new" id="pass-new"
                                    value="{{ old('pass-new') }}">
                                @error('pass-new')
                                    <div class="text-danger">{!! $message !!}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="pass-confirm">Nhập lại mật khẩu</label>
                                <input class="form-control" type="password" name="pass-confirm" id="pass-confirm"
                                    value="{{ old('pass-confirm') }}">
                                @error('pass-confirm')
                                    <div class="text-danger">{!! $message !!}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary">Đổi mật khẩu</button>
                </form>
            </div>
        </div>
    </div>
@endsection
