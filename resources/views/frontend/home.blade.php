@extends('layouts.app')

@section('title', 'Trang Chủ')

@section('content')
    <section class="intro-section bg-success-subtle">
        <div class="container h-100 w-100">
            <div id="list-example"
                class="intro h-100 w-100 d-flex text-center justify-content-center align-items-center flex-column">
                <p class="text-animation">Bình Minh</p>
                <p class="text-animation">chuyên cho thuê xe máy tại Đà Nẵng</p>
                <a href="#list-motorbike" class="btn-view-product-category text-animation text-decoration-none">Thuê Xe Ngay
                    <i class="bi bi-arrow-down"></i></a>
            </div>
        </div>
    </section>
    <section class="achievements-section mt-4">
        <div class="container">
            <div class="achievements-desc">
                <h2 class="text-center fw-bold text-1">CHÚNG TÔI ĐÃ ĐẠT ĐƯỢC NHỮNG THÀNH TỰU GÌ?</h2>
                <p class="text-center">
                    <span class="text-1 fw-bold">Bình Minh</span>
                    là dịch vụ cho thuê xe máy uy tín tại Đà Nẵng, cung cấp đa dạng các loại xe từ xe số đến
                    xe tay ga, phục vụ nhu cầu di chuyển của khách hàng trong thành phố. Website cho phép
                    đặt xe trực tuyến dễ dàng, hỗ trợ thanh toán an toàn và giao xe tận nơi. Với đội ngũ
                    chăm sóc khách hàng 24/7 và các xe được bảo dưỡng định kỳ, Bình Minh mang đến cho khách
                    hàng trải nghiệm thuê xe tiện lợi, an toàn và chất lượng.
                </p>
            </div>

            <div class="achievements-detail">
                <div class="row">
                    <div class="col-md-4 col-12">
                        <div class="card">
                            <div class="card-head text-center fs-2 mt-3 fw-bold text-1">Chi Nhánh</div>
                            <div class="card-title counter text-center fs-1 mt-1" data-target="100">
                                100+
                            </div>
                            <div class="card-body text-center">
                                Với hơn 100 chi nhanh trên địa bàn thành phố Đà Nẵng chúng tôi có thể đáp
                                ứng nhu cầu của mọi khách hàng.
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-12 my-3 my-md-0">
                        <div class="card">
                            <div class="card-head text-center fs-2 mt-3 fw-bold text-1">Đơn đặt xe</div>
                            <p class="card-title counter text-center fs-1 mt-1" data-target="10000">
                                10000+
                            </p>
                            <div class="card-body text-center">
                                Chúng tôi đã đạt được hơn 10000 đơn đặt xe từ khi hoạt động đến hiện nay,
                                điều đó chứng tỏ sự tin tưởng của khách hàng vào dịch vụ của chúng tôi.
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="card">
                            <div class="card-head text-center fs-2 mt-3 fw-bold text-1">Khách Hàng</div>
                            <div class="card-title counter text-center fs-1 mt-1" data-target="5000">
                                5000+
                            </div>
                            <div class="card-body text-center">
                                Vào tháng 11/2024 chúng vô cùng hạnh phúc và vui mừng khi hệ thống của chúng
                                tôi vừa đạt đến con số 5000 khách hàng.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-motorbike mt-5" data-bs-spy="scroll" data-bs-target="#list-example" data-bs-smooth-scroll="true"
        tabindex="0" id="list-motorbike">
        <div class="container w-100">
            <div class="d-flex align-items-center justify-content-center">
                <div class="line me-3 flex-grow-1"></div>
                <h2 class="text-uppercase text-center text-1">
                    danh sách xe máy cho thuê tại Đà nẵng 2024 của bình mình
                </h2>
                <div class="line ms-3 flex-grow-1"></div>
            </div>

            @foreach ($listMotorbikes as $listMotorbike => $category)
                {{-- @dd($category) --}}
                <div class="motorbike-category-title d-flex justify-content-between align-items-center mt-4 mb-3">
                    <h2 class="text-uppercase text-1 m-0">{{ $category['name'] }}</h2>
                    <a href="{{ route('home.motorbike.category', ['slug' => $category['slug']]) }}"
                        class="see-more text-1 text-decoration-none">Xem Tất Cả</a>
                </div>
                <div class="list-motorbike owl-carousel owl-theme">
                    @foreach ($category['motorbikes'] as $motorbike)
                        {{-- @dd($motorbike->pinnedImage->file_name); --}}
                        <div class="card mx-2 h-100">
                            <a href="{{ route('home.motorbike.detail', ['slugCategory' => $motorbike->categoryMotorbikeBySlug->slug, 'slugXe' => $motorbike->slug, 'id' => $motorbike->id]) }}"
                                class="list-unstyled text-decoration-none text-black">
                                <img src="{{ asset('storage/images/motorbike/' . $motorbike->pinnedImage->file_name) }}"
                                    class="card-img-top" alt="..." style="max-height: 130px" />
                                <div class="card-body pb-0">
                                    <h5 class="card-title text-center">{{ $motorbike->name }}</h5>
                                    <h6 class="card-title">Màu sắc: <span class="fw-normal">{{ $motorbike->color }}</span>
                                    </h6>

                                    <h6 class="card-title">
                                        Giá thuê: <span
                                            class="fw-normal">{{ format_price($motorbike->rental_price) }}/ngày</span>
                                    </h6>
                                    <h6 class="card-title">Trạng thái:
                                        @if ($motorbike->quantity > 0)
                                            <span class="badge text-bg-success fw-normal">còn xe</span>
                                        @else
                                            <span class="badge text-bg-warning fw-normal">hết xe</span>
                                        @endif
                                    </h6>
                                </div>
                            </a>
                            <div class="motorbike-action d-flex justify-content-center m-1">
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
                                <a href="{{ route('home.motorbike.detail', ['slugCategory' => $motorbike->categoryMotorbikeBySlug->slug, 'slugXe' => $motorbike->slug, 'id' => $motorbike->id]) }}"
                                    class="btn btn-primary ms-1">Xem Chi Tiết</a>
                            </div>


                        </div>
                    @endforeach

                </div>
            @endforeach



    </section>

    <section class="customer-review mt-5">
        <div class="container">
            <div class="d-flex align-items-center justify-content-center">
                <div class="line me-3 flex-grow-1"></div>
                <h2 class="text-uppercase text-center text-1">khách hàng nói gì về chúng tôi?</h2>
                <div class="line ms-3 flex-grow-1"></div>
            </div>
            <div class="list-customer-review owl-carousel owl-carousel-customer rounded mt-4">
                <div class="card mx-2 d-flex">
                    <div class="image-wrapper mt-2">
                        <img src="{{ asset('storage/images/user_1.jpg') }}" class="image-container" alt="..." />
                    </div>
                    <div class="card-body d-flex flex-column">
                        <div class="star d-flex justify-content-center fs-4 mt-2">
                            <i class="bi bi-star-fill mx-1 text-1"></i>
                            <i class="bi bi-star-fill mx-1 text-1"></i>
                            <i class="bi bi-star-fill mx-1 text-1"></i>
                            <i class="bi bi-star-fill mx-1 text-1"></i>
                            <i class="bi bi-star mx-1 text-1"></i>
                        </div>
                        <p class="card-text text-center mt-3">
                            Cửa hàng Bình Minh thật tuyệt vời! Xe máy ở đây rất mới và chất lượng. Nhân viên rất nhiệt tình,
                            hỗ trợ tôi tận tình khi lần đầu đến Đà Nẵng. Chắc chắn sẽ quay lại khi có dịp.
                        </p>
                        <h3 class="card-title text-center">Nguyễn Văn Hòa</h3>
                    </div>
                </div>
                <div class="card mx-2 d-flex">
                    <div class="image-wrapper mt-2">
                        <img src="{{ asset('storage/images/user_2.jpg') }}" class="image-container" alt="..." />
                    </div>
                    <div class="card-body d-flex flex-column">
                        <div class="star d-flex justify-content-center fs-4 mt-2">
                            <i class="bi bi-star-fill mx-1 text-1"></i>
                            <i class="bi bi-star-fill mx-1 text-1"></i>
                            <i class="bi bi-star-fill mx-1 text-1"></i>
                            <i class="bi bi-star-fill mx-1 text-1"></i>
                            <i class="bi bi-star mx-1 text-1"></i>
                        </div>
                        <p class="card-text text-center mt-3">
                            Tôi rất ấn tượng với dịch vụ của cửa hàng. Giá thuê hợp lý, thủ tục nhanh gọn. Xe chạy rất êm và
                            tiết kiệm xăng. Chủ cửa hàng còn hướng dẫn địa điểm du lịch cực kỳ chi tiết.
                        </p>
                        <h3 class="card-title text-center">Nguyễn Văn Hòa</h3>
                    </div>
                </div>
                <div class="card mx-2 d-flex">
                    <div class="image-wrapper mt-2">
                        <img src="{{ asset('storage/images/user_3.jpg') }}" class="image-container" alt="..." />
                    </div>
                    <div class="card-body d-flex flex-column">
                        <div class="star d-flex justify-content-center fs-4 mt-2">
                            <i class="bi bi-star-fill mx-1 text-1"></i>
                            <i class="bi bi-star-fill mx-1 text-1"></i>
                            <i class="bi bi-star-fill mx-1 text-1"></i>
                            <i class="bi bi-star-fill mx-1 text-1"></i>
                            <i class="bi bi-star mx-1 text-1"></i>
                        </div>
                        <p class="card-text text-center mt-3">
                            Đã thuê xe tại Bình Minh 3 ngày, xe máy hoạt động ổn định và không gặp bất kỳ vấn đề gì. Đội
                            ngũ nhân viên thân thiện và luôn sẵn sàng hỗ trợ.

                        </p>
                        <h3 class="card-title text-center">Lê Minh Quân</h3>
                    </div>
                </div>

                <div class="card mx-2 d-flex">
                    <div class="image-wrapper mt-2">
                        <img src="{{ asset('storage/images/user_4.png') }}" class="image-container" alt="..." />
                    </div>
                    <div class="card-body d-flex flex-column">
                        <div class="star d-flex justify-content-center fs-4 mt-2">
                            <i class="bi bi-star-fill mx-1 text-1"></i>
                            <i class="bi bi-star-fill mx-1 text-1"></i>
                            <i class="bi bi-star-fill mx-1 text-1"></i>
                            <i class="bi bi-star-fill mx-1 text-1"></i>
                            <i class="bi bi-star mx-1 text-1"></i>
                        </div>
                        <p class="card-text text-center mt-3">
                            Cảm ơn cửa hàng Bình Minh đã giúp chuyến đi Đà Nẵng của tôi trở nên trọn vẹn. Xe máy được giao
                            tận nơi đúng giờ và trong tình trạng rất tốt."



                        </p>
                        <h3 class="card-title text-center">Phạm Thùy Linh</h3>
                    </div>
                </div>

                <div class="card mx-2 d-flex">
                    <div class="image-wrapper mt-2">
                        <img src="{{ asset('storage/images/user_5.jpg') }}" class="image-container" alt="..." />
                    </div>
                    <div class="card-body d-flex flex-column">
                        <div class="star d-flex justify-content-center fs-4 mt-2">
                            <i class="bi bi-star-fill mx-1 text-1"></i>
                            <i class="bi bi-star-fill mx-1 text-1"></i>
                            <i class="bi bi-star-fill mx-1 text-1"></i>
                            <i class="bi bi-star-fill mx-1 text-1"></i>
                            <i class="bi bi-star mx-1 text-1"></i>
                        </div>
                        <p class="card-text text-center mt-3">
                            Xe tại Bình Minh được bảo dưỡng tốt, sạch sẽ, không có mùi xăng hay dầu. Giá cả minh bạch,
                            không có phí ẩn. Rất đáng tin cậy!
                        </p>
                        <h3 class="card-title text-center">Đỗ Hoàng An</h3>
                    </div>
                </div>

                <div class="card mx-2 d-flex">
                    <div class="image-wrapper mt-2">
                        <img src="{{ asset('storage/images/user_6.png') }}" class="image-container" alt="..." />
                    </div>
                    <div class="card-body d-flex flex-column">
                        <div class="star d-flex justify-content-center fs-4 mt-2">
                            <i class="bi bi-star-fill mx-1 text-1"></i>
                            <i class="bi bi-star-fill mx-1 text-1"></i>
                            <i class="bi bi-star-fill mx-1 text-1"></i>
                            <i class="bi bi-star-fill mx-1 text-1"></i>
                            <i class="bi bi-star mx-1 text-1"></i>
                        </div>
                        <p class="card-text text-center mt-3">
                            Tôi đã thuê xe tay ga ở đây và rất hài lòng. Cửa hàng có nhiều dòng xe để lựa chọn, phù hợp với
                            mọi nhu cầu. Nhân viên còn tặng bản đồ và mũ bảo hiểm miễn phí!
                        </p>
                        <h3 class="card-title text-center">Nguyễn Thị Bích</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="list-post mt-5">
        <div class="container">
            <div class="d-flex align-items-center justify-content-center">
                <div class="line me-3 flex-grow-1"></div>
                <h2 class="text-uppercase text-center text-1">một số mẹo du lịch đà nẵng</h2>
                <div class="line ms-3 flex-grow-1"></div>
            </div>
            <div class="row mt-4">
                @foreach ($listPosts as $post)
                    <div class="card-post col-lg-3 col-md-6 col-12 d-flex mt-4">
                        <a href="{{ route('home.post.detail', ['slug' => $post->slug, 'id' => $post->id]) }}" class="text-decoration-none">
                            <div class="card h-100 w-100">
                                <img src="{{ asset('storage/images/posts/' . $post->thumbnail) }}" class="card-img-top"
                                    alt="..." />
                                <div class="card-body mt-3 d-flex flex-column">
                                    <h5 class="card-title">{{ $post->title }}</h5>
                                    <span class="post-create-date text-body-secondary my-1 "><i class="bi bi-clock"></i>
                                        {{ $post->created_at }}</span>
                                    <p class="post-desc card-text text-body-secondary">
                                        {{ $post->short_description }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach


            </div>
        </div>
    </section>
@endsection


@push('order')
    @if (Auth::guard('customer')->check())
        <!-- Modal (chỉ một modal chung) -->
        <div class="modal fade" data-bs-backdrop="false" id="order" tabindex="-1"
            aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index: 111111111112 !important;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="" method="post" id="order-form">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5 text-center w-100 fw-bold" id="exampleModalLabel">
                                ĐƠN ĐẶT XE</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
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
                                    <input type="hidden" name="motorbike_id" class="motorbike_id"
                                        id="modal-motorbike-id">
                                    <input type="hidden" name="customer_id" class="customer_id" id="modal-customer-id"
                                        value="{{ Auth::guard('customer')->user()->id }}">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="mt-3">
                                                <label for="startDate">Ngày nhận xe:</label>
                                                <input type="datetime-local" id="startDate" class="form-control"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="mt-3">
                                                <label for="endDate">Ngày trả xe dự kiến:</label>
                                                <input type="datetime-local" id="endDate" class="form-control"
                                                    readonly>
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

@push('js')
    <script src="{{ asset('js/order.js') }}"></script>
@endpush
