@extends('layouts.admin')

@section('title', 'Trang Đơn Đặt Xe')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                <h5 class="">Danh sách đơn đặt xe</h5>
                <div class="form-search form-inline">
                    <form action="{{ url()->current() }}" class="d-flex" method="get">
                        @if (request()->has('status'))
                            <input type="hidden" name="status" value="{{ request()->get('status') }}">
                        @endif

                        <input type="text" name="search" class="form-control form-search me-1" style="width: 65%;"
                            placeholder="Tìm kiếm" value="{{ old('search', request('search')) }}">

                        <button type="submit" class="form-control form-search btn btn-primary" style="width: 35%;">Tìm
                            kiếm</button>
                    </form>
                </div>
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
                <div class="analytic">
                    <a href="{{ route('invoice.index', ['search' => request('search')]) }}"
                        class="text-primary text-decoration-none {{ $status == 'all' ? 'fw-bolder' : '' }}">Tất cả<span
                            class="text-muted">({{ $counts['all'] }})</span></a> |

                    <a href="{{ route('invoice.index', ['status' => 'pending', 'search' => request('search')]) }}"
                        class="text-primary text-decoration-none {{ $status == 'pending' ? 'fw-bolder' : '' }}">Chờ xác
                        nhận<span class="text-muted">({{ $counts['pending'] }})</span></a> |

                    <a href="{{ route('invoice.index', ['status' => 'confirmed', 'search' => request('search')]) }}"
                        class="text-primary text-decoration-none {{ $status == 'confirmed' ? 'fw-bolder' : '' }}">Đã xác
                        nhận<span class="text-muted">({{ $counts['confirmed'] }})</span></a> |

                    <a href="{{ route('invoice.index', ['status' => 'delivering', 'search' => request('search')]) }}"
                        class="text-primary text-decoration-none {{ $status == 'delivering' ? 'fw-bolder' : '' }}">Đang
                        giao xe<span class="text-muted">({{ $counts['delivering'] }})</span></a> |

                    <a href="{{ route('invoice.index', ['status' => 'waiting_for_pickup', 'search' => request('search')]) }}"
                        class="text-primary text-decoration-none {{ $status == 'waiting_for_pickup' ? 'fw-bolder' : '' }}">Chờ
                        nhận xe<span class="text-muted">({{ $counts['waiting_for_pickup'] }})</span></a> |

                    <a href="{{ route('invoice.index', ['status' => 'picked_up', 'search' => request('search')]) }}"
                        class="text-primary text-decoration-none {{ $status == 'picked_up' ? 'fw-bolder' : '' }}">Đã nhận
                        xe<span class="text-muted">({{ $counts['picked_up'] }})</span></a> |

                    <a href="{{ route('invoice.index', ['status' => 'completed', 'search' => request('search')]) }}"
                        class="text-primary text-decoration-none {{ $status == 'completed' ? 'fw-bolder' : '' }}">Hoàn
                        thành<span class="text-muted">({{ $counts['completed'] }})</span></a> |

                    <a href="{{ route('invoice.index', ['status' => 'cancelled', 'search' => request('search')]) }}"
                        class="text-primary text-decoration-none {{ $status == 'cancelled' ? 'fw-bolder' : '' }}">Đã
                        hủy<span class="text-muted">({{ $counts['cancelled'] }})</span></a> |

                    <a href="{{ route('invoice.index', ['status' => 'trash', 'search' => request('search')]) }}"
                        class="text-primary text-decoration-none {{ $status == 'trash' ? 'fw-bolder' : '' }}">Thùng
                        Rác<span class="text-muted">({{ $counts['trash'] }})</span></a>
                </div>

                <form action="" method="post" id="action-form" data-name-module="{{ $nameModule }}"
                    data-name-module-vietnamese="{{ $nameModuleVietnamese }}">

                    <div class="form-action form-inline py-3">
                        <div class="row">
                            <div class="col-4 d-flex">
                                <select class="form-control me-2 action" id="action" name="action">
                                    <option value="0">Chọn</option>
                                    @if ($status == 'trash')
                                        <option value="restore">Khôi phục</option>
                                        <option value="forceDelete">Xóa vĩnh viễn</option>
                                    @else
                                        <option value="pending">Chờ xác nhận</option>
                                        <option value="confirmed">Đã xác nhận</option>
                                        <option value="delivering">Đang giao xe</option>
                                        <option value="waiting_for_pickup">Chờ nhận xe</option>
                                        <option value="picked_up">Đã nhận xe</option>
                                        {{-- <option value="completed">Hoàn thành</option> --}}
                                        <option value="cancelled">Đã hủy</option>
                                        <option value="delete">Xóa tạm thời</option>
                                    @endif
                                </select>
                                <input type="submit" name="btn-action" value="Áp dụng" class="btn btn-primary btn-action">
                            </div>
                        </div>

                    </div>
                    @if (request('search'))
                        <i>Kết quả tìm kiếm cho: <strong>{{ request('search') }}</strong></i>
                    @endif
                    <table class="table table-striped table-checkall">
                        <thead>
                            <tr>
                                <th scope="col">
                                    <input name="checkall" id="checkall" type="checkbox">
                                </th>
                                <th scope="col">#</th>
                                <th scope="col">Mã đặt xe</th>
                                <th scope="col">Tên khách hàng</th>
                                <th scope="col">Ngày đặt</th>
                                <th scope="col">Ngày trả dự kiến</th>
                                <th scope="col">Giá thuê</th>
                                <th scope="col">Tổng tiền dự kiến</th>
                                @if ($status != 'trash')
                                    <th scope="col">Trạng Thái</th>
                                @endif
                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($invoices->isNotEmpty())
                                @php
                                    $i = 0;
                                @endphp
                                @foreach ($invoices as $invoice)
                                    @php
                                        $i++;
                                        // Lấy thông tin khách hàng
                                        $customerName = $invoice->customer->name ?? 'Không có tên khách hàng'; // Truy xuất tên khách hàng từ bảng customers
                                        // Tính giá thuê (có thể lấy từ bảng motorbikes hoặc từ dữ liệu trong bảng invoices)
                                        // $rentalPrice = $invoice->motorbike->rental_price ?? 0; // Giả sử bạn có trường rental_price trong bảng motorbikes
                                        // // Tính tổng tiền dự kiến
                                        // $totalAmount =
                                        //     $invoice->total_cost + ($invoice->total_cost * $invoice->vat_fee) / 100;
                                    @endphp
                                    <tr class="align-middle">
                                        <td>
                                            <input type="checkbox" name="selected" value="{{ $invoice->id }}">
                                        </td>
                                        <td>{{ $i }}</td>
                                        <td><a href="{{ route('invoice.edit', ['invoice' => $invoice->id]) }}"
                                            class="text-decoration-none text-secondary">{{ $invoice->id }}</a></td>
                                        <td>
                                            <a href="{{ route('invoice.edit', ['invoice' => $invoice->id]) }}"
                                                class="text-decoration-none text-secondary">{{ $customerName }}</a>
                                        </td>
                                        <td>{{ $invoice->start_date }}</td>
                                        <td>{{ $invoice->expected_return_date }}</td>
                                        <td>{{ number_format($invoice->motorbike->rental_price, 0, ',', '.') }} VNĐ</td>
                                        <!-- Hiển thị giá thuê -->
                                        <td>{{ number_format($invoice->total_amount, 0, ',', '.') }} VNĐ</td>
                                        <!-- Hiển thị tổng tiền dự kiến -->
                                        @if ($status != 'trash')
                                            <td><span
                                                    class="{{ $invoice->status_class }}">{{ $invoice->status_label }}</span>
                                            </td>
                                        @endif
                                        <td>
                                            @if ($status == 'trash')
                                                <button class="btn btn-success btn-sm rounded-0 text-white btn-restore"
                                                    data-toggle="tooltip" title="Khôi phục"
                                                    data-id="{{ $invoice->id }}" data-name="{{ $invoice->id }}"
                                                    data-name-module="{{ $nameModule }}"
                                                    data-name-module-vietnamese="{{ $nameModuleVietnamese }}">
                                                    <i class="bi bi-arrow-clockwise"></i>
                                                </button>

                                                <button class="btn btn-danger btn-sm rounded-0 text-white btn-force-delete"
                                                    data-toggle="tooltip" title="Xóa vĩnh viễn"
                                                    data-id="{{ $invoice->id }}" data-name="{{ $invoice->id }}"
                                                    data-name-module="{{ $nameModule }}"
                                                    data-name-module-vietnamese="{{ $nameModuleVietnamese }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @else
                                                <a href="{{ route('invoice.edit', ['invoice' => $invoice->id]) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Sửa">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <button type="submit"
                                                    class="btn btn-danger btn-sm rounded-0 text-white btn-delete"
                                                    data-toggle="tooltip" title="Xóa tạm thời"
                                                    data-id="{{ $invoice->id }}" data-name="{{ $invoice->id }}"
                                                    data-name-module="{{ $nameModule }}"
                                                    data-name-module-vietnamese="{{ $nameModuleVietnamese }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="8">Không tồn tại dữ liệu</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>

                </form>
                {{ $invoices->appends(['status' => request('status'), 'search' => request('search')])->onEachSide(1)->links() }}

            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('js/action.js') }}"></script>
@endpush
