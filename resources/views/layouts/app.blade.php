<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="{{ asset('storage/images/logo.png') }}" type="image/x-icon">
    <link rel="stylesheet" href=" {{ asset('css/carousel/owl.carousel.min.css') }}" />
    <link rel="stylesheet" href=" {{ asset('css/carousel/owl.theme.default.min.css') }}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/animation.css') }}" />
    @stack('css')
    <title>@yield('title')</title>
</head>

<body>
    <div class="spinner d-flex justify-content-center align-items-center" style="min-height: 100vh">
        <div class="spinner-border text-success d-flex justify-content-center align-items-center" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <div id="wrapper">
        <header class="sticky-top bg-white shadow">
            <div class="container d-flex justify-content-between">
                <div class="header-logo">
                    <a href="{{ route('home') }}"><img src="{{ asset('storage/images/logo.png') }}"
                            alt="" /></a>
                </div>
                <nav class="w-75">
                    <ul class="d-flex list-unstyled justify-content-end w-100 h-100 align-items-center">
                        <li class="mx-3">
                            <a href="{{ route('home.motorbike.index') }}"
                                class="d-block w-100 h-100 text-decoration-none fs-4 fw-semibold text-1">Danh sách
                                xe</a>
                        </li>
                        <li class="mx-3">
                            <a href="{{ route('home.post.index') }}"
                                class="d-block w-100 h-100 text-decoration-none fs-4 fw-semibold text-1">Mẹo du lịch</a>
                        </li>
                        @foreach ($pages as $page)
                            <li class="mx-3">
                                <a href="{{ route('home.page.show', ['slugPage' => $page->slug]) }}"
                                    class="d-block w-100 h-100 text-decoration-none fs-4 fw-semibold text-1">{{ $page->name }}</a>
                            </li>
                        @endforeach

                    </ul>
                </nav>
                <div class="header-info w-auto">
                    <div class="d-flex justify-content-between h-100 align-items-center">
                        {{-- <div class="cart">
                            <a href="" class="text-black fw-semibold fs-2 mx-3">
                                <i class="bi bi-bell"></i>
                                <span class="cart-qty">6</span>
                            </a>
                        </div> --}}

                        <div class="menu-info">
                            @if (Auth::guard('customer')->check())
                                <i class="bi bi-person-circle text-black fw-semibold fs-2 mx-3"></i>
                                <ul class="user-dropdown-menu list-unstyled p-2 border border-black rounded">
                                    <li><a href="{{ route('home.profile.show') }}"
                                            class="text-decoration-none text-black fs-5">Hồ sơ</a></li>
                                    <li><a href="{{ route('home.order.show') }}"
                                            class="text-decoration-none text-black fs-5">Đơn đặt xe</a>
                                    </li>
                                    <li><a href="{{ route('home.pass.show') }}"
                                            class="text-decoration-none text-black fs-5">Đổi mật khẩu</a>
                                    </li>
                                    <li><a href="{{ route('home.logout') }}"
                                            class="text-decoration-none text-black fs-5">Đăng xuất</a>
                                    </li>
                                </ul>
                            @else
                                <a href="{{ route('home.login.show') }}">
                                    <i class="bi bi-person-circle text-black fw-semibold fs-2 mx-3"></i>
                                </a>
                            @endif


                        </div>
                    </div>
                </div>
            </div>
        </header>
        <main id="wp-content">
            @yield('content')
        </main>
        <footer class="mt-5 bg-footer">
            <div class="container ">
                <div class="row">
                    <div class="col-md-4 col-12">
                        <img src="{{ asset('storage/images/logo.png') }}" alt="hình ảnh logo" width="200px" />
                        <p class="fs-5">
                            <span class="fw-bold text-1">Bình Minh</span> là hệ thống cửa hàng cho thuê xe máy tại
                            Đà Nẵng
                        </p>
                        <p class="fw-bold fs-5">Giờ mở cửa: <span class="fw-normal">8H - 22H hằng ngày</span></p>
                        <div class="footer-contact fs-1">
                            <a href="https://www.facebook.com/cep.edu.vn" class="text-dark-emphasis"><i
                                    class="bi bi-facebook"></i></a>
                            <a href="https://www.facebook.com/cep.edu.vn" class="text-dark-emphasis"><i
                                    class="bi bi-youtube mx-2"></i></a>
                            <a href="https://www.facebook.com/cep.edu.vn" class="text-dark-emphasis"><i
                                    class="bi bi-instagram"></i></a>
                        </div>
                    </div>
                    <div class="about-us col-md-4 col-12 mt-5 px-4">
                        <h3 class="text-1 fw-bold mb-4">Về chúng tôi</h3>
                        <ul class="list-unstyled">
                            <li class="mb-3">
                                <a href="" class="text-decoration-none fs-5 text-black d-block w-100">Giới
                                    thiệu</a>
                            </li>
                            <li class="mb-3">
                                <a href="" class="text-decoration-none fs-5 text-black d-block w-100">Tin tức</a>
                            </li>
                            <li class="mb-3">
                                <a href="" class="text-decoration-none fs-5 text-black d-block w-100">Chính
                                    sách</a>
                            </li>
                            <li class="mb-3">
                                <a href="" class="text-decoration-none fs-5 text-black d-block w-100">Liên
                                    Hệ</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-4 col-12 mt-5">
                        <iframe
                            src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fcep.edu.vn&tabs=timeline&width=340&height=500&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true&appId"
                            width="340" height="300" style="border:none;overflow:hidden" scrolling="no"
                            frameborder="0" allowfullscreen="true"
                            allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
                    </div>
                </div>
            </div>
            <p class="text-center m-0 bg-secondary p-2 text-light">&copy; 2024 Bình Minh. All rights reserved.</p>
        </footer>
        <div id="btn-top">
            <i class="bi bi-arrow-up-square"></i>
        </div>

        @stack('order')
    </div>
    <script
        src="https://messenger.svc.chative.io/static/v1.0/channels/sfd567b11-7d68-4239-ac81-3c73e5fa1201/messenger.js?mode=livechat"
        defer="defer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/home.js') }}"></script>
    <script src="{{ asset('js/carousel/owl.carousel.min.js') }} "></script>
    @stack('js')
</body>

</html>
