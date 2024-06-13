$(document).ready(function() {
    $(".menu").click(function() {
        $(this).toggleClass('active');
        $('.header-link-wrapper-main').toggleClass('open');
        $('body').toggleClass('menu-open');
    });
    $(".wishlist-icon").click(function() {
        $(this).toggleClass('active');
    })

});

$('#file-upload').change(function() {
    var i = $(this).prev('label').clone();
    var file = $('#file-upload')[0].files[0].name;
    $(this).prev('label').text(file);
});


$('.productSlider').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    dots: true,
    infinite: false,
    centerMode: false,
    focusOnSelect: true
})
$('#productDetails').on('show.bs.modal', function(e) {
    $('.productSlider').slick("refresh")
});


function getAccordion(element_id, screen) {
    //$(window).resize(function() { location.reload(); });

    if ($(window).width() < screen) {
        var concat = '';
        obj_tabs = $(element_id + " li").toArray();
        obj_cont = $(".tab-content .tab-pane").toArray();
        // console.log(jQuery.each(obj_cont, function(n, val) {
        //     var p = val;

        // }));
        jQuery.each((obj_tabs, obj_cont), function(n, val) {
            concat += '<div id="' + n + '" class="panel panel-default">';
            concat += '<div class="panel-heading" role="tab" id="heading' + n + '">';
            concat += '<a class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse' + n + '" aria-expanded="false" aria-controls="collapse' + n + '">' + obj_tabs[n].innerText + '</a>';
            concat += '</div>';
            concat += '<div id="collapse' + n + '" class="accordion-collapse collapse" data-bs-parent="#accordion" aria-labelledby="heading' + n + '">';
            concat += '<div class="panel-body">' + obj_cont[n].innerHTML + '</div>';
            concat += '</div>';
            concat += '</div>';
        });

        $("#accordion").html(concat);
        $("#accordion").find('.panel-collapse:first').addClass("show");
        $("#accordion").find('.panel-heading a:first').attr("aria-expanded", "true");
        $(element_id).remove();
        $(".tab-content").remove();
    }
}
$(document).ready(function() {
    getAccordion("#tabs", 991);
});

$(function() {

    var $slider = $('#thumbline');
    var $progressBar = $('.progress');
    var $progressBarLabel = $('.slider__label');
    var $sliderCount = $('.slider-count');

    $slider.on('beforeChange', function(event, slick, currentSlide, nextSlide) {
        var calc = ((nextSlide) / (slick.slideCount - 1)) * 100;
        $progressBarLabel.css('width', calc + '%');

    });
    $slider.on('init', function(event, slick) {
        $sliderCount.text('/0' + slick.slideCount);
    });

    $slider.slick({
        dots: true,
        centerMode: true,
        infinite: true,
        centerPadding: '25%',
        slidesToShow: 1,
        cssEase: 'linear',
        speed: 500,
        autoplay: true,
        autoplaySpeed: 5000,
        asNavFor: '#bannerBg',
        arrows: false,
        prevArrow: $('#thumbline-prev'),
        nextArrow: $('#thumbline-next'),
        responsive: [{
                breakpoint: 1024,
                settings: {
                    centerPadding: '20%',
                },
            },
            {
                breakpoint: 700,
                settings: {
                    centerPadding: '10%',
                }
            }
        ]
    });
    $('#bannerBg').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        asNavFor: '#thumbline',
        fade: true,
        prevArrow: $('#thumbline-prev'),
        nextArrow: $('#thumbline-next'),
    });


    $('#products').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        dots: false,
        infinite: false,
        centerMode: false,
        focusOnSelect: true,

        prevArrow: $('#productPrev'),
        nextArrow: $('#productNext'),

        responsive: [{
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    // centerMode: true,

                }

            }, {
                breakpoint: 900,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    infinite: true,

                }

            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    infinite: true,

                }

            }
        ]
    });

    $('#dateContent').slick({
        slidesToShow: 7,
        slidesToScroll: 6,
        dots: false,
        centerMode: false,
        centerPadding: '10',
        focusOnSelect: true,
        prevArrow: $('#date-prev'),
        nextArrow: $('#date-next'),
        infinite: false,
        responsive: [{
            breakpoint: 1024,
            settings: {
                slidesToShow: 6,
                slidesToScroll: 4,
                // centerMode: true,
            }

        }, {
            breakpoint: 800,
            settings: {
                slidesToShow: 4,
                slidesToScroll: 3,
                infinite: false,

            }
        }, {
            breakpoint: 480,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 2,
                infinite: false,
                autoplaySpeed: 2000,
            }
        }]
    });
});
// validation

$(function() {
    $("form[name='loginForm']").validate({
        rules: {
            emailMobile: {
                required: true,
                email: true
            },
            password: {
                required: true
            }
        },
        messages: {
            emailMobile: "Please enter a valid username",
            password: "Please enter a valid password"
        },
        submitHandler: function(form) {

            var myModalEl = document.getElementById('loginSignup');
            var modal = bootstrap.Modal.getInstance(myModalEl)
            modal.hide();
        }

    });
});


