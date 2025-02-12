$(document).ready(function () {
    $("#images").on("change", function (event) {
        const preview = $("#preview");
        const images_new = $("#images_new");
        
        var num_images_old = $("#count_images").val();
        console.log(num_images_old);
        const files = event.target.files;
        // console.log(files);
        images_new.empty(); // Xóa nội dung cũ
        
        const p = "<p>Ảnh mới</p>";
        images_new.append(p);
        Array.from(event.target.files).forEach((file) => {
            const img = $("<img>").attr("src", URL.createObjectURL(file));
            img.attr("pin", num_images_old);
            // if (num_images_old == 0) {
            //     img.css({
            //         border: "2px solid red",
            //         transform: "scale(1.1)",
            //         transition: "all 0.3s ease", // Thêm hiệu ứng chuyển đổi mượt mà
            //         opacity: 0.9,
            //     });
            //     $("#pin").val(1);
            // }
            
            img.css({
                width: "200px",
                margin: "10px",
            });
            images_new.append(img);
            num_images_old++;
        });
    });

    $("#preview").on("click", "img", function () {
        $("#preview img").stop().removeClass('active');
        $(this).stop().addClass('active');
        var pin = $(this).attr("pin");
        $("#pin").val(pin);
    });
});
