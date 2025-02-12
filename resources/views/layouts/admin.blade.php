<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="{{ asset('storage/images/logo.png') }}" type="image/x-icon">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}" />
    @stack('css')
    <title>@yield('title')</title>
</head>

<body>
    <div id="wrapper">
        <header class="shadow sticky-top bg-white">
            <div class="container-fluid">
                <nav class="navbar-light fs-4 fw-bold p-2 d-flex">
                    <div class="navbar-brand d-flex align-items-center me-4">
                        <a href="{{ route('admin.index') }}" class="text-decoration-none text-primary">
                            Bình Minh <span class="text-danger">SYSTEM</span>
                        </a>
                    </div>
                    <div class="navbar-right d-flex justify-content-between w-100">
                        <div class="btn-group btn-first mr-auto" role="group">
                            <button type="button" class="btn dropdown-toggle fs-4 border-0" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="bi bi-plus-circle"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="{{ route('motorbike.create') }}"
                                    class="dropdown-item"> Thêm Mới Xe
                                </a></li>
                                <li><a href="{{ route('post.create') }}"
                                    class="dropdown-item"> Thêm Mới Bài Viết
                                </a></li>
                            </ul>
                        </div>

                        <div class="btn-group " role="group">
                            <button type="button" class="btn dropdown-toggle fs-5 border-0" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                {{ Auth::guard('web')->user()->name }}
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('profile.show') }}">Thông tin cá nhân</a>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('pass.show') }}">Đổi mật khẩu</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.logout') }}">Đăng Xuất</a></li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        </header>
        <main style="min-height: 100vh" class="d-flex">

            <div id="sidebar" class="mt-3 fs-5 bg-white m-2 ps-2" style="width: 15%">
                <ul id="sidebar-menu" class="p-0">
                    <li
                        class="nav-item d-flex flex-column mb-2 {{ session('ModuleActive') == 'dashboard' ? 'active' : '' }}">
                        <div class="menu-title d-flex justify-content-between">
                            <a href="{{ route('admin.index') }}" class="nav-link text-decoration-none link-secondary">
                                <i class="bi bi-folder2"></i> Dashboard
                            </a>
                            
                        </div>
                    </li>

                    <li
                        class="nav-item d-flex flex-column mb-2 {{ session('ModuleActive') == 'invoice' ? 'active' : '' }}">
                        <div class="menu-title d-flex justify-content-between">
                            <a href="{{ route('invoice.index') }}"
                                class="nav-link text-decoration-none link-secondary">
                                <i class="bi bi-folder2"></i> Đơn đặt xe
                            </a>
                            <i
                                class="{{ session('ModuleActive') == 'invoice' ? 'bi-chevron-down' : 'bi bi-chevron-right' }}"></i>
                        </div>

                        <ul class="sub-menu list-unstyled">
                            <li class="nav-item">
                                <a href="{{ route('invoice.create') }}"
                                    class="nav-link text-decoration-none link-secondary">Thêm mới</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('invoice.index') }}"
                                    class="nav-link text-decoration-none link-secondary">Danh sách</a>
                            </li>
                        </ul>
                    </li>
                    <li
                        class="nav-item d-flex flex-column mb-2 {{ session('ModuleActive') == 'category' ? 'active' : '' }}">
                        <div class="menu-title d-flex justify-content-between">
                            <a href="{{ route('motorbike-category.index') }}"
                                class="nav-link text-decoration-none link-secondary">
                                <i class="bi bi-folder2"></i> Loại Xe
                            </a>
                            <i
                                class="{{ session('ModuleActive') == 'category' ? 'bi-chevron-down' : 'bi bi-chevron-right' }}"></i>
                        </div>

                        <ul class="sub-menu list-unstyled">
                            <li class="nav-item">
                                <a href="{{ route('motorbike-category.create') }}"
                                    class="nav-link text-decoration-none link-secondary">Thêm mới</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('motorbike-category.index') }}"
                                    class="nav-link text-decoration-none link-secondary">Danh sách</a>
                            </li>
                        </ul>
                    </li>
                    <li
                        class="nav-item d-flex flex-column mb-2 {{ session('ModuleActive') == 'motorbike' ? 'active' : '' }}">
                        <div class="menu-title d-flex justify-content-between">
                            <a href="{{ route('motorbike.index') }}"
                                class="nav-link text-decoration-none link-secondary">
                                <i class="bi bi-folder2"></i> Xe
                            </a>
                            <i
                                class="{{ session('ModuleActive') == 'motorbike' ? 'bi-chevron-down' : 'bi bi-chevron-right' }}"></i>
                        </div>

                        <ul class="sub-menu list-unstyled">
                            <li class="nav-item">
                                <a href="{{ route('motorbike.create') }}"
                                    class="nav-link text-decoration-none link-secondary">Thêm mới</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('motorbike.index') }}"
                                    class="nav-link text-decoration-none link-secondary">Danh sách</a>
                            </li>
                        </ul>
                    </li>
                    <li
                        class="nav-item d-flex flex-column mb-2 {{ session('ModuleActive') == 'supplier' ? 'active' : '' }}">
                        <div class="menu-title d-flex justify-content-between">
                            <a href="{{ route('supplier.index') }}"
                                class="nav-link text-decoration-none link-secondary">
                                <i class="bi bi-folder2"></i> Nhà cung cấp
                            </a>
                            <i
                                class="{{ session('ModuleActive') == 'supplier' ? 'bi-chevron-down' : 'bi bi-chevron-right' }}"></i>
                        </div>

                        <ul class="sub-menu list-unstyled">
                            <li class="nav-item">
                                <a href="{{ route('supplier.create') }}"
                                    class="nav-link text-decoration-none link-secondary">Thêm mới</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('supplier.index') }}"
                                    class="nav-link text-decoration-none link-secondary">Danh sách</a>
                            </li>
                        </ul>
                    </li>

                    <li
                        class="nav-item d-flex flex-column mb-2 {{ session('ModuleActive') == 'customer' ? 'active' : '' }}">
                        <div class="menu-title d-flex justify-content-between">
                            <a href="{{ route('customer.index') }}"
                                class="nav-link text-decoration-none link-secondary">
                                <i class="bi bi-folder2"></i> Khách hàng
                            </a>
                            <i
                                class="{{ session('ModuleActive') == 'customer' ? 'bi-chevron-down' : 'bi bi-chevron-right' }}"></i>
                        </div>

                        <ul class="sub-menu list-unstyled">
                            <li class="nav-item">
                                <a href="{{ route('customer.create') }}"
                                    class="nav-link text-decoration-none link-secondary">Thêm mới</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('customer.index') }}"
                                    class="nav-link text-decoration-none link-secondary">Danh sách</a>
                            </li>
                        </ul>
                    </li>

                    <li
                        class="nav-item d-flex flex-column mb-2 {{ session('ModuleActive') == 'employee' ? 'active' : '' }}">
                        <div class="menu-title d-flex justify-content-between">
                            <a href="{{ route('employee.index') }}"
                                class="nav-link text-decoration-none link-secondary">
                                <i class="bi bi-folder2"></i> Nhân viên
                            </a>
                            <i
                                class="{{ session('ModuleActive') == 'employee' ? 'bi-chevron-down' : 'bi bi-chevron-right' }}"></i>
                        </div>

                        <ul class="sub-menu list-unstyled">
                            <li class="nav-item">
                                <a href="{{ route('employee.create') }}"
                                    class="nav-link text-decoration-none link-secondary">Thêm mới</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('employee.index') }}"
                                    class="nav-link text-decoration-none link-secondary">Danh sách</a>
                            </li>
                        </ul>
                    </li>

                    <li
                        class="nav-item d-flex flex-column mb-2 {{ session('ModuleActive') == 'position' ? 'active' : '' }}">
                        <div class="menu-title d-flex justify-content-between">
                            <a href="{{ route('position.index') }}"
                                class="nav-link text-decoration-none link-secondary">
                                <i class="bi bi-folder2"></i> Chức vụ
                            </a>
                            <i
                                class="{{ session('ModuleActive') == 'position' ? 'bi-chevron-down' : 'bi bi-chevron-right' }}"></i>
                        </div>

                        <ul class="sub-menu list-unstyled">
                            <li class="nav-item">
                                <a href="{{ route('position.create') }}"
                                    class="nav-link text-decoration-none link-secondary">Thêm mới</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('position.index') }}"
                                    class="nav-link text-decoration-none link-secondary">Danh sách</a>
                            </li>
                        </ul>
                    </li>

                    <li
                        class="nav-item d-flex flex-column mb-2 {{ session('ModuleActive') == 'branch' ? 'active' : '' }}">
                        <div class="menu-title d-flex justify-content-between">
                            <a href="{{ route('branch.index') }}"
                                class="nav-link text-decoration-none link-secondary">
                                <i class="bi bi-folder2"></i> Chi Nhánh
                            </a>
                            <i
                                class="{{ session('ModuleActive') == 'branch' ? 'bi-chevron-down' : 'bi bi-chevron-right' }}"></i>
                        </div>

                        <ul class="sub-menu list-unstyled">
                            <li class="nav-item">
                                <a href="{{ route('branch.create') }}"
                                    class="nav-link text-decoration-none link-secondary">Thêm mới</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('branch.index') }}"
                                    class="nav-link text-decoration-none link-secondary">Danh sách</a>
                            </li>
                        </ul>
                    </li>
                    <li
                        class="nav-item d-flex flex-column mb-2 {{ session('ModuleActive') == 'post' ? 'active' : '' }}">
                        <div class="menu-title d-flex justify-content-between">
                            <a href="{{ route('post.index') }}" class="nav-link text-decoration-none link-secondary">
                                <i class="bi bi-folder2"></i> Bài Viết
                            </a>
                            <i
                                class="{{ session('ModuleActive') == 'post' ? 'bi-chevron-down' : 'bi bi-chevron-right' }}"></i>
                        </div>

                        <ul class="sub-menu list-unstyled">
                            <li class="nav-item">
                                <a href="{{ route('post.create') }}"
                                    class="nav-link text-decoration-none link-secondary">Thêm mới</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('post.index') }}"
                                    class="nav-link text-decoration-none link-secondary">Danh sách</a>
                            </li>
                        </ul>
                    </li>

                    <li
                        class="nav-item d-flex flex-column mb-2 {{ session('ModuleActive') == 'page' ? 'active' : '' }}">
                        <div class="menu-title d-flex justify-content-between">
                            <a href="{{ route('page.index') }}" class="nav-link text-decoration-none link-secondary">
                                <i class="bi bi-folder2"></i> Trang
                            </a>
                            <i
                                class="{{ session('ModuleActive') == 'page' ? 'bi-chevron-down' : 'bi bi-chevron-right' }}"></i>
                        </div>

                        <ul class="sub-menu list-unstyled">
                            <li class="nav-item">
                                <a href="{{ route('page.create') }}"
                                    class="nav-link text-decoration-none link-secondary">Thêm mới</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('page.index') }}"
                                    class="nav-link text-decoration-none link-secondary">Danh sách</a>
                            </li>
                        </ul>
                    </li>

                </ul>
            </div>
            <div id="wp-content" class="bg-secondary-subtle" style="width: 85%;">
                <div class="container-fluid py-3">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('js')
    <script src="{{ asset('js/admin.js') }}"></script>

</body>

</html>
