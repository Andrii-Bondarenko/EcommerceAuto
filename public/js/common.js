$(document).ready(function () {
    $(function(){

        function imageLoaded() {
            counter--; // уменьшаем значение счетчика
            if( counter === 0 ) {
                $('.slider-for').slick({
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: false,
                    fade: true,
                    asNavFor: '.slider-nav',

                });
                $('.slider-nav').slick({
                    slidesToShow: 4,
                    slidesToScroll: 1,
                    asNavFor: '.slider-for',
                    dots: false,
                    arrows: false,
                    focusOnSelect: true,
                    responsive: [
                        {
                            breakpoint: 1024,
                            settings: {
                                slidesToShow: 4,
                                slidesToScroll: 1,
                            }
                        },
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 3,
                                slidesToScroll: 1
                            }
                        },
                        {
                            breakpoint: 480,
                            settings: {
                                slidesToShow: 2,
                                slidesToScroll: 1
                            }
                        }
                    ]
                });
            }
        }

        var images = $('img'); // выбираем все изображения на странице
        var counter = images.length; // количество изображений на странице

        images.each(function() {
            if( this.complete ) {
                imageLoaded.call( this );
            } else {
                $(this).one('load', imageLoaded);
            }
        });

    });





    $('.cd-nav-trigger').on('click', function(event){

        event.preventDefault();
        $('.bottom-nav__list').toggleClass('nav-is-visible');
        $('.cd-nav-trigger').toggleClass('open');
    });

    $('.search__button').on('click', function(event) {
        event.preventDefault();
        if($('.search__input').val().trim()!="") {
            $('.search-form').submit();
        }
    });

    $('.search__input').keyup(function(event) {
        event.preventDefault();
        if(event.keyCode == 13) {
            if($('.search__input').val().trim()!="") {
                $('.search-form').submit();
            }
        }
    });
});
