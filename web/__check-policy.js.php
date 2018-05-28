$(function() {
    var policyStatuses = {
        PENDING: "<?= _("полис настоящий, но еще не действителен (дата начала покрытия еще не наступила)") ?>",
        VALID: "<?= _("полис настоящий, действует") ?>",
        EXPIRED: "<?= _("полис настоящий, но уже не действителен по сроку действия") ?>",
        TERMINATED: "<?= _("полис настоящий, договор был расторгнут по инициативе страхователя") ?>",
        PAID: "<?= _("полис настоящий, но уже не действителен, т.к. по нему уже произведена выплата") ?>",
        null: "<?= _("полис не найден в системе") ?>"
    };

    /* отправляем форму расчета страховки */
    $(".check-policy__form").submit(function(e) {
        e.preventDefault();

        var $input = $(this).find('input');
        var $button = $(this).find('button');

        $input.addClass("loading").prop('readonly', true);
        $button.text("<?= _("Проверяем...") ?>").prop('disabled', true);

        if($input.val().length != 12) {
            $(".check-policy__result").text("<?= _("Неверно введен номер полиса") ?>");

            $input.removeClass("loading").prop('readonly', false);
            $button.text("<?= _("Проверить") ?>").prop('disabled', false);

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
            $button.text("<?= _("Проверить") ?>").prop('disabled', false);

        })
        .fail(function(jqXHR, textStatus) {
//            document.location.href = '/500.html';
        });
    });

});

