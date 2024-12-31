(function ($) {
    
    "use strict";

    $("form").attr("autocomplete", "off");

    $(".scroll-top").hide();
    $(window).on("scroll", function () {
        if ($(this).scrollTop() > 300) {
            $(".scroll-top").fadeIn();
        } else {
            $(".scroll-top").fadeOut();
        }
    });
    $(".scroll-top").on("click", function () {
        $("html, body").animate(
            {
                scrollTop: 0,
            },
            700
        );
    });

    new WOW().init();

    $(".video-button").magnificPopup({
        type: "iframe",
        gallery: {
            enabled: true,
        },
    });

    $(".magnific").magnificPopup({
        type: "image",
        gallery: {
            enabled: true,
        },
    });
    
    jQuery(".mean-menu").meanmenu({
        meanScreenWidth: "991",
    });

    $('#datatable').DataTable({
        language: {
            emptyTable: "No data available in table." // Veri yok mesajı
        },
        "order": [[0, "desc"]], // İlk sütun (ID) büyükten küçüğe sıralanır
        drawCallback: function (settings) {
            if (settings.aoData.length === 0) {
                $('#datatable_wrapper > *:last-child').hide();
            } else {
                $('#datatable_wrapper > *:last-child').show();
            }
        }
    })

    tinymce.init({
        selector: '.editor',
        height: '300'
    });

    $('.select2').select2();

    $(".testimonial-carousel").owlCarousel({
        loop: true,
        autoplay: true,
        autoplayHoverPause: true,
        autoplaySpeed: 1500,
        smartSpeed: 1500,
        margin: 30,
        nav: false,
        animateIn: "fadeIn",
        animateOut: "fadeOut",
        navText: [
            "<i class='fa fa-caret-left'></i>",
            "<i class='fa fa-caret-right'></i>",
        ],
        responsive: {
            0: {
                items: 1
            },
            768: {
                items: 1,
            },
            992: {
                items: 1,
            },
        },
    });

    $('#datepicker').datepicker({
        dateFormat: 'yyyy-mm-dd',
        language: {
            today: 'Today',
            days: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
            daysShort: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            daysMin: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
            months: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
        }
    });

})(jQuery);