@extends('layouts.app')

@section('title', 'Bình Minh - Mẹo Du Lịch')

@section('content')
    <div class="container mt-3 profile">
        <nav aria-label="breadcrumb" class="pt-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}" class="text-decoration-none text-black">Trang chủ</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Đơn đặt xe</li>
            </ol>
        </nav>
        <div class="row">
            <div class="col-lg-3 col-md-3">
                <ul class="list-group">
                    <li class="list-group-item list-group-item-action list-group-item-light">
                        <a href="{{ route('home.profile.show') }}"
                            class="d-block w-100 h-100 text-decoration-none text-black">Thông tin cá nhân</a>
                    </li>
                    <li class="list-group-item list-group-item-action list-group-item-light active">
                        <a href="{{ route('home.order.show') }}"
                            class="d-block w-100 h-100 text-decoration-none text-black">Đơn đặt xe</a>
                    </li>
                    <li class="list-group-item list-group-item-action list-group-item-light">
                        <a href="{{ route('home.pass.show') }}"
                            class="d-block w-100 h-100 text-decoration-none text-black">Đổi mật khẩu</a>
                    </li>
                </ul>
            </div>

            <div class="col-lg-9 col-md-9">
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
                {{-- <form action="" method="post"> --}}
                {{-- @csrf --}}
                {{-- @method('POST') --}}
                <table class="table table-hover caption-top table-bordered align-middle">
                    <caption class="fs-3 text-black text-uppercase fw-bold pt-0">Danh sách đơn đặt xe</caption>
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Mã đơn</th>
                            <th>Tên xe</th>
                            <th>Ngày đặt</th>
                            <th>Ngày trả dự kiến</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Tác vụ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 0;
                        @endphp
                        @foreach ($listOrder as $order)
                            @php
                                $i++;
                            @endphp
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->motorbike->name }}</td>
                                <td>{{ $order->start_date->format('d/m/Y H:i') }}</td>
                                <td>{{ $order->expected_return_date->format('d/m/Y H:i') }}</td>
                                <td>{{ format_price($order->total_amount) }}</td>
                                <td><span class="{{ $order->status_class }}">
                                        {{ $order->status_label }}
                                    </span></td>
                                <td class="d-flex justify-content-around">
                                    <a href="{{ route('home.orderDetail.show', ['id'=>$order->id]) }}" class="d-block custom-hover w-100 text-center"
                                        data-bs-toggle="tooltip" data-bs-title="Xem chi tiết">
                                        <i class="bi bi-eye"></i>
                                    </a> |

                                    <button class="btn btn-cancel-order d-block custom-hover w-100 text-center p-0"
                                        data-id="{{ $order->id }}" data-bs-toggle="tooltip"
                                        data-bs-title="Hủy đơn đặt xe">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- </form> --}}
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        $(document).ready(function() {
            $(".btn-cancel-order").click(function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                Swal.fire({
                    title: `Bạn chắc chắn muốn hủy đơn hàng ${id} này không?`,
                    icon: "question",
                    confirmButtonText: "Chắc chắn",
                    cancelButtonText: "Trở Lại",
                    showCancelButton: true,
                    showCloseButton: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajaxSetup({
                            headers: {
                                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                    "content"),
                            },
                        });

                        $.ajax({
                            type: "POST",
                            url: "/don-dat-xe/huy/" + id,
                            data: {},
                            dataType: "json",
                            success: function(response) {
                                if (response.code == 'success') {
                                    Swal.fire({
                                        title: response.status,
                                        icon: "success",
                                        confirmButtonText: "Đóng",
                                    }).then((result) => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        title: response.status,
                                        icon: "error",
                                        confirmButtonText: "Đóng",
                                    });
                                }
                                // console.log(response);
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
