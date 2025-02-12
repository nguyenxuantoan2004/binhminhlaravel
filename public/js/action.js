$(document).ready(function () {
    // var nameModuleVietNamese = "chi nhánh";
    // var nameModule = "branch";

    //========================================
    //ajax khôi phục
    //========================================

    $(".btn-restore").click(function (e) {
        e.preventDefault();
        // alert("ok");
        var id = $(this).data("id");
        var name = $(this).data("name");
        var nameModuleVietNamese = $(this).data("name-module-vietnamese");
        var nameModule = $(this).data("name-module");
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        Swal.fire({
            title: `Bạn chắc chắn muốn khôi phục ${nameModuleVietNamese} "${name}" này không?`,
            icon: "question",
            confirmButtonText: "Chắc chắn",
            cancelButtonText: "Trở Lại",
            showCancelButton: true,
            showCloseButton: true,
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "PUT",
                    url: `/admin/${nameModule}/restore/` + id,
                    data: {},
                    dataType: "json",
                    success: function (data) {
                        if (data.code == "success") {
                            Swal.fire({
                                title: data.status,
                                icon: "success",
                            }).then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function (xhr) {
                        console.error("Lỗi:", xhr);
                    },
                });
            }
        });
    });

    //========================================
    //ajax xóa vĩnh viễn
    //========================================

    $(".btn-force-delete").click(function (e) {
        e.preventDefault();
        var id = $(this).data("id");
        var name = $(this).data("name");
        var nameModuleVietNamese = $(this).data("name-module-vietnamese");
        var nameModule = $(this).data("name-module");
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        Swal.fire({
            title: `Bạn chắc chắn muốn xóa vĩnh viễn ${nameModuleVietNamese} "${name}" này không?`,
            icon: "question",
            confirmButtonText: "Chắc chắn",
            cancelButtonText: "Trở Lại",
            showCancelButton: true,
            showCloseButton: true,
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "PUT",
                    url: `/admin/${nameModule}/force-delete/` + id,
                    data: {},
                    dataType: "json",
                    success: function (data) {
                        if (data.code == "success") {
                            Swal.fire({
                                title: data.status,
                                icon: "success",
                                // draggable: true,
                            }).then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function (xhr) {
                        console.error("Lỗi:", xhr);
                    },
                });
            }
        });
    });

    //========================================
    //ajax xóa tạm thời
    //========================================

    $(".btn-delete").click(function (e) {
        e.preventDefault();
        var id = $(this).data("id");
        var name = $(this).data("name");
        var nameModuleVietNamese = $(this).data("name-module-vietnamese");
        var nameModule = $(this).data("name-module");
        // console.log(nameModule);
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        Swal.fire({
            title: `Bạn chắc chắn muốn xóa ${nameModuleVietNamese} "${name}" này không?`,
            icon: "question",
            confirmButtonText: "Chắc chắn",
            cancelButtonText: "Trở Lại",
            showCancelButton: true,
            showCloseButton: true,
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "DELETE",
                    url: `/admin/${nameModule}/` + id,
                    data: {},
                    dataType: "json",
                    success: function (data) {
                        if (data.code == "success") {
                            Swal.fire({
                                title: data.status,
                                icon: "success",
                                // draggable: true,
                            }).then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function (xhr) {
                        console.error("Lỗi:", xhr);
                    },
                });
            }
        });
    });

    //========================================
    //ajax action
    //========================================

    $("#action-form").submit(function (e) {
        e.preventDefault();
        var action = $(".action").val();
        var nameModuleVietNamese = $(this).data("name-module-vietnamese");
        var nameModule = $(this).data("name-module");
        let selecteds = [];
        $('input[name="selected"]:checked').each(function () {
            selecteds.push($(this).val());
        });

        if (action != 0 && selecteds.length > 0) {
            Swal.fire({
                title: `Bạn chắc chắn muốn thực hiện hành đồng hàng loạt này không?`,
                icon: "question",
                confirmButtonText: "Chắc chắn",
                cancelButtonText: "Trở Lại",
                showCancelButton: true,
                showCloseButton: true,
            }).then((result) => {
                let data = {
                    action: action,
                    selecteds: selecteds,
                };

                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                });

                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: `/admin/${nameModule}/update-action`,
                        data: data,
                        dataType: "json",
                        success: function (responsive) {
                            if (responsive.code == "success") {
                                Swal.fire({
                                    title: responsive.status,
                                    icon: "success",
                                    // draggable: true,
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        },
                        error: function (xhr) {
                            console.log(xhr.status); // In ra mã trạng thái HTTP
                            console.log(xhr.responseText); // In ra phản hồi từ server
                            if (xhr.status == 419) {
                                alert(
                                    "Lỗi CSRF: Vui lòng kiểm tra CSRF token!"
                                );
                            } else {
                                alert("Có lỗi xảy ra: " + xhr.statusText);
                            }
                        },
                    });
                }
            });
        } else {
            if (action == 0) {
                Swal.fire({
                    title: `Bạn chưa chọn chức năng muốn thực hiện?`,
                    icon: "warning",
                });
            } else if (selectedCategories.length <= 0) {
                Swal.fire({
                    title: `Bạn chưa chọn ${nameModuleVietNamese}?`,
                    icon: "warning",
                });
            }
        }
    });
});
