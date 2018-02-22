var iinCache = {};

/* сюда запишем расчитанную стоимость */
var policyCost = null;


var kzRegions = {
    "OMNG": {
        "NORMAL": "Мангистауская область",
        "SHORT": "Мангистауская обл.",
        "FULL": "Мангистауская область"
    },
    "OKZL": {
        "NORMAL": "Кызылординская область",
        "SHORT": "Кызылординская обл.",
        "FULL": "Кызылординская область"
    },
    "OZK": {
        "NORMAL": "Западно-Казахстанская область",
        "SHORT": "З.-Казахстанская обл.",
        "FULL": "Западно-Казахстанская область"
    },
    "OALM": {
        "NORMAL": "Алматинская область",
        "SHORT": "Алматинская обл.",
        "FULL": "Алматинская область"
    },
    "OKST": {
        "NORMAL": "Костанайская область",
        "SHORT": "Костанайская обл.",
        "FULL": "Костанайская область"
    },
    "OPVL": {
        "NORMAL": "Павлодарская область",
        "SHORT": "Павлодарская обл.",
        "FULL": "Павлодарская область"
    },
    "OKGD": {
        "NORMAL": "Карагандинская область",
        "SHORT": "Карагандинская обл.",
        "FULL": "Карагандинская область"
    },
    "OATY": {
        "NORMAL": "Атырауская область",
        "SHORT": "Атырауская обл.",
        "FULL": "Атырауская область"
    },
    "GAST": {
        "NORMAL": "город Астана",
        "SHORT": "г. Астана",
        "FULL": "город Астана"
    },
    "OAKT": {
        "NORMAL": "Актюбинская область",
        "SHORT": "Актюбинская обл.",
        "FULL": "Актюбинская область"
    },
    "GALM": {
        "NORMAL": "город Алматы",
        "SHORT": "г. Алматы",
        "FULL": "город Алматы"
    },
    "OSK": {
        "NORMAL": "Северо-Казахстанская область",
        "SHORT": "С.-Казахстанская обл.",
        "FULL": "Северо-Казахстанская область"
    },
    "OZHM": {
        "NORMAL": "Жамбылская область",
        "SHORT": "Жамбылская обл.",
        "FULL": "Жамбылская область"
    },
    "OAKM": {
        "NORMAL": "Акмолинская область",
        "SHORT": "Акмолинская обл.",
        "FULL": "Акмолинская область"
    },
    "OVK": {
        "NORMAL": "Восточно-Казахстанская область",
        "SHORT": "В.-Казахстанская обл.",
        "FULL": "Восточно-Казахстанская область"
    },
    "OUK": {
        "NORMAL": "Южно-Казахстанская область",
        "SHORT": "Ю.-Казахстанская обл.",
        "FULL": "Южно-Казахстанская область"
    }
};


var utm = {
<?php if(isset($_GET['utm_source']) && trim($_GET['utm_source']) != ''): ?>
    "source": '<?= isset($_GET['utm_source']) ? urldecode($_GET['utm_source']) : null ?>',
    "medium": '<?= isset($_GET['utm_medium']) ? urldecode($_GET['utm_medium']) : null ?>',
    "campaign": '<?= isset($_GET['utm_campaign']) ? urldecode($_GET['utm_campaign']) : null ?>',
    "content": '<?= isset($_GET['utm_content']) ? urldecode($_GET['utm_content']) : null ?>',
    "term": '<?= isset($_GET['utm_term']) ? urldecode($_GET['utm_term']) : null ?>'
<?php elseif(isset($_GET['gclid'])): ?>
    "source": 'google',
    "medium": 'cpc',
    "campaign": 'undefined',
    "content": 'gclid-<?= isset($_GET['gclid']) ? urldecode($_GET['gclid']) : '' ?>'
<?php elseif(isset($_GET['yclid'])): ?>
    "source": 'yandex',
    "medium": 'cpc',
    "campaign": 'undefined',
    "content": 'yclid-<?= isset($_GET['yclid']) ? urldecode($_GET['yclid']) : '' ?>'
<?php endif; ?>
};

