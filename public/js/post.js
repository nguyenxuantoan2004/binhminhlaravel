$(document).ready(function () {
    $("#thumbnail").on("change", function (event) {
        const preview = $("#preview");
        const files = event.target.files;
        console.log(files);
        preview.empty(); // Xóa nội dung cũ

        Array.from(event.target.files).forEach((file) => {
            const img = $("<img>").attr("src", URL.createObjectURL(file));
            img.addClass("rounded");
            img.css("max-width", "800px");
            preview.append(img);
        });
    });
});
