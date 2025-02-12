<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="{{ asset('storage/images/logo.png') }}" type="image/x-icon">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title>Trang login Admin</title>
</head>
<style>
    body {
        margin: 0;
        background-image: url("{{ asset('storage/images/background-admin.jpg') }}");
        background-repeat: no-repeat;
        background-size: cover;
    }


    body::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.1);
        /* Lớp phủ màu trắng, bạn có thể chỉnh độ mờ */
        /* backdrop-filter: blur(1px); */
        /* Làm mờ lớp phủ */
        z-index: -1;
        /* Đảm bảo lớp phủ ở phía sau nội dung */
    }

    .container form {
        background-color: rgba(238, 238, 238, 0.9);
        box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.5);
    }
</style>

<body>

    <div class="container w-100 d-flex justify-content-center align-items-center" style="height: 80vh">
        <form action="{{ route('admin.check') }}" method="post" class="p-3 border rounded my-5"
            style="width: 40%">
            @csrf
            @method('post')
            <h3 class="m-0 text-center mt-2 mb-3">Đăng Nhập Hệ Thống</h3>
            <fieldset>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="floatingInput" placeholder="Tên Tài Khoản"
                        name="email" value="{{ old('email') }}" />
                    <label for="floatingInput">Tên Tài Khoản</label>
                    @error('email')
                        <div class="text-danger">{!! $message !!}</div>
                    @enderror
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="floatingInput" placeholder="Mật Khẩu"
                        name="password" value="{{ old('password') }}" />
                    <label for="floatingInput">Mật Khẩu</label>
                    @error('password')
                        <div class="text-danger">{!! $message !!}</div>
                    @enderror
                </div>
                <div class="mb-3 form-check d-flex justify-content-between">
                    <div>
                        <input type="checkbox" class="form-check-input" id="exampleCheck1" name="remember"/>
                        <label class="form-check-label d-block" for="exampleCheck1">Ghi nhớ tôi</label>
                    </div>
                </div>
                @if (session('code') == 'error')
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

                    </div>
                @endif
                <button type="submit" class="btn btn-primary w-100 p-2">Đăng nhập</button>
            </fieldset>
        </form>
    </div>
</body>

</html>
