$(document).ready(function () {
    $("#sidebar-menu li .menu-title i").click(function () {

        $(this).find(".sub-menu").slideToggle();
        // $(this).closest("li").toggleClass("active");
        $(this).toggleClass("bi-chevron-right bi-chevron-down");

        // Xóa lớp "active" khỏi tất cả menu khác
        // let selector = "#sidebar-menu li"; // Chọn tất cả các mục menu
        // let className = "active"; // Lớp cần xóa
        
        
        $(this).closest("li").find(".sub-menu").slideToggle();
        $(selector).removeClass(className);
        
    });

    // Sự kiện khi checkbox "Chọn tất cả" được bấm
    $("input[name='checkall']").click(function () {
        var checked = $(this).is(":checked"); // Lấy trạng thái của checkbox "Chọn tất cả"
        $(".table-checkall tbody input[type='checkbox']").prop(
            "checked",
            checked
        ); // Đặt trạng thái cho tất cả checkbox trong tbody
    });

    // Sự kiện khi một checkbox trong tbody được bấm
    $(".table-checkall tbody input[type='checkbox']").change(function () {
        var totalCheckbox = $(
            ".table-checkall tbody input[type='checkbox']"
        ).length; // Tổng số checkbox trong tbody
        var checkedCheckbox = $(
            ".table-checkall tbody input[type='checkbox']:checked"
        ).length; // Số checkbox được chọn

        // Cập nhật trạng thái của checkbox "Chọn tất cả"
        $("input[name='checkall']").prop(
            "checked",
            totalCheckbox === checkedCheckbox
        );
    });

   


});
