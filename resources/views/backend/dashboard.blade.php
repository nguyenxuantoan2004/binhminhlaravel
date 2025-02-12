@extends('layouts.admin')

@section('title', 'Trang Chi Nhánh')

@section('content')
    <div id="content" class="container-fluid">
        <div class="row">
            <!-- Doanh thu -->
            <div class="col-md-3 mb-3">0
                <div class="card text-white bg-primary h-100">
                    <div class="card-header">DOANH THU</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ number_format($totalRevenue, 0, ',', '.') }} VNĐ</h5>
                        <p class="card-text">Tổng doanh thu từ các đơn hàng đã hoàn thành</p>
                    </div>
                </div>
            </div>

            <!-- Chờ xác nhận -->
            <div class="col-md-3 mb-3">
                <div class="card text-white text-bg-info h-100">
                    <div class="card-header">Chờ xác nhận</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $counts['pending'] }}</h5>
                        <p class="card-text">Số đơn hàng đang chờ xác nhận</p>
                    </div>
                </div>
            </div>

            <!-- Đã xác nhận -->
            <div class="col-md-3 mb-3">
                <div class="card text-white text-bg-primary h-100">
                    <div class="card-header">Đã xác nhận</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $counts['confirmed'] }}</h5>
                        <p class="card-text">Số đơn hàng đã được xác nhận</p>
                    </div>
                </div>
            </div>

            <!-- Đang giao xe -->
            <div class="col-md-3 mb-3">
                <div class="card text-white text-bg-warning h-100">
                    <div class="card-header">Đang giao xe</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $counts['delivering'] }}</h5>
                        <p class="card-text">Số đơn hàng đang giao xe</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <!-- Chờ nhận xe -->
            <div class="col-md-3 mb-3">
                <div class="card text-white text-bg-secondary h-100">
                    <div class="card-header">Chờ nhận xe</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $counts['waiting_for_pickup'] }}</h5>
                        <p class="card-text">Số đơn hàng đang giao xe</p>
                    </div>
                </div>
            </div>

            <!-- Hoàn thành -->
            <div class="col-md-3 mb-3">
                <div class="card text-white text-bg-success h-100">
                    <div class="card-header">Hoàn thành</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $counts['completed'] }}</h5>
                        <p class="card-text">Số đơn hàng đã hoàn thành</p>
                    </div>
                </div>
            </div>

            <!-- Đã hủy -->
            <div class="col-md-3 mb-3">
                <div class="card text-white text-bg-danger h-100">
                    <div class="card-header">Đã hủy</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $counts['cancelled'] }}</h5>
                        <p class="card-text">Số đơn hàng đã bị hủy</p>
                    </div>
                </div>
            </div>
        </div>




        <!-- Danh sách đơn hàng mới nhất -->
        <div class="card mt-4">
            <div class="card-header font-weight-bold">ĐƠN HÀNG MỚI NHẤT</div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Mã đặt xe</th>
                            <th scope="col">Khách hàng</th>
                            <th scope="col">Ngày đặt</th>
                            <th scope="col">Ngày trả dự kiến</th>
                            <th scope="col">Giá thuê</th>
                            <th scope="col">Tổng tiền</th>
                            <th scope="col">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($latestOrders as $index => $order)
                            <tr>
                                <a href="#">
                                    <td>{{ $index + 1 }}</td>
                                    <td><a href="{{ route('invoice.edit', ['invoice' => $order->id]) }}"
                                            class="text-decoration-none text-secondary">{{ $order->id }}</a></td>
                                    <td><a href="{{ route('customer.edit', ['customer' => $order->customer->id]) }}"
                                            class="text-decoration-none text-secondary">{{ $order->customer->name }}</a>
                                    </td>
                                    <td>{{ $order->start_date }}</td>
                                    <td>{{ $order->expected_return_date }}</td>
                                    <td>{{ number_format($order->motorbike->rental_price, 0, ',', '.') }} VNĐ / Ngày</td>
                                    <td>{{ number_format($order->total_amount, 0, ',', '.') }} VNĐ</td>
                                    <td><span class="{{ $order->status_class }}">{{ $order->status_label }}</span></td>
                                </a>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
