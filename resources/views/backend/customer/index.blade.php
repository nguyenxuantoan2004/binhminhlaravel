@extends('layouts.admin')

@section('title', 'Trang khách hàng')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                <h5 class="">Danh sách khách hàng</h5>
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
                    <a href="{{ route('customer.index', ['search' => request('search')]) }}"
                        class="text-primary text-decoration-none {{ $status == 'all' ? 'fw-bolder' : '' }}">Tất cả<span
                            class="text-muted">({{ $counts['all'] }})</span></a> |

                    <a href="{{ route('customer.index', ['status' => 'active', 'search' => request('search')]) }}"
                        class="text-primary text-decoration-none {{ $status == 'active' ? 'fw-bolder' : '' }}">Hoạt
                        động<span class="text-muted">({{ $counts['active'] }})</span></a> |

                    <a href="{{ route('customer.index', ['status' => 'temporary_lock', 'search' => request('search')]) }}"
                        class="text-primary text-decoration-none {{ $status == 'temporary_lock' ? 'fw-bolder' : '' }}">Khóa
                        tạm thời<span class="text-muted">({{ $counts['temporary_lock'] }})</span></a> |

                    <a href="{{ route('customer.index', ['status' => 'permanently_locked', 'search' => request('search')]) }}"
                        class="text-primary text-decoration-none {{ $status == 'permanently_locked' ? 'fw-bolder' : '' }}">Khóa
                        vĩnh viễn<span class="text-muted">({{ $counts['permanently_locked'] }})</span></a> |

                    <a href="{{ route('customer.index', ['status' => 'trash', 'search' => request('search')]) }}"
                        class="text-primary text-decoration-none {{ $status == 'trash' ? 'fw-bolder' : '' }}">Thùng
                        Rác<span class="text-muted">({{ $counts['trash'] }})</span></a>
                </div>

                <form action="" method="post" id="action-form" data-name-module="{{ $nameModule }}"
                    data-name-module-vietnamese="{{ $nameModuleVietnamese }}">

                    <div class="form-action form-inline py-3">
                        <div class="row">
                            <div class="col-4 d-flex">
                                <select class="form-control me-2 action" id="" name="action">
                                    <option value="0">Chọn</option>
                                    @if ($status == 'trash')
                                        <option value="restore">Khôi phục</option>
                                        <option value="forceDelete">Xóa vĩnh viễn</option>
                                    @else
                                        <option value="active">Hoạt động</option>
                                        <option value="temporary_lock">Khóa tạm thời</option>
                                        <option value="permanently_locked">Khóa vĩnh viễn</option>
                                        <option value="delete">Xóa</option>
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
                                <th scope="col">Tên khách hàng</th>
                                <th scope="col">Số điện thoại</th>
                                <th scope="col">Địa chỉ</th>
                                <th scope="col">Email</th>
                                @if ($status != 'trash')
                                    <th scope="col">Trạng Thái</th>
                                @endif
                                <th scope="col">Ngày tạo</th>
                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($customers->isNotEmpty())
                                @php
                                    $i = 0;
                                @endphp
                                @foreach ($customers as $customer)
                                    @php
                                        $i++;
                                    @endphp
                                    <tr class="align-middle">
                                        <td>
                                            <input type="checkbox" name="selected" value="{{ $customer->id }}">
                                        </td>
                                        <td>{{ $i }}</td>
                                        <td>
                                            <a href="{{ route('customer.edit', ['customer' => $customer->id]) }}"
                                                class="text-decoration-none text-secondary">{{ $customer->name }}</a>
                                        </td>
                                        <td>
                                            {{ $customer->phone_number }}
                                        </td>
                                        <td>
                                            {{ $customer->address }}
                                        </td>
                                        <td>
                                            {{ $customer->email }}
                                        </td>
                                        {{-- Accessors --}}
                                        @if ($status != 'trash')
                                            <td><span class="{{ $customer->class }}">{{ $customer->status_label }}</span>
                                            </td>
                                        @endif
                                        <td>{{ $customer->created_at }}</td>
                                        <td>
                                            @if ($status == 'trash')
                                                <button class="btn btn-success btn-sm rounded-0 text-white btn-restore"
                                                    data-toggle="tooltip" title="Khôi phục" data-id="{{ $customer->id }}"
                                                    data-name="{{ $customer->name }}"
                                                    data-name-module="{{ $nameModule }}"
                                                    data-name-module-vietnamese="{{ $nameModuleVietnamese }}">
                                                    <i class="bi bi-arrow-clockwise"></i>
                                                </button>

                                                <button class="btn btn-danger btn-sm rounded-0 text-white btn-force-delete"
                                                    data-toggle="tooltip" title="Xóa vĩnh viễn"
                                                    data-id="{{ $customer->id }}" data-name="{{ $customer->name }}"
                                                    data-name-module="{{ $nameModule }}"
                                                    data-name-module-vietnamese="{{ $nameModuleVietnamese }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @else
                                                <a href="{{ route('customer.edit', ['customer' => $customer->id]) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Sửa"><i
                                                        class="bi bi-pencil-square"></i></a>
                                                <button type="submit"
                                                    class="btn btn-danger btn-sm rounded-0 text-white btn-delete"
                                                    data-toggle="tooltip" title="Xóa tạm thời"
                                                    data-id="{{ $customer->id }}" data-name="{{ $customer->name }}"
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
                                    <td colspan="9">Không tồn tại dữ liệu</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </form>
                {{ $customers->appends(['status' => request('status'), 'search' => request('search')])->onEachSide(1)->links() }}

            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('js/action.js') }}"></script>
@endpush