$(function() {

    checkIin(".inn");

    checkMajorityCity(".region-select");
});

var driversCountRu = {
    2: '<?= _("Второй") ?>',
    3: '<?= _("Третий") ?>',
    4: '<?= _("Четвертый") ?>',
    5: '<?= _("Пятый") ?>'
};
var initialDriver = 1;
var initialAuto = 1;


$('.main-form').on('keydown', '.inn', function (e) {
    /* Allow: backspace, delete, tab, escape, enter and . */
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
    /* Allow: Ctrl+A, Command+A */
        (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
     /* Allow: Ctrl+R, Command+R */
        (e.keyCode === 82 && (e.ctrlKey === true || e.metaKey === true)) ||
     /* Allow: home, end, left, right, down, up */
        (e.keyCode >= 35 && e.keyCode <= 40)) {
     /* let it happen, don't do anything */
        return;
    }
     /* Ensure that it is a number and stop the keypress */
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
        e.preventDefault();
    }
});

$(".main-form").on('input keyup change focusout', ".inn", function(e) {

    checkIin(this);
});


var iinNumber = false;
var iinInput = false;

function checkIin(input) {

    var $iin = $(input);

    if(iinInput != $iin.val()) {
        $iin.next(".help-block").hide();

        $("#how-much").prop('disabled', true);

        iinInput = $iin.val();
    }

    if( $iin.val() && $iin.val().length == 12 ) {/* заполнен ли иин полностью? */

        if(iinNumber != $iin.val()) {/* введен новый иин? */

            iinNumber = $iin.val();

            $iin.next(".help-block").remove();

            $iin.addClass("loading");

            $.ajax({
                method: "POST",
                url: "driver.php",
                data:  { "iin" : $iin.val() },
                dataType: "json"
            })
                .done(function( data ) {

                    iinCache[$iin.val().replace(/\s/g, '')] = data;

                    if(data.error != true) {

                        if(data.personal && data.personal.name != null) {
                            var $name = $('<div class="help-block text">' + data.personal.name + " " + data.personal.surename + ', Класс ' + data.insuranceClass.replace(/CLASS_/, '') + '</div>');

                            if($("#inputName").val() == '') {
                                $("#inputName").val(data.personal.name + " " + data.personal.surename);
                            }
                        } else {
                            var $name = $('<div class="help-block">Класс ' + data.insuranceClass.replace(/CLASS_/, '') + '</div>');
                        }

                        $("#how-much").prop('disabled', false);
                    } else {

                        $("#how-much").prop('disabled', true);

                        if(data.code == 500) {
                            var $name = $('<div class="help-block text">' + '<span class="text-danger"><?= _("К сожалению, произошла ошибка. Пожалуйста, загляните чуть позже или позвоните нас по телефонам 8&nbsp;800-080-00-99 или 5678") ?></span>' + '</div>');
                        } else {
                            var $name = $('<div class="help-block text">' + '<span class="text-danger"><?= _("Неверно введен ИИН. Пожалуйста, проверьте еще раз.") ?></span>' + '</div>');
                        }

                    }

                    $iin.removeClass("loading");

                    /* показываем имя и класс или ошибку */
                    $iin.after($name);
                    $iin.next(".help-block").fadeIn();
                })
                .fail(function(jqXHR, textStatus) {
                    document.location.href = '/500.html';
                    $iin.removeClass("loading");
                    $("#how-much").prop('disabled', false);
                    console.log(textStatus);
                });

        } else {
            $iin.next(".help-block").fadeIn();
            $("#how-much").prop('disabled', false);

            if(!$iin.next(".help-block").find('span').hasClass("text-danger")) {
                $("#how-much").prop('disabled', false);
            }
        }
    }
}


function checkCitiesFilled() {

    var itsOk = true;

    $(".сity-select:visible").each(function(i, e) {

        if($(e).val() == null || $(e).val() == '-1') {

            var target = $(e);
            if( target.length ) {
                $('html, body').stop().animate({
                    scrollTop: (target.offset().top - 10)
                }, 700, function() {
                    $(e)
                        .focus()
                        .addClass('animated shake')
                        .one("animationend webkitAnimationEnd", function () {
                            $(this).removeClass('shake');
                        });
                });
            }

            itsOk = false;

            return false;
        }
    });

    return itsOk;
}


/* добавляем еще одно авто */
$(".add-auto").click(function(e) {
    e.preventDefault();

    ++initialAuto;

    var autoTemplate = $("#autoTemplate").clone();
    autoTemplate.attr('id', 'autoTemplate' + initialAuto);

    $(".main-form__btn-container").before(autoTemplate);

    autoTemplate.find(".driver-num").text(driversCountRu[initialAuto] + ' <?= _("автомобиль") ?>');

    $(".add-driver").hide();
    $(".add-car__or").hide();

    if(initialAuto == 5) {
        $(".more-details").hide();
    }

    autoTemplate.find("input, select").each(function() {
        name = $(this).attr('name');
        $(this).attr('name', name.replace(/\[\]/, '[' + (initialAuto - 1) + ']'));

        if($(this).hasClass("region-select")) {
            checkMajorityCity(this);
        }
    });

    $(".input-reg-number").typeWatch( options );

    autoTemplate.slideDown();
});

/* добавляем еще одного водителя */
$(".add-driver").click(function(e) {
    e.preventDefault();

    /* отключаем кнопку расчета */
    $("#how-much").prop('disabled', true);

    ++initialDriver;

    var driverTemplate = $("#driverTemplate").clone();
    driverTemplate.attr('id', 'driverTemplate' + initialDriver);

    $(".main-form__btn-container").before(driverTemplate);

    driverTemplate.find(".driver-num").text(driversCountRu[initialDriver] + ' <?= _("водитель") ?>');

    $(".add-car").hide();

    if(initialDriver == 5) {
        $(".more-details").hide();
    }

    driverTemplate.find("input").each(function() {
        name = $(this).attr('name');
        $(this).attr('name', name.replace(/\[\]/, '[' + (initialDriver - 1) + ']'));
    });

    driverTemplate.slideDown();

});


/* удаляем еще одного водителя или авто */
$(".main-form").on('click', '.close-form',  function(e) {
    e.preventDefault();

    if($(this).parent().hasClass('main-form__additional-auto')) {
        --initialAuto;

        if(initialAuto < 5) {
            $(".more-details").show();
        }

        if(initialAuto == 1) {
            $(".add-car__or").show();
            $(".add-driver").show();
        }
    } else {
        --initialDriver;

        if(initialDriver < 5) {
            $(".more-details").show();
        }

        if(initialDriver == 1) {
            $(".add-car").show();
        }
    }

    $(this).parent().slideUp().remove();

    $("#how-much").prop('disabled', false);

    /* проверяем заполненность иин полей */
    $(".main-form .inn").each(function() {

        var iinValue = $(this).val().replace(/\s/g, '');

        if(iinValue.match(/_/) || iinValue == '') {/* пусто */
            $("#how-much").prop('disabled', true);

            return false;
        } else if(iinCache.iinValue && iinCache.iinValue.error == true) {/* с ошибкой */
            $("#how-much").prop('disabled', true);

            return false;
        }
    });

});

$(".main-form").on('click', '.radio-toggles .radio', function(e) {
    $(this).parent().find(".radio.active").removeClass("active");
    $(this).addClass("active");
});

$(".main-form").on('change', '.region-select', function() {
    checkMajorityCity(this);
});

/* город областного значения для алматы и астаны всегда отмечен */
function checkMajorityCity(select) {

    $(select).parent().next().find('select').remove();

    if($(select).val().match(/^O/)) {
        $(select).parent().next().find('[type=hidden]').val(0);

        $.ajax({
            method: "POST",
            url: "city.php",
            data:  { "region" : $(select).val() },
            dataType: "json"
        }).done(function( data ) {

            if(data.error != true) {

                var citiesSelect = $('<select class="form-control сity-select"></select>');
                citiesSelect.append('<option disabled selected value="-1"><?= _('Выберите город') ?></option>');

                for (var prop in data) {
                    citiesSelect.append('<option value="'+ prop +'">'+ data[prop]['SHORT'] +'</option>');
                }

                citiesSelect.append('<option value="0"><?= _('Нет в списке') ?></option>');

                citiesSelect.change(function() {
                    if($(this).val() == '0' || $(this).val() == '-1') {
                        citiesSelect.prev('input').val(0);
                    } else {
                        citiesSelect.prev('input').val(1);
                    }
                });

                $(select).closest('div').next().append(citiesSelect);

            } else {
                document.location.href = '/500.html';
            }


        })
        .fail(function(jqXHR, textStatus) {
            document.location.href = '/500.html';
        });

    } else {
        $(select).parent().next().find('[type=hidden]').val(1);
    }
}

/* город областного значения для алматы и астаны всегда отмечен */
$(".main-form").on('change', '.majorCity', function(e) {
    if(!$(this).parents(".major-city__container").prev().find('select').val().match(/^O/)) {
        $(this).prop("checked", "checked");
    }
});

 /* временный въезд */
$(".main-form").on('change', ".temporary-entry", function() {

    var $fader = $(this).parents(".form-group").prev().find(".form-fader");

    if($(this).is(":checked")) {
        $fader.show();
        $fader.parent().addClass('form-faded');
    } else {
        $fader.hide();
        $fader.parent().removeClass('form-faded');
    }
});

/* отправляем форму расчета страховки */
$("#main-form").submit(function(e) {
    e.preventDefault();

    if(false == checkCitiesFilled()) {
        return false;
    }

    $("#how-much").prop("disabled", true).text("<?= _("Расчитываем стоимость...") ?>");


    $.ajax({
        method: "POST",
        url: $(this).attr("action"),
        data:  $(this).serialize(),
        dataType: "json"
    }).done(function( data ) {

        if(data.cost) {

            price = Math.round(data.cost) + '';
            priceByThree = Math.round(price/3) + '';

            price = price.replace(/(.)/g, function(c, i, o, a) {
                return (o == (a.length - 3) || o == (a.length - 6)) ? " " + c : c;
            });
            priceByThree = priceByThree.replace(/(.)/g, function(c, i, o, a) {
                return (o == (a.length - 3) || o == (a.length - 6)) ? " " + c : c;
            });

            $("#how-much").prop("disabled", false).text("<?= _("Рассчитать стоимость") ?>");

            $("#order-form").find(".price").text(price + " <?= _("тенге") ?>");
            $("#order-form").find(".price-post").text(priceByThree);
        } else {

            if( data.code == 500 ) {
                document.location.href = '/500.html';
            } else {
                $("#order-form").find(".price").parent().text("<?= _("Сервис временно недоступен. Но вы можете оставить заявку.") ?>")
            }
        }

        policyCost = data;

        if(data.drivers[0].personal.name && data.drivers[0].personal.surename) {
            $("#order-form #inputName").val(data.drivers[0].personal.name + " " + data.drivers[0].personal.surename);
        }

        $("#order-form").slideDown(function(){
            var target = $("#order-form");
            if( target.length ) {
                $('html, body').stop().animate({
                    scrollTop: target.offset().top
                }, 500);
            }
        });

        $("#order-form input[name=phone]").val($("#inputPhone").val());
    })
    .fail(function(jqXHR, textStatus) {
        document.location.href = '/500.html';
        console.log(textStatus);
    });
});

