@extends('layouts.admin')

@section('title', 'Trang Cập Nhật Chức Vụ')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                <h5>Cập nhật chức vụ</h5>
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
                <form action="{{ route('position.update', ['position' => $position->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="name">Tên chức vụ</label>
                                <input class="form-control" type="text" name="name" id="name"
                                    value="{{ old('name', $position->name) }}">
                                @error('name')
                                    <div class="text-danger">{!! $message !!}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-6">
                    
                            <div class="form-group">
                                <label for="status">Trạng thái</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">Chọn</option>
                                    <option value="draft"
                                        {{ old('status', $position->status ?? '') == 'draft' ? 'selected' : '' }}>Nháp
                                    </option>
                                    <option value="pending"
                                        {{ old('status', $position->status ?? '') == 'pending' ? 'selected' : '' }}>Chờ
                                        duyệt
                                    </option>
                                    <option value="public"
                                        {{ old('status', $position->status ?? '') == 'public' ? 'selected' : '' }}>Công khai
                                    </option>
                                    <option value="private"
                                        {{ old('status', $position->status ?? '') == 'private' ? 'selected' : '' }}>Không
                                        công
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
