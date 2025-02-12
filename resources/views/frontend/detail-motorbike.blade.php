@extends('layouts.app')

@section('title', 'Trang Chi Tiết Xe')

@section('content')
    <div class="container mt-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}" class="text-decoration-none text-black">Trang chủ</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('home.motorbike.index') }}" class="text-decoration-none text-black">Danh sách xe</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Chi tiết xe</li>
            </ol>
        </nav>

        <!-- Motorbike Details -->
        <div class="row mt-4">
            <div class="col-md-6">
                {{-- <img src="images/SH125i-5.jpg" class="img-fluid rounded shadow" alt="Xe SH mode 2024" /> --}}
                {{-- <div class="slick-slider">
                    @foreach ($motorbike->MotorbikeImage as $item)
                        <div><img src="{{ asset('storage/images/motorbike/' . $item->file_name) }}" alt="Slide 1"></div>
                    @endforeach
                </div> --}}
                <div class="slider-container">
                    <!-- Slider chính -->
                    <div class="slider-for">
                        @foreach ($motorbike->MotorbikeImage as $item)
                            <div><img src="{{ asset('storage/images/motorbike/' . $item->file_name) }}" alt="Slide">
                            </div>
                        @endforeach
                    </div>

                    <!-- Slider nhỏ -->
                    <div class="slider-nav">
                        @foreach ($motorbike->MotorbikeImage as $item)
                            <div><img src="{{ asset('storage/images/motorbike/' . $item->file_name) }}" alt="Thumbnail">
                            </div>
                        @endforeach
                    </div>
                </div>


            </div>
            <div class="col-md-6">
                <h2 class="fw-bold">{{ $motorbike->name }}</h2>
                <h5 class="fw-bold mt-3">Thông tin chi tiết:</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <strong>Loại xe:</strong> {{ $motorbike->categoryMotorbike->name }}
                    </li>
                    <li class="mb-2">
                        <strong>Năm sản xuất:</strong> {{ $motorbike->manufacture_year }}
                    </li>
                    <li class="mb-2">
                        <strong>Màu sắc:</strong> {{ $motorbike->color }}
                    </li>
                    <li class="mb-2">
                        <strong>Giá thuê:</strong> {{ format_price($motorbike->rental_price) }}/ngày
                    </li>

                    <li class="mb-2">
                        <strong>Trạng thái:</strong>
                        @if ($motorbike->quantity > 0)
                            <span class="badge text-bg-success fs-6">còn xe</span>
                        @else
                            <span class="badge text-bg-warning fs-6">hết xe</span>
                        @endif
                    </li>
                </ul>
                <div class="d-flex mt-4">
                    @if (Auth::guard('customer')->check() && $motorbike->quantity > 0)
                        <button class="btn btn-success me-1 btn-show-modal" style="font-size: 12px"
                            data-motorbike-id="{{ $motorbike->id }}" data-name="{{ $motorbike->name }}"
                            data-price="{{ format_price($motorbike->rental_price) }}"
                            data-license="{{ $motorbike->license_plate }}"
                            data-image="{{ asset('storage/images/motorbike/' . $motorbike->pinnedImage->file_name) }}"
                            data-bs-toggle="modal" data-bs-target="#order">Đặt Xe Ngay</button>
                    @else
                        @if ($motorbike->quantity > 0)
                            <button class="btn btn-success me-1 btn-show-message" style="font-size: 12px">Đặt Xe
                                Ngay</button>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection

@push('order')
    @if (Auth::guard('customer')->check())
        <!-- Modal (chỉ một modal chung) -->
        <div class="modal fade" data-bs-backdrop="false" id="order" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true" style="z-index: 111111111112 !important;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="" method="post" id="order-form">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5 text-center w-100 fw-bold" id="exampleModalLabel">
                                ĐƠN ĐẶT XE</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <!-- Hình ảnh -->
                                <div class="custom-swal col-4">
                                    <img id="modal-image" src="" alt=""
                                        style="width: 150px; object-fit: cover;">
                                </div>
                                <!-- Thông tin xe -->
                                <div class="col-8">
                                    <div class="info">
                                        <p class="m-1">Tên Xe: <b id="modal-name"></b></p>
                                        <p class="m-1">Giá thuê: <b id="modal-price"></b></p>
                                    </div>
                                </div>
                                <hr class="w-100">
                                <!-- Thông tin nhận xe -->
                                <div class="col-12">
                                    <h4 class="w-100 text-center">Thông tin nhận xe</h4>
                                    <input type="hidden" name="motorbike_id" class="motorbike_id" id="modal-motorbike-id">
                                    <input type="hidden" name="customer_id" class="customer_id" id="modal-customer-id"
                                        value="{{ Auth::guard('customer')->user()->id }}">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="mt-3">
                                                <label for="startDate">Ngày nhận xe:</label>
                                                <input type="datetime-local" id="startDate" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="mt-3">
                                                <label for="endDate">Ngày trả xe dự kiến:</label>
                                                <input type="datetime-local" id="endDate" class="form-control" readonly>
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="mt-3">
                                                <label for="rentalDays">Số ngày thuê:</label>
                                                <input type="number" id="rentalDays" class="form-control"
                                                    min="1" value="1">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="mt-3">
                                                <label for="totalPrice">Tổng tiền dự kiến:</label>
                                                <input type="text" id="totalPrice" class="form-control" readonly>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mt-3">
                                                <label for="modal-tel">Số điện thoại</label>
                                                <input type="tel" id="modal-tel" class="form-control"
                                                    value="{{ Auth::guard('customer')->user()->phone_number }}" required>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mt-3">
                                                <label for="modal-email">Email</label>
                                                <input type="email" id="modal-email" class="form-control"
                                                    value="{{ Auth::guard('customer')->user()->email }}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Số ngày thuê và ngày nhận trả xe -->
                                    <div class="row mt-3">
                                        <h6>Phương thức nhận xe</h6>
                                        <div class="col-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="pickupOption"
                                                    id="pickupStore" value="store" checked>
                                                <label class="form-check-label" for="pickupStore">Nhận xe
                                                    tại
                                                    cửa hàng</label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="pickupOption"
                                                    id="delivery" value="delivery">
                                                <label class="form-check-label" for="delivery">Giao xe tận
                                                    nơi</label>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Chi nhánh cửa hàng -->
                                    <div class="branch-select mt-2" style="display: none;">
                                        <label for="branch">Chọn chi nhánh:</label>
                                        <select class="form-control" id="branch">
                                            @foreach ($branchs as $branch)
                                                <option value="{{ $branch->id }}">{{ $branch->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!-- Địa chỉ giao xe -->
                                    <div class="delivery-input mt-2" style="display: none;">
                                        <label for="deliveryInfo">Nhập thông tin giao xe:</label>
                                        <input type="text" id="deliveryInfo" class="form-control"
                                            placeholder="Địa chỉ nhận xe">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-success btn-order">Đặt Xe</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endpush
@push('css')
    <link rel="stylesheet" href="{{ asset('css/slick/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('css/slick/slick-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/config-slick.css') }}">
@endpush

@push('js')
    <script src="{{ asset('js/order.js') }}"></script>
    <script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <script src="{{ asset('js/slick/slick.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.slider-for').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                arrows: true,
                fade: true,
                asNavFor: '.slider-nav'
            });
            $('.slider-nav').slick({
                slidesToShow: 3,
                slidesToScroll: 1,
                asNavFor: '.slider-for',
                dots: true,
                arrows: false,
                centerMode: true,
                focusOnSelect: true
            });
        });
    </script>
@endpush
