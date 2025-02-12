$(document).ready(function () {
    $(".spinner-border").show();
    // $("#wrapper").hide();

    $(window).on("load", function () {
        $(".spinner").remove();
        $("#wrapper").show();

        // Hiệu ứng số chạy
        const $counters = $(".counter"); // Chọn tất cả các phần tử có class 'counter'

        // Hàm tăng giá trị của mỗi phần tử đếm
        function runCounter($counter) {
            const target = $counter.attr("data-target"); // Lấy giá trị từ data-target
            const increment = target / 100; // Tốc độ tăng
            let current = 0;

            function updateCounter() {
                current += increment;
                if (current < target) {
                    $counter.text(current + "+"); // Cập nhật giá trị
                    setTimeout(updateCounter, 20); // Thời gian chạy
                } else {
                    $counter.text(target + "+"); // Khi đạt giá trị cuối cùng
                }
            }
            updateCounter();
        }

        // Kiểm tra nếu phần tử xuất hiện trong viewport
        function checkScroll() {
            $counters.each(function () {
                const $counter = $(this);
                const rect = $counter[0].getBoundingClientRect(); // Lấy thông tin vị trí của phần tử
                if (rect.top < $(window).height() && rect.bottom >= 0) {
                    if (!$counter.hasClass("started")) {
                        $counter.addClass("started"); // Đánh dấu là đã bắt đầu chạy
                        runCounter($counter); // Chạy hiệu ứng đếm
                    }
                }
            });
        }

        // Kiểm tra ngay khi tải trang
        checkScroll();

        // Lắng nghe sự kiện scroll
        $(window).on("scroll", checkScroll);
    });

    //========================
    //========================
    //hiệu ứng đầu
    $(window).on("load", () => {
        $(".text-animation").addClass("active");
    });

    //========================
    //========================
    //========================
    //Xử lý phần dropdown

    $(".menu-info").click(function () {
        $(".user-dropdown-menu").stop().slideToggle();
        $(".menu-info").toggleClass("active");
    });

    $(document).scroll(function () {
        $(".user-dropdown-menu").slideUp();
        $(".menu-info").removeClass("active");
    });

    // Ẩn sub-menu nếu click ra ngoài user-dropdown-menu
    $(document).click(function (e) {
        // Kiểm tra xem click có phải ngoài .user-dropdown-menu hay không
        if (!$(e.target).closest(".user-dropdown-menu, .menu-info").length) {
            $(".user-dropdown-menu").slideUp();
            $(".menu-info").removeClass("active");
        }
    });

    $("section.customer-review .owl-carousel-customer").owlCarousel({
        autoplay: true,
        autoplayTimeout: 3000,
        loop: true,
        nav: false,
        dots: false,
        responsive: {
            0: {
                items: 1,
            },
            600: {
                items: 1,
            },
            1000: {
                items: 2,
            },
        },
    });

    $(".owl-carousel").owlCarousel({
        autoplay: true,
        autoplayTimeout: 3000,
        loop: false,
        nav: false,
        dots: false,
        responsive: {
            0: {
                items: 1,
            },
            600: {
                items: 2,
            },
            1000: {
                items: 5,
            },
        },
    });

    //========================
    //========================
    //========================
    //Xử lý phần btn-top

    $(window).scroll(() => {
        if ($(window).scrollTop() >= 200) {
            $("#btn-top").fadeIn();
        }

        if ($(window).scrollTop() < 200) {
            $("#btn-top").fadeOut();
        }
    });
    $("#btn-top").click(function () {
        $(window).scrollTop(0);
    });

    //========================
    //========================
    //========================
    //Xử lý phần active profile
    // $(".profile .list-group .list-group-item").click(function (e) {
    //     e.preventDefault();
    //     $(".profile .list-group .list-group-item").removeClass("active");

    //     $(this).addClass("active");
    // });

     //========================
    //========================
    //========================
    //Xử lý phần upload ảnh
});
