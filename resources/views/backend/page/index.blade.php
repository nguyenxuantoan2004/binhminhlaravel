@extends('layouts.admin')

@section('title', 'Trang')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                <h5 class="">Danh sách trang</h5>
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
                    <a href="{{ route('page.index', ['search' => request('search')]) }}"
                        class="text-primary text-decoration-none {{ $status == 'all' ? 'fw-bolder' : '' }}">Tất cả<span
                            class="text-muted">({{ $counts['all'] }})</span></a> |

                    <a href="{{ route('page.index', ['status' => 'public', 'search' => request('search')]) }}"
                        class="text-primary text-decoration-none {{ $status == 'public' ? 'fw-bolder' : '' }}">Công
                        khai<span class="text-muted">({{ $counts['public'] }})</span></a> |

                    <a href="{{ route('page.index', ['status' => 'private', 'search' => request('search')]) }}"
                        class="text-primary text-decoration-none {{ $status == 'private' ? 'fw-bolder' : '' }}">Không công
                        khai<span class="text-muted">({{ $counts['private'] }})</span></a> |

                    <a href="{{ route('page.index', ['status' => 'pending', 'search' => request('search')]) }}"
                        class="text-primary text-decoration-none {{ $status == 'pending' ? 'fw-bolder' : '' }}">Chờ
                        duyệt<span class="text-muted">({{ $counts['pending'] }})</span></a> |

                    <a href="{{ route('page.index', ['status' => 'draft', 'search' => request('search')]) }}"
                        class="text-primary text-decoration-none {{ $status == 'draft' ? 'fw-bolder' : '' }}">Nháp<span class="text-muted">({{ $counts['draft'] }})</span></a> |

                    <a href="{{ route('page.index', ['status' => 'trash', 'search' => request('search')]) }}"
                        class="text-primary text-decoration-none {{ $status == 'trash' ? 'fw-bolder' : '' }}">Thùng
                        Rác<span class="text-muted">({{ $counts['trash'] }})</span></a>
                </div>

                <form action="" method="page" id="action-form" data-name-module="{{ $nameModule }}"
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
                                        <option value="public">Công khai</option>
                                        <option value="private">Không công khai</option>
                                        <option value="pending">Chờ duyệt</option>
                                        <option value="draft">Nháp</option>
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
                                <th scope="col">Tên trang</th>
                                <th scope="col">Người tạo</th>
                                @if ($status != 'trash')
                                    <th scope="col">Trạng Thái</th>
                                @endif
                                <th scope="col">Ngày tạo</th>
                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($pages->isNotEmpty())
                                @php
                                    $i = 0;
                                @endphp
                                @foreach ($pages as $page)
                                    @php
                                        $i++;
                                    @endphp
                                    <tr class="align-middle">
                                        <td>
                                            <input type="checkbox" name="selected" value="{{ $page->id }}">
                                        </td>
                                        <td>{{ $i }}</td>
                                        <td>
                                            <a href="{{ route('page.edit', ['page' => $page->id]) }}"
                                                class="text-decoration-none text-secondary">{{ $page->name }}</a>
                                        </td>
                                        <td><a href="{{ route('employee.edit', ['employee' => $page->employee_id]) }}" class="text-decoration-none text-secondary">{{ $page->employee->name }}</a></td>
                                        {{-- Accessors --}}
                                        @if ($status != 'trash')
                                            <td><span class="{{ $page->class }}">{{ $page->status_label }}</span>
                                            </td>
                                        @endif
                                        <td>{{ $page->created_at }}</td>
                                        <td>
                                            @if ($status == 'trash')
                                                <button class="btn btn-success btn-sm rounded-0 text-white btn-restore"
                                                    data-toggle="tooltip" title="Khôi phục" data-id="{{ $page->id }}"
                                                    data-name="{{ $page->title }}"
                                                    data-name-module="{{ $nameModule }}"
                                                    data-name-module-vietnamese="{{ $nameModuleVietnamese }}">
                                                    <i class="bi bi-arrow-clockwise"></i>
                                                </button>

                                                <button class="btn btn-danger btn-sm rounded-0 text-white btn-force-delete"
                                                    data-toggle="tooltip" title="Xóa vĩnh viễn"
                                                    data-id="{{ $page->id }}" data-name="{{ $page->title }}"
                                                    data-name-module="{{ $nameModule }}"
                                                    data-name-module-vietnamese="{{ $nameModuleVietnamese }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @else
                                                <a href="{{ route('page.edit', ['page' => $page->id]) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Sửa"><i
                                                        class="bi bi-pencil-square"></i></a>
                                                <button type="submit"
                                                    class="btn btn-danger btn-sm rounded-0 text-white btn-delete"
                                                    data-toggle="tooltip" title="Xóa tạm thời"
                                                    data-id="{{ $page->id }}" data-name="{{ $page->title }}"
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
                                    <td colspan="7">Không tồn tại dữ liệu</td>
                                </tr>
                            @endif

                        </tbody>
                    </table>
                </form>
                {{ $pages->appends(['status' => request('status'), 'search' => request('search')])->onEachSide(1)->links() }}

            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('js/action.js') }}"></script>
@endpush
