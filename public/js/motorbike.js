$(document).ready(function () {
    $("#images").on("change", function (event) {
        const preview = $("#preview");
        const maxFile = 5;
        const files = event.target.files;
        
        preview.empty(); // Xóa nội dung cũ
        var i = 0;
        Array.from(event.target.files).forEach((file) => {
            const img = $("<img>").attr("src", URL.createObjectURL(file));
            img.attr("pin", i);
            if (i == 0) {
                img.addClass("active");
                // img.css({
                //     border: "2px solid red",
                //     transform: "scale(1.1)",
                //     transition: "all 0.3s ease", // Thêm hiệu ứng chuyển đổi mượt mà
                //     opacity: 0.9,
                // });
                $("#pin").val(1);
            }
            
            img.css({
                width: "200px",
                margin: "10px",
            });
            preview.append(img);
            i++;
        });
    });

    $("#preview").on("click", "img", function () {

        $("img").css({
            border: "none",
            transform: "scale(1)",
            opacity: 1,
        });
        
        $(this).css({
            border: "2px solid red",
            transform: "scale(1.1)",
            transition: "all 0.3s ease", // Thêm hiệu ứng chuyển đổi mượt mà
            opacity: 0.9,
        });
        var pin = $(this).attr("pin");
        $("#pin").val(pin);
    });
});
