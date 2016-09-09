
/* ----- SUBSCRIBE NEWS ----- */

function subscribeNews() {
    let $form = $('.subscribe-form'),
        $text = $form.find('input[name="subscribe-mail"]');

    // RETURN DEFAULT VALUE FOR DESCRIPTION
    $text.on('focus', function() {
        $(this).removeAttr('data-error');
    });

    // FORM SUBMIT
    $form.on('submit', function(e) {
        e.preventDefault();

        if (validateEmail($text.val())) {
            $.ajax({
                type: 'POST',
                url: $form.attr('action'),
                dataType: 'json',
                data: {
                    'email': $text.val(),
                    '_token': $form.find('[name=\'_token\']').val()
                },

                success: function(data) {
                    let clearInput = function() {
                        $text.val('');
                    };

                    switch (data.status) {
                        case 'success':

                            popUp('Подписка на новости', data.message, clearInput);

                            break;

                        case 'error':
                            popUp('Подписка на новости', data.message, clearInput);

                            $text.attr('data-error', '');

                            break;
                    }



                    console.log('subscribe ajax success');
                },

                error: function(response) {
                    let tempMessage = '';

                    $.each(response.responseJSON, function(i, item) {
                        tempMessage += item + '\n';
                    });

                    popUp('Подписка на новости', tempMessage);

                    $text.attr('data-error', '');

                    console.log('subscribe ajax error');
                }
            });
        } else {
            popUp('Подписка на новости', 'Неправильный E-mail. Попытайтесь еще раз.');

            $text.attr('data-error', '');
        }
    });
}

/* ----- end SUBSCRIBE NEWS ----- */