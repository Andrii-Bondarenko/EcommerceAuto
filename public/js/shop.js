$.fn.serializeObject = function()
{
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};


$(document).ready(function () {
    $('.fast-buy').on('click', function(event){
        let url = '/cart/getPopupCart';
        $.ajax({
            type: "POST",
            url: url,
            data: {id:$('.product-id').val()},
            complete: (resPopup) => {
                let popup = $(resPopup.responseText).attr('id');
                if($('#'+popup)) {
                    $('#'+popup).remove();
                }
                $('footer').after(resPopup.responseText);

                $.magnificPopup.open({
                    items: {
                        src: '#'+popup
                    },
                    showCloseBtn: true,
                });
            }
        });
    });

    $(document).on('click', '.popup-modal-dismiss', function (e) {
        $.magnificPopup.close();
    });

    $(document).on("click", ".sent-form-fast", function(e) {
        e.preventDefault();
        // get the properties and values from the form
        var data = $(".fast-buy-form").serializeObject();

        $.ajax({
            url: '/cart/sentFormFastBuy',
            type: 'POST',
            data: data,
            dataType: 'json',
            success:function(data) {
                if (data['status'] === false) {
                    if(typeof data['name']['message']!== "undefined") {
                        $('#name').parent().find('span').remove();
                        $('#name').after('<span>'+data['name']['message']+'</span>');
                    }

                    if(typeof data['phone']['message']!== "undefined" ) {
                        $('#phone').parent().find('span').remove();
                        $('#phone').after('<span>'+data['phone']['message']+'</span>');
                    }
                } else {
                    $.magnificPopup.close();
                    setTimeout(function () {
                        $.magnificPopup.open({
                            items: {
                                src: '#success-buy'
                            },
                            showCloseBtn: true,
                        });
                    },300);

                }
            }
        });
    });


    $(document).on("click", ".button-feedback", function(e) {
        e.preventDefault();
        // get the properties and values from the form
        var data = {'data': {'phone':$(".phone-block__form input").val()}};

        $.ajax({
            url: '/feedback/sentPhone',
            type: 'POST',
            data: data,
            dataType: 'json',
            success:function(data) {
                if (data['status'] === false) {
                    if(typeof data['phone']['message']!== "undefined" ) {
                        $('.feedback-mistake').remove();
                        $('#input-phone').val();
                        $('.phone-block__form').after('<div class="red-label feedback-mistake"><span>'+data['phone']['message']+'</span></div>');
                    }
                } else {
                    setTimeout(function () {
                        $('#input-phone').val('');
                        $.magnificPopup.open({
                            items: {
                                src: '#success-buy'
                            },
                            showCloseBtn: true,
                        });
                    },300);

                }
            }
        });
    });

    $(document).on('click', '.form-group input', function (e) {
       $(this).parent().find('span').remove();
    });

    $(document).on('click', '#input-phone', function (e) {
        $('.feedback-mistake').remove();
    });
});
