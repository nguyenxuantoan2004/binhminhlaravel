$(document).ready(function () {
    // Hàm chuyển đổi thời gian sang định dạng datetime-local và đúng múi giờ địa phương
    function formatToLocalDatetime(date) {
        const offset = date.getTimezoneOffset() * 60000; // Lấy độ lệch múi giờ (ms)
        const localDate = new Date(date.getTime() - offset); // Trừ đi độ lệch để có giờ địa phương
        return localDate.toISOString().slice(0, 16); // Định dạng cho datetime-local
    }

    // Hàm cập nhật ngày trả xe dự kiến
    function updateEndDate() {
        const rentalDays = parseInt($("#rentalDays").val()) || 1; // Lấy số ngày thuê
        const startDate = new Date($("#startDate").val()); // Lấy ngày nhận xe

        if (!isNaN(startDate.getTime())) {
            // Kiểm tra giá trị ngày nhận xe
            const endDate = new Date(startDate);
            endDate.setDate(startDate.getDate() + rentalDays); // Cộng thêm số ngày thuê
            // Giữ nguyên giờ/phút/giây của ngày nhận xe
            endDate.setHours(startDate.getHours());
            endDate.setMinutes(startDate.getMinutes());
            endDate.setSeconds(startDate.getSeconds());
            $("#endDate").val(formatToLocalDatetime(endDate)); // Cập nhật ngày trả xe đúng múi giờ địa phương
        }
    }

    // Hàm cập nhật tổng tiền dự kiến
    function updateTotalPrice() {
        const rentalDays = parseInt($("#rentalDays").val()) || 1; // Lấy số ngày thuê
        const dailyPrice =
            parseFloat(
                $("#modal-price")
                    .text()
                    .replace(/[^0-9.-]+/g, "")
            ) || 0; // Lấy giá thuê hàng ngày
        const totalPrice = dailyPrice * rentalDays; // Tính tổng tiền
        $("#totalPrice").val(
            totalPrice.toLocaleString().replace(/\./g, ",") + "đ"
        ); // Định dạng và cập nhật tổng tiền
    }

    // Khi thay đổi số ngày thuê hoặc ngày nhận xe, cập nhật ngày trả xe và tổng tiền
    $("#rentalDays, #startDate").on("input change", function () {
        updateEndDate();
        updateTotalPrice();
    });

    // Hiển thị modal với dữ liệu từ button
    $(".btn-show-modal").on("click", function () {
        const name = $(this).data("name");
        const price = $(this).data("price");
        const license = $(this).data("license");
        const image = $(this).data("image");
        const motorbikeId = $(this).data("motorbikeId");

        $("#modal-name").text(name);
        $("#modal-price").text(price);
        $("#modal-license").text(license);
        $("#modal-image").attr("src", image);
        $("#modal-motorbike-id").val(motorbikeId);

        // Reset dữ liệu đầu vào
        $("#rentalDays").val(1);
        $("#startDate").val("");
        $("#endDate").val("");
        $("#totalPrice").val(price);
    });
    // ===========================
    // Xử lý sự kiện thay đổi
    // ===========================

    $('input[name="pickupOption"]').change(function () {
        if (this.value === "store") {
            $(".branch-select").show();
            $(".delivery-input").hide();
            $("#deliveryInfo").prop("required", false); // Loại bỏ thuộc tính required
            $(".branch-select").prop("required", true); // Loại bỏ thuộc tính required
            $("#branch").prop("selectedIndex", 0);
        } else if (this.value === "delivery") {
            $(".branch-select").hide();
            // $(".delivery-input")
            $(".delivery-input").show();
            $("#deliveryInfo").prop("required", true); // Thêm thuộc tính required
            $(".branch-select").prop("required", false); // Thêm thuộc tính required
        }
    });

    // Mặc định chọn nhận xe tại cửa hàng
    $("#pickupStore").prop("checked", true);
    $(".branch-select").show();
    $(".delivery-input").hide();

    // ===========================
    // Xử lý thông báo khi chưa đăng nhập
    // ===========================
    $(".btn-show-message").on("click", function () {
        Swal.fire({
            title: `Vui lòng đăng nhập để đặt xe!`,
            icon: "warning",
            confirmButtonText: "Đăng nhập",
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "/dang-nhap";
            }
        });
    });

    // ===========================
    // Xử lý gửi form đặt xe
    // ===========================
    $("#order-form").on("submit", function (event) {
        event.preventDefault(); // Ngăn chặn form reload trang

        const motorbikeReceiptMethod = $(
            'input[name="pickupOption"]:checked'
        ).val();
        if (motorbikeReceiptMethod === "delivery") {
            $("#branch").val(""); // Xóa giá trị chọn cửa hàng nếu chọn giao xe
        } else {
            $("#deliveryInfo").val(""); // Xóa thông tin giao xe nếu chọn nhận xe tại cửa hàng
        }
        const motorbikePickupLocation = $("#deliveryInfo").val() || "";
        const rentalDays = parseInt($("#rentalDays").val()) || 1;
        const totalPrice =
            parseFloat(
                $("#totalPrice")
                    .val()
                    .replace(/[^0-9.-]+/g, "")
            ) || 0;

        const data = {
            start_date: $("#startDate").val(),
            expected_return_date: $("#endDate").val(),
            total_rental_duration: rentalDays,
            total_amount: totalPrice,
            motorbike_receipt_method: motorbikeReceiptMethod,
            motorbike_pickup_location: motorbikePickupLocation,
            motorbike_id: $("#modal-motorbike-id").val(),
            branch_id: $("#branch").val() || 1,
            phone: $("#modal-tel").val() || 1,
            email: $("#modal-email").val() || 1,
        };

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        $.ajax({
            type: "POST",
            url: "/dat-xe-ngay",
            data: data,
            dataType: "json",
            success: function (response) {
                console.log(response);
                if(response.code == "success"){
                    Swal.fire({
                        title: response.status,
                        icon: "success",
                        showCancelButton: true,
                        confirmButtonText: "OK",
                        cancelButtonText: "Trở lại",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload();
                        }
                    });
                }
                else{
                    Swal.fire({
                        title: response.status,
                        icon: "error",
                        confirmButtonText: "OK",
                    });
                }
            },
            error: function (xhr) {
                console.error("Lỗi:", xhr);
            },
        });

        // Gửi dữ liệu qua AJAX hoặc xử lý tiếp tại đây
    });
});
