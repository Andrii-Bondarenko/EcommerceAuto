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
                console.log(data);
                if (data['status'] === false) {
                    if(data['name']['message']) {
                        $('#name').after('<span>'+data['name']['message']+'</span>');
                    }

                    if(data['phone']['message']) {
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

    $(document).on('click', '.form-group input', function (e) {
       $(this).parent().find('span').remove();
    });
});