/* отправляем заявку на полис */
$("#order-form").submit(function(e) {
    e.preventDefault();

    $("#result-msg").hide();

    $("#order-form button").text("<?= _("Отправляем заявку...") ?>").prop("disabled", true);
    $(".more-details").slideUp();


    requester = {name: $("#inputName").val()};
    formData = $(this).serializeArray();
    $(formData).each(function(i, e) {

        if(e.value) {
            requester[e.name] = e.value;
        }
    });

    $.ajax({
        method: "POST",
        url: $(this).attr("action"),
        data: { requester: requester, utm: utm, policy: policyCost },
        dataType: "json"
    }).done(function( data ) {

        if(data.message == 'Success') {
            $("#order-form button").fadeOut(function() {
                $("#result-msg").text("<?= _("Спасибо. Мы получили вашу заявку") ?>").removeClass('text-danger').fadeIn();

                if(data.paymentLink != null) {

                    $("#result-msg").append("<br/><br/><?= _("Сейчас вы будете перенаправлены на страницу оплаты") ?>");


                    <?php if(isset($_SERVER['HTTP_REFERER'])): ?>
                    if (parent.postMessage) {
                        parent.postMessage(data.paymentLink, '<?= $_SERVER['HTTP_REFERER'] ?>');
                    }
                    <?php else: ?>
                    window.setTimeout('document.location.href="' + data.paymentLink + '"', 3000);
                    <?php endif; ?>
                }
            });

            $("#payment-online-block, #email-block").fadeOut();

            $(".one-more-form").fadeIn();
        } else {

            if(data.code == 500) {
                document.location.href = '/500.html';
            } else {

                $("#result-msg").html(data.message).addClass('text-danger').fadeIn();

                $("#order-form button").text("<?= _("Заказать полис") ?>").prop('disabled', false);
            }
        }
    })
    .fail(function(jqXHR, textStatus) {
        document.location.href = '/500.html';
        console.log(textStatus);
    });

});

/* отправляем форму обратной связи */
$("#callback-form").submit(function(e) {
    e.preventDefault();

    $form = $(this);

    $("#callback-form .form-control").addClass("loading");
    $("#callback-form button").prop("disabled", true);

    $.ajax({
        method: "POST",
        url: $(this).attr("action"),
        data: $form.serialize(),
        dataType: "json"
    }).done(function( data ) {

        $("#callback-form .form-control").removeClass("loading");

        if(data.message == 'Success') {

            $($form).replaceWith("<strong class='callback-sent'><?= _("Спасибо, мы приняли вашу заявку.") ?></strong>");

        } else {

            if(data.code == 500) {
                document.location.href = '/500.html';
            } else {
                $form.find(".help-block span").text(data.message);
                $form.find(".help-block").slideDown();
            }

            $("#callback-form button").prop("disabled", false);

        }
    })
    .fail(function(jqXHR, textStatus) {
        document.location.href = '/500.html';
        console.log(textStatus);
    });

});


$('input[type=tel]').mask('+7 (999) 999-99-99',{placeholder:"_"});

$('input[type=tel]').keydown(function(e) {

    if($(this).val().match(/\+7 \(___\) ___-__-__/) && e.key == 8) {
        return false;
    }
});

$("input:required").on("invalid", function(event) {
    $(this)
        .focus()
        .addClass('animated shake')
        .one("animationend webkitAnimationEnd", function () {
            $(this).removeClass('shake');
        });
});

$('a[href^="#"]').on('click', function(event) {
    var target = $(this.getAttribute('href'));
    if( target.length ) {
        event.preventDefault();
        $('html, body').stop().animate({
            scrollTop: target.offset().top - 45
        }, 500);
    }
});

$("#payment-online").change(function() {

    if($(this).is(":checked")) {
        $("#email-block").slideDown();
        $("#inputEmail").attr("required", true);
    } else {
        $("#email-block").slideUp();
        $("#inputEmail").attr("required", false);
    }
});


$(".main-form").on('click', ".vehicle-group-opener", function(e)
    {
    e.preventDefault();

    $(this).hide();
    $(this).closest('.reg-number-group').next('.vehicle-group').slideDown();
    });

