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

    $('.sent-form-fast').on('click',function (e) {
        e.preventDefault();

    })


});
