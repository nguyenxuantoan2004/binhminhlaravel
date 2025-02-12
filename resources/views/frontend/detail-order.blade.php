@extends('layouts.app')

@section('title', 'Bình Minh - Mẹo Du Lịch')

@section('content')
    <div id="content" class="container mt-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}" class="text-decoration-none text-black">Trang chủ</a>
                </li>
                <li class="breadcrumb-item"> <a href="{{ route('home.order.show') }}"
                        class="text-decoration-none text-black">Đơn đặt xe</a></li>

                <li class="breadcrumb-item active" aria-current="page">Chi tiết đơn đặt xe</li>

            </ol>
        </nav>
        <div class="card">
            <div class="card-header font-weight-bold text-uppercase">
                <h4>Chi tiết đơn đặt xe</h4>
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
                {{-- {{ route('invoice.store') }} --}}
                <form action="" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Thông tin hóa đơn -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Mã đơn đặt xe</label>
                            <input type="text" name="start_date" id="start_date" class="form-control"
                                value="{{ $invoice->id ?? 'Chưa xác định' }}" readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Tên khách hàng</label>
                            <input type="text" name="start_date" id="start_date" class="form-control"
                                value="{{ $invoice->customer->name ?? 'Chưa xác định' }}" readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Số điện thoại</label>
                            <input type="text" name="start_date" id="start_date" class="form-control"
                                value="{{ $invoice->phone ?? 'Chưa xác định' }}" readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Email</label>
                            <input type="text" name="start_date" id="start_date" class="form-control"
                                value="{{ $invoice->email ?? 'Chưa xác định' }}" readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Ngày bắt đầu</label>
                            <input type="text" name="start_date" id="start_date" class="form-control"
                                value="{{ $invoice->start_date ?? 'Chưa xác định' }}" readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="expected_return_date" class="form-label">Ngày trả dự kiến</label>
                            <input type="text" name="expected_return_date" id="expected_return_date" class="form-control"
                                value="{{ $invoice->expected_return_date ?? 'Chưa xác định' }}" readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="actual_return_date" class="form-label">Ngày trả thực tế</label>
                            <input type="text" name="actual_return_date" id="actual_return_date" class="form-control"
                                value="{{ $invoice->actual_return_date ?? 'Chưa xác định' }}" readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="total_rental_duration" class="form-label">Thời gian thuê (ngày)</label>
                            <input type="text" name="total_rental_duration" id="total_rental_duration"
                                class="form-control" value="{{ $invoice->total_rental_duration ?? 'Chưa xác định' }}"
                                readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="vat_fee" class="form-label">Thuế VAT (%)</label>
                            <input type="text" name="vat_fee" id="vat_fee" class="form-control"
                                value="{{ $invoice->vat_fee ?? 'Chưa xác định' }}" readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="additional_fees" class="form-label">Phí phụ thu</label>
                            <input type="text" name="additional_fees" id="additional_fees" class="form-control"
                                value="{{ format_price($invoice->total_additional_feesamount) ?? 'Chưa xác định' }}" readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="total_cost" class="form-label">Tổng chi phí</label>
                            <input type="text" name="total_cost" id="total_cost" class="form-control"
                                value="{{ format_price($invoice->total_cost) ?? 'Chưa xác định' }}" readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="total_amount" class="form-label">Tổng tiền thanh toán</label>
                            <input type="text" name="total_amount" id="total_amount" class="form-control"
                                value="{{ format_price($invoice->total_amount) ?? 'Chưa xác định' }}" readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="motorbike_receipt_method" class="form-label">Phương thức nhận xe</label>
                            <input type="text" name="motorbike_receipt_method" id="motorbike_receipt_method"
                                class="form-control"
                                value="{{ $invoice->motorbike_receipt_method == 'store' ? 'Nhận tại cửa hàng' : ($invoice->motorbike_receipt_method == 'delivery' ? 'Giao tận nơi' : 'Chưa xác định') }}"
                                readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="receipt_location" class="form-label">Địa điểm nhận xe</label>
                            <input type="text" name="receipt_location" id="receipt_location" class="form-control"
                                value="{{ $invoice->motorbike_receipt_method == 'store' ? $invoice->branch->name ?? 'Chưa xác định' : ($invoice->motorbike_receipt_method == 'delivery' ? $invoice->motorbike_pickup_location ?? 'Chưa xác định' : 'Chưa xác định') }}"
                                readonly>
                        </div>
                    </div>

                    <!-- Nút trở lại -->
                    <div class="mt-4 text-center">
                        <a href="{{ route('home.order.show') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left-circle"></i> Trở lại
                        </a>
                    </div>
                </form>



                <!-- Đường gạch ngang -->
                <hr class="my-4">

                <!-- Tiêu đề Thông tin xe -->
                <h4 class="text-center mb-3">Thông tin xe</h4>

                <!-- Bảng thông tin xe -->
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Hình ảnh</th>
                            <th>Tên xe</th>
                            <th>Màu sắc</th>
                            <th>Năm sản xuất</th>
                            <th>Tình trạng</th>
                            <th>Giá thuê (VNĐ)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="align-middle">
                            <td class="text-center">
                                <img src="{{ asset('storage/images/motorbike/' . $motorbike->pinnedImage->file_name) }}"
                                    alt="{{ $motorbike->name }}" class="img-thumbnail" width="150">
                            </td>
                            <td>{{ $motorbike->name }}</td>
                            <td>{{ $motorbike->color }}</td>
                            <td>{{ $motorbike->manufacture_year }}</td>
                            <td>{{ $motorbike->vehicle_condition }}</td>
                            <td>{{ number_format($motorbike->rental_price, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection
