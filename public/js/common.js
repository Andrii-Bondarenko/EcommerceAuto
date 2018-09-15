$(document).ready(function () {
    $('.slider-for').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: true,
        asNavFor: '.slider-nav'
    });
    $('.slider-nav').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        asNavFor: '.slider-for',
        dots: false,
        arrows: false,
        focusOnSelect: true
    });


    $('.cd-nav-trigger').on('click', function(event){
        event.preventDefault();
        $('.bottom-nav__list').toggleClass('nav-is-visible');
        $('.cd-nav-trigger').toggleClass('open');
    });
});
