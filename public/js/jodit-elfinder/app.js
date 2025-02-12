$(document).ready(function () {
    // Kiểm tra và khởi tạo editor nếu tồn tại
    if ($("#editor").length > 0) {
        var editor = new Jodit("#editor", {
            height: 600,
            extraButtons: [
                {
                    name: "Open elFinder",
                    tooltip: "Mở elFinder",
                    exec: function (editor) {
                        window.open(
                            elFinderUrl,
                            "elfinderWindow",
                            "width=800,height=400"
                        );
                    },
                },
            ],
        });
    }

    // Kiểm tra và khởi tạo editorDesc nếu tồn tại
    if ($("#editorDesc").length > 0) {
        var editorDesc = new Jodit("#editorDesc", {
            height: 100,
            toolbarSticky: false, // Không làm thanh công cụ dính
            buttons: [
                "bold", // In đậm
                "italic", // In nghiêng
                "underline", // Gạch dưới
                "ul", // Danh sách không thứ tự
                "ol", // Danh sách có thứ tự
                "link", // Chèn link
                "align", // Căn chỉnh
                "undo", // Hoàn tác
                "redo", // Làm lại
            ],
        });
    }

    // Lắng nghe thông điệp từ cửa sổ elFinder
    window.addEventListener("message", function (event) {
        if (event.data.type === "file") {
            var file = event.data.file;
            console.log(file);
            // Giải mã URL
            var decodedUrl = decodeURIComponent(file.url);
            // Chèn hình ảnh vào Jodit Editor mà không lưu ngay
            var imageUrl = decodedUrl;
            editor.selection.insertHTML(
                '<img class="url_img" src="' +
                    imageUrl +
                    '" alt="' +
                    file.name +
                    '">'
            );
        }
    });

    // $("#thumb").change(function () {
    //     // Lấy tệp mà người dùng đã chọn
    //     var file = this.files[0];

    //     // Kiểm tra xem người dùng có chọn tệp hay không
    //     if (file) {
    //         // Tạo một đối tượng FileReader để đọc nội dung của tệp
    //         const reader = new FileReader();

    //         // Định nghĩa hàm sẽ được gọi khi quá trình đọc tệp hoàn tất
    //         reader.onload = function (e) {
    //             // Gán URL dữ liệu (data URL) của tệp vào thuộc tính 'src' của thẻ img để hiển thị ảnh
    //             $("#img_thumb").attr("src", e.target.result);
    //         }

    //         // Bắt đầu đọc tệp dưới dạng URL dữ liệu
    //         reader.readAsDataURL(file);
    //     }
    // });
});
