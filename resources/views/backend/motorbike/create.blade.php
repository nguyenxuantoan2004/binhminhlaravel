@extends('layouts.admin')

@section('title', 'Trang Thêm Mới Xe')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                <h5>Thêm xe</h5>
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
                <form action="{{ route('motorbike.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="name">Tên xe</label>
                                <input class="form-control" type="text" name="name" id="name"
                                    placeholder="VD: Wave Alpha" value="{{ old('name') }}">
                                @error('name')
                                    <div class="text-danger">{!! $message !!}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label for="rental_price">Giá thuê</label>
                                <input class="form-control" type="text" name="rental_price" id="rental_price"
                                    placeholder="VD: 100000" value="{{ old('rental_price') }}">
                                @error('rental_price')
                                    <div class="text-danger">{!! $message !!}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="quantity">Số lượng xe</label>
                                <input class="form-control" type="text" name="quantity" id="quantity"
                                    placeholder="VD: 1" value="{{ old('quantity') }}">
                                @error('quantity')
                                    <div class="text-danger">{!! $message !!}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label for="manufacture_year">Năm sản xuất</label>
                                <select class="form-control" name="manufacture_year" id="manufacture_year">
                                    <option value="">Chọn năm</option>
                                    @for ($year = date('Y'); $year >= 1900; $year--)
                                        <option value="{{ $year }}"
                                            {{ old('manufacture_year') == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endfor
                                </select>
                                @error('manufacture_year')
                                    <div class="text-danger">{!! $message !!}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="color">Màu sắc</label>
                                <input class="form-control" type="text" name="color" id="color"
                                    placeholder="VD: Đỏ Đen" value="{{ old('color') }}">
                                @error('color')
                                    <div class="text-danger">{!! $message !!}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label for="vehicle_condition">Tình trạng xe</label>
                                <input class="form-control" type="text" name="vehicle_condition" id="vehicle_condition"
                                    placeholder="VD: Mới" value="{{ old('vehicle_condition') }}">
                                @error('vehicle_condition')
                                    <div class="text-danger">{!! $message !!}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="supplier_id">Nhà cung cấp</label>
                                <select name="supplier_id" id="supplier_id" class="form-control">
                                    <option value="">Chọn nhà cung cấp</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}"
                                            {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <div class="text-danger">{!! $message !!}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label for="category_motorbike_id">Loại xe</label>
                                <select name="category_motorbike_id" id="category_motorbike_id" class="form-control">
                                    <option value="">Chọn loại xe</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_motorbike_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_motorbike_id')
                                    <div class="text-danger">{!! $message !!}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">

                        <div class="col-6">
                            <div class="form-group">
                                <label for="status">Trạng thái</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">Chọn</option>
                                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Nháp</option>
                                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Chờ duyệt
                                    </option>
                                    <option value="public" {{ old('status') == 'public' ? 'selected' : '' }}>Công khai
                                    </option>
                                    <option value="private" {{ old('status') == 'private' ? 'selected' : '' }}>Không công
                                        khai</option>
                                    <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Bảo
                                        trì</option>
                                </select>
                                @error('status')
                                    <div class="text-danger">{!! $message !!}</div>
                                @enderror
                            </div>
                        </div>


                        <div class="col-6">
                            <div class="form-group">
                                <label for="images">Hình ảnh</span></label>
                                <input type="file" name="images[]" id="images" class="form-control" multiple
                                    accept="image/*">
                                <input type="hidden" name="pin" id="pin" value="{{ old('pin') }}">
                                @error('images')
                                    <div class="text-danger">{!! $message !!}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-12">
                            <div id="preview" style="margin-top: 10px;">

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

@push('js')
    <script src="{{ asset('js/motorbike.js') }}"></script>
@endpush
