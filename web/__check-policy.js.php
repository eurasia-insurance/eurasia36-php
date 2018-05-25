$(function() {
    var policyStatuses = {
        PENDING: "полис настоящий, но еще не действителен (дата начала покрытия еще не наступила)",
        VALID: "полис настоящий, действует",
        EXPIRED: "полис настоящий, но уже не действителен по сроку действия",
        TERMINATED: "полис настоящий, договор был расторгнут по инициативе страхователя",
        PAID: "полис настоящий, но уже не действителен, т.к. по нему уже произведена выплата",
        null: "полис не найден в системе"
    };

    /* отправляем форму расчета страховки */
    $(".check-policy__form").submit(function(e) {
        e.preventDefault();

        var $input = $(this).find('input');
        var $button = $(this).find('button');

        $input.addClass("loading").prop('readonly', true);
        $button.text("Проверяем...").prop('disabled', true);

        if($input.val().length != 12) {
            $(".check-policy__result").text("Неверно введен номер полиса");

            return false;
        }

        $.ajax({
            method: "POST",
            url: $(this).attr("action"),
            data:  $(this).serialize(),
            dataType: "json"
        }).done(function( data ) {

            if (data.policyStatus) {

                console.log(data);

                $(".check-policy__result").text(policyStatuses[data.policyStatus]);
            } else if( data.code == 500 ) {
//                document.location.href = '/500.html';
            }

            $input.removeClass("loading").prop('readonly', false);
            $button.text("Проверить").prop('disabled', false);

        })
        .fail(function(jqXHR, textStatus) {
//            document.location.href = '/500.html';
        });
    });

});