var options = {
    callback: function (value) {

        var fillFields = function(vehicleGroup, data) {
            if(data.typeClass != null) {
                $(vehicleGroup).find(".input-auto").val(data.typeClass);
            }

            if(data.ageClass != null) {
                $(vehicleGroup).find(".age-class input").each(function () {
                    if ($(this).val() == data.ageClass) {
                        $(this).closest('label').click();
                        $(this).closest('div').click();
                    }
                });
            }

            if(data.area != null) {
                $(vehicleGroup).find(".region-select").val(data.area).change();
            }

            if(data.temporaryEntry != null) {
                $(vehicleGroup).find(".temporary-entry").attr("checked", true).change();
            }
        };

        var inputReg = $(this);
        inputReg.removeClass('animated shake');

        var regMsgs = $(this).next('.reg-msgs');
        regMsgs.html('');
        regMsgs.next('.сity-select').remove();

        var vehicleGroup = $(this).closest('.reg-number-group').next('.vehicle-group');

        if(value === '') {
            return ;
        }

        inputReg.addClass("loading");

        $.ajax({
            method: "POST",
            url: "reg-number.php",
            data:  { "regNumber" : value },
            dataType: "json"
        }).done(function( data ) {

            if(data.error != true) {

                fillFields(vehicleGroup, data);

                /* has all data */
                if(data.typeClass != null && data.ageClass != null && data.area != null/* && data.temporaryEntry != null*/) {

                    var hint = data.name + ", " + data.year + " <?= _('г.') ?>";

                    $(regMsgs).html(hint);

                    if(data.majorCity == null) {

                         var selectCity = '<br/>' + kzRegions[data.area]['FULL'];

                        /* спрашиваем город */
                        $.ajax({
                            method: "POST",
                            url: "city.php",
                            data:  { "region" : data.area },
                            dataType: "json"
                        }).done(function( data ) {

                            if(data.error != true) {

                                if($(vehicleGroup).is(':hidden')) {

                                    $(regMsgs).html( $(regMsgs).html()+ selectCity );

                                    var citiesSelect = $('<select class="form-control сity-select"></select>');
                                    citiesSelect.append('<option disabled selected value="-1"><?= _('Выберите город') ?></option>');

                                    for (var prop in data) {
                                        citiesSelect.append('<option value="' + prop + '">' + data[prop]['SHORT'] + '</option>');
                                    }

                                    citiesSelect.change(function () {

                                        $(vehicleGroup).find('.сity-select').val($(this).val()).change();
                                    });

                                    citiesSelect.append('<option value="0"><?= _('Нет в списке') ?></option>');

                                    $(inputReg).closest('div').append(citiesSelect);
                                }

                            } else {
                                document.location.href = '/500.html';
                            }


                        }).fail(function(jqXHR, textStatus) {
                            document.location.href = '/500.html';
                        });

                    }

                } else {

                    $(regMsgs).html('');

                    $(vehicleGroup).slideDown();
                }

            } else {

                if(data.code == 500) {
                    document.location.href = '/500.html';
                } else {
                    inputReg.addClass('animated shake');

                    var msg = '<small class="text-danger">' + data.message[0].message + "</small><br/>"
                        + '<a href="" class="vehicle-group-opener flink small"><?= _("Ввести данные вручную") ?></a>';

                    $(regMsgs).html(msg);
                }

            }

            inputReg.removeClass("loading");

        })
        .fail(function(jqXHR, textStatus) {
            document.location.href = '/500.html';
        });

    },
    wait: 1000,
    highlight: true,
    allowSubmit: false,
    captureLength: 6
};

$(".input-reg-number").typeWatch( options );

<?php if(isset($_SERVER['HTTP_REFERER'])): ?>
$("#oneMorePolicy").click(function(e) {
    e.preventDefault();
    return false;
});
<?php endif; ?>