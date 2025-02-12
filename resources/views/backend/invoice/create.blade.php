@extends('layouts.admin')

@section('title', 'Trang Thêm Mới Đơn Đặt Xe')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold text-uppercase">
                <h4>Thêm mới đơn đặt xe</h4>
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
                <form action="{{ route('invoice.store') }}" method="POST" id="orderForm">
                    @csrf
                    @method('POST')

                    <!-- Ngày đặt và ngày trả dự kiến -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Ngày đặt</label>
                            <input type="datetime-local" name="start_date" id="start_date" class="form-control"
                                min="" value="{{ now()->format('Y-m-d\TH:i') }}">
                            @error('start_date')
                                <div class="text-danger">{!! $message !!}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="expected_return_date" class="form-label">Ngày trả dự kiến</label>
                            <input type="datetime-local" name="expected_return_date" id="expected_return_date"
                                class="form-control" min="">
                            @error('expected_return_date')
                                <div class="text-danger">{!! $message !!}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Chọn xe -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="motorbike_id" class="form-label">Chọn xe</label>
                            <select name="motorbike_id" id="motorbike_id" class="form-control">
                                <option value="">Chọn xe</option>
                                @foreach ($motorbikes as $motorbike)
                                    <option value="{{ $motorbike->id }}" data-price="{{ $motorbike->rental_price }}"
                                        {{ old('motorbike_id') == $motorbike->id ? 'selected' : '' }}>
                                        {{ $motorbike->name }}                                   </option>
                                @endforeach
                            </select>
                            @error('motorbike_id')
                                <div class="text-danger">{!! $message !!}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="rental_price" class="form-label">Giá thuê xe (VNĐ/ngày)</label>
                            <input type="text" id="rental_price" class="form-control" readonly>
                        </div>
                    </div>

                    <!-- Phương thức nhận xe -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="motorbike_receipt_method" class="form-label">Phương thức nhận xe</label>
                            <select name="motorbike_receipt_method" id="motorbike_receipt_method" class="form-control">
                                <option value="store" selected>Nhận tại cửa hàng</option>
                                <option value="delivery">Giao tận nơi</option>
                            </select>
                            @error('motorbike_receipt_method')
                                <div class="text-danger">{!! $message !!}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3" id="branch_selection">
                            <label for="branch_id" class="form-label">Chi nhánh</label>
                            <select name="branch_id" id="branch_id" class="form-control">
                                <option value="">Chọn chi nhánh</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}"
                                        {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('branch_id')
                                <div class="text-danger">{!! $message !!}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3" id="pickup_location" style="display: none;">
                            <label for="motorbike_pickup_location" class="form-label">Địa điểm nhận xe</label>
                            <input type="text" name="motorbike_pickup_location" id="motorbike_pickup_location"
                                class="form-control" value="{{ old('motorbike_pickup_location') }}">
                            @error('motorbike_pickup_location')
                                <div class="text-danger">{!! $message !!}</div>
                            @enderror
                        </div>


                        <div class="col-md-6 mb-3">
                            <label for="customer_id" class="form-label">Khách hàng</label>
                            <select name="customer_id" id="customer_id" class="form-control">
                                <option value="">Chọn khách hàng</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}"
                                        {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }} | {{ $customer->phone_number }}
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <div class="text-danger">{!! $message !!}</div>
                            @enderror
                        </div>
                        <!-- Chi phí và thời gian -->
                        <div class="col-md-6 mb-3" id="additional_fees_row">
                            <label for="additional_fees" class="form-label">Phụ phí (VNĐ)</label>
                            <input type="number" name="additional_fees" id="additional_fees" class="form-control"
                                value="0" readonly>
                            @error('additional_fees')
                                <div class="text-danger">{!! $message !!}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="total_rental_duration" class="form-label">Tổng thời gian thuê</label>
                            <input type="text" id="total_rental_duration" name="total_rental_duration"
                                class="form-control" readonly value="0 ngày 0 giờ">
                            @error('total_rental_duration')
                                <div class="text-danger">{!! $message !!}</div>
                            @enderror
                        </div>


                        <div class="col-md-6 mb-3">
                            <label for="vat_fee" class="form-label">Thuế VAT (%)</label>
                            <input type="number" name="vat_fee" id="vat_fee" class="form-control" value="0">
                            @error('vat_fee')
                                <div class="text-danger">{!! $message !!}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="total_cost" class="form-label">Tổng chi phí (VNĐ)</label>
                            <input type="text" id="total_cost" name="total_cost" class="form-control" readonly
                                value="0">
                            @error('total_cost')
                                <div class="text-danger">{!! $message !!}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="total_amount" class="form-label">Tổng tiền thanh toán (VNĐ)</label>
                            <input type="text" id="total_amount" name="total_amount" class="form-control" readonly
                                value="0">
                            @error('total_amount')
                                <div class="text-danger">{!! $message !!}</div>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Tạo đơn mới</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            const motorbikeSelect = $('#motorbike_id');
            const rentalPriceInput = $('#rental_price'); // Giá thuê theo ngày
            const startDateInput = $('#start_date');
            const expectedReturnDateInput = $('#expected_return_date');
            const totalRentalDurationInput = $('#total_rental_duration');
            const additionalFeesInput = $('#additional_fees');
            const vatFeeInput = $('#vat_fee');
            const totalCostInput = $('#total_cost');
            const totalAmountInput = $('#total_amount');
            const receiptMethodSelect = $('#motorbike_receipt_method');
            const branchSelection = $('#branch_selection');
            const pickupLocation = $('#pickup_location');
            const additionalFeesRow = $('#additional_fees_row'); // Row chứa ô phụ phí

            // Đặt giá trị mặc định cho ngày đặt và ngày trả
            const now = new Date();
            now.setHours(now.getHours() + 7); // Chuyển sang giờ Việt Nam (UTC+7)
            const nowFormatted = now.toISOString().slice(0, 16);
            startDateInput.val(nowFormatted).attr('min', nowFormatted);
            expectedReturnDateInput.attr('min', nowFormatted);

            // Cập nhật giá trị min cho ngày trả khi ngày đặt thay đổi
            startDateInput.on('change', function() {
                const startDate = $(this).val();
                expectedReturnDateInput.attr('min', startDate);
                calculateCost();
            });

            // Ẩn/hiện chi nhánh hoặc địa điểm nhận xe
            receiptMethodSelect.on('change', function() {
                const method = $(this).val();
                if (method === 'delivery') {
                    branchSelection.hide();
                    pickupLocation.show();
                } else {
                    branchSelection.show();
                    pickupLocation.hide();
                }
                calculateCost();
            });

            // Khi chọn xe, cập nhật giá thuê theo ngày và tính toán chi phí
            motorbikeSelect.on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const rentalPrice = parseFloat(selectedOption.data('price')) || 0; // Giá thuê theo ngày
                rentalPriceInput.val(rentalPrice); // Không định dạng với dấu phẩy
                calculateCost();
            });

            function calculateCost() {
                const startDateVal = startDateInput.val();
                const expectedReturnDateVal = expectedReturnDateInput.val();

                // Kiểm tra xem các trường ngày đã được chọn chưa
                if (!startDateVal || !expectedReturnDateVal) {
                    setDefaults();
                    return;
                }

                const startDate = new Date(new Date(startDateVal).getTime() - 7 * 60 * 60 * 1000); // Giờ UTC
                const expectedReturnDate = new Date(new Date(expectedReturnDateVal).getTime() - 7 * 60 * 60 *
                1000); // Giờ UTC
                const rentalPrice = parseFloat(rentalPriceInput.val()) || 0; // Giá thuê theo ngày
                const additionalFees = parseFloat(additionalFeesInput.val()) || 0;
                const vatRate = parseFloat(vatFeeInput.val()) || 0;

                if (startDate && expectedReturnDate && startDate <= expectedReturnDate) {
                    const durationMs = expectedReturnDate - startDate;
                    let durationHours = durationMs / (1000 * 60 * 60);
                    const durationMinutes = (durationMs / (1000 * 60)) % 60;

                    // Nếu phút thuê > 30 phút, tính thêm 1 giờ
                    if (durationMinutes > 30) {
                        durationHours = Math.ceil(durationHours);
                    } else {
                        durationHours = Math.floor(durationHours);
                    }

                    // Tính số ngày và số giờ lẻ
                    let days = Math.floor(durationHours / 24);
                    let hours = durationHours % 24;

                    // Nếu giờ lẻ >= 8, tính thành 1 ngày
                    if (hours >= 8) {
                        days += 1;
                        hours = 0; // Đặt giờ lẻ về 0
                    }
                    // Nếu tổng thời gian thuê < 1 ngày, mặc định là 1 ngày và 0 giờ
                    if (days < 1) {
                        days = 1;
                        hours = 0;
                    }

                    // Hiển thị tổng thời gian thuê
                    totalRentalDurationInput.val(`${days} ngày${hours > 0 ? ' ' + hours + ' giờ' : ''}`);

                    // Hiển thị/ẩn ô phụ phí nếu có giờ lẻ
                    if (hours > 0) {
                        additionalFeesInput.prop('readonly', false);
                    } else {
                        additionalFeesInput.prop('readonly', true).val(0);
                    }

                    // Tính phụ phí dựa trên giờ lẻ
                    let totalCost = hours > 0 ? hours * additionalFees : 0;
                    totalCostInput.val(totalCost.toFixed(0)); // Định dạng số nguyên

                    // Tính tổng tiền: (Số ngày * Giá thuê theo ngày) + Tổng chi phí + VAT
                    const dailyCost = days * rentalPrice; // Số ngày * giá thuê theo ngày
                    const preVatAmount = dailyCost + totalCost;
                    const totalAmount = preVatAmount + (preVatAmount * vatRate / 100);
                    totalAmountInput.val(totalAmount.toFixed(0)); // Định dạng số nguyên
                } else {
                    setDefaults();
                }
            }

            // Hàm đặt giá trị mặc định khi không hợp lệ
            function setDefaults() {
                totalRentalDurationInput.val('1 ngày');
                additionalFeesInput.prop('readonly', true).val(0);
                totalCostInput.val('0');
                totalAmountInput.val('0');
            }

            // Gọi hàm tính toán khi thay đổi các trường liên quan
            startDateInput.on('input', calculateCost);
            expectedReturnDateInput.on('input', calculateCost);
            additionalFeesInput.on('input', calculateCost);
            vatFeeInput.on('input', calculateCost);
        });
    </script>
@endpush
