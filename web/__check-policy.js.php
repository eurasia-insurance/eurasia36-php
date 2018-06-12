$(function() {
    var policyStatuses = {
        PENDING: "<?= _("Полис настоящий, начнёт действовать %s") ?>",
        VALID: "<?= _("Полис настоящий, действует до&nbsp;%s") ?>",
        EXPIRED: "<?= _("Полис настоящий, закончил действие %s") ?>",
        TERMINATED: "<?= _("Полис настоящий, не&nbsp;действует, потому что&nbsp;страхователь расторгнул договор") ?>",
        PAID: "<?= _("Полис настоящий, не&nbsp;действует, потому что&nbsp;произведена выплата") ?>",
        null: "<?= _("Полис не&nbsp;найден. Убедитесь, что&nbsp;ввели номер без&nbsp;ошибок, или&nbsp;свяжитесь с&nbsp;нами") ?>"
    };

    var month = {
        '01': '<?= _("января") ?>',
        '02': '<?= _("февраля") ?>',
        '03': '<?= _("марта") ?>',
        '04': '<?= _("апреля") ?>',
        '05': '<?= _("мая") ?>',
        '06': '<?= _("июня") ?>',
        '07': '<?= _("июля") ?>',
        '08': '<?= _("августа") ?>',
        '09': '<?= _("сентября") ?>',
        '10': '<?= _("октября") ?>',
        '11': '<?= _("ноября") ?>',
        '12': '<?= _("декабря") ?>'
    };

    /* отправляем форму расчета страховки */
    $(".check-policy__form").submit(function(e) {
        e.preventDefault();

        var $container = $(".check-policy");
        var $input = $(this).find('input');
        var $button = $(this).find('button');

        $container.removeClass("check-policy_green check-policy_yellow check-policy_red");
        $(".check-policy__result").text('');

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

            if (data.policyStatus !== undefined) {

                var dateStr = '';

                if(data.policyStatus == 'PENDING') {
                    var dateRaw = data.validFrom.split("-");

                    dateStr = parseInt(dateRaw[2]) + " " + month[dateRaw[1]] + " " + dateRaw[0];
                } else if (data.policyStatus == 'VALID' || data.policyStatus == 'EXPIRED') {
                    var dateRaw = data.validTill.split("-");

                    dateStr = parseInt(dateRaw[2]) + " " + month[dateRaw[1]] + " " + dateRaw[0];
                }

                var message = policyStatuses[data.policyStatus].replace("%s", dateStr);

                if(data.policyStatus == 'PENDING' || data.policyStatus == 'VALID') {
                    $container.addClass('check-policy_green');
                } else if(data.policyStatus == 'EXPIRED' || data.policyStatus == 'TERMINATED' || data.policyStatus == 'PAID'  ) {
                    $container.addClass('check-policy_yellow');
                } else {
                    $container.addClass('check-policy_red');
                }

                $(".check-policy__result").html(message);
            } else if( data.code == 500 ) {
                document.location.href = '/500.html';
            }

            $input.removeClass("loading").prop('readonly', false);
            $button.text("<?= _("Проверить") ?>").prop('disabled', false);

        })
        .fail(function(jqXHR, textStatus) {
            document.location.href = '/500.html';
        });
    });

});

