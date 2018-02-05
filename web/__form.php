<div class="main-form" id="form-container">
    <form action="/price.php" method="post" class="form-horizontal" id="main-form">
        <div class="form-group widget-two-col">
            <label for="inputInn" class="col-sm-3 control-label"><strong><?= _("Ваш ИИН") ?></strong></label>
            <div class="col-sm-9">
                <input type="text" name="drivers[0][idNumber]" class="form-control inn" id="inputInn" placeholder="" maxlength="12" />
            </div>
        </div>
        <div class="form-group widget-two-col">
            <label class="col-sm-3 control-label toggle-label"><?= _("за рулем") ?></label>
            <div class="col-sm-9 radio-toggles">
                <div class="radio">
                    <label>
                        <input type="radio" name="drivers[0][expirienceClass]" id="optionsRadios1" value="LESS2" />
                        <?= _("менее 2 лет") ?>
                    </label>
                </div><!--
                                    --><div class="radio active">
                    <label>
                        <input type="radio" name="drivers[0][expirienceClass]" id="optionsRadios2" value="MORE2" checked />
                        <?= _("2 года и больше") ?>
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-9 col-sm-offset-3">
                <div class="checkbox">
                    <input type="hidden" value="0"  name="drivers[0][privileger]" />
                    <label>
                        <input type="checkbox" value="1"  name="drivers[0][privileger]" />
                        <?= _("льготные условия для пенсионеров и инвалидов") ?>
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group widget-two-col">
            <label for="inputAuto" class="col-sm-3 control-label"><strong><?= _("Ваше авто") ?></strong></label>
            <div class="col-sm-9">
                <select class="form-control" id="inputAuto" name="vehicles[0][typeClass]">
                    <option value="CAR" selected="selected"><?= _("Легковая машина") ?></option>
                    <option value="MOTO"><?= _("Мотоцикл") ?></option>
                    <option value="CARGO"><?= _("Грузовик") ?></option>
                    <option value="TRAILER"><?= _("Прицеп") ?></option>
                    <option value="BUS16"><?= _("Автобус (до 16 пассажиров)") ?></option>
                    <option value="BUSOVR16"><?= _("Автобус (более 16 пассажиров)") ?></option>
                    <!--<option value="TRAM"><?= _("Троллейбус или трамвай") ?></option>-->
                </select>
            </div>
        </div>
        <div class="form-group widget-two-col">
            <label class="col-sm-3 control-label toggle-label"><?= _("год выпуска") ?></label>
            <div class="col-sm-9 radio-toggles">
                <div class="radio active">
                    <label>
                        <input type="radio" name="vehicles[0][ageClass]" id="optionsRadios1" value="OVER7" checked>
                        <?= sprintf(_("до %d"), date('Y') - 7) ?>
                    </label>
                </div><!--
                                    --><div class="radio">
                    <label>
                        <input type="radio" name="vehicles[0][ageClass]" id="optionsRadios2" value="UNDER7">
                        <?= sprintf(_("%d и новее"), date('Y') - 6) ?>
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group widget-two-col">
            <label for="inputReg" class="col-sm-3 control-label"><?= _("на учёте в") ?></label>
            <div class="col-sm-9" id="inputReg">
                <select class="form-control region-select" name="vehicles[0][area]">
                    <option value="GAST"><?= _("Астане") ?></option>
                    <option value="GALM" selected="selected"><?= _("Алматы") ?></option>
                    <option value="OAKM"><?= _("Акмолинской области") ?></option>
                    <option value="OALM"><?= _("Алматинской области") ?></option>
                    <option value="OAKT"><?= _("Актюбинской области") ?></option>
                    <option value="OATY"><?= _("Атырауской области") ?></option>
                    <option value="OVK"><?= _("Восточно-Казахстанской области") ?></option>
                    <option value="OZHM"><?= _("Жамбылской области") ?></option>
                    <option value="OZK"><?= _("Западно-Казахстанской области") ?></option>
                    <option value="OKGD"><?= _("Карагандинской области") ?></option>
                    <option value="OKST"><?= _("Костанайской области") ?></option>
                    <option value="OKZL"><?= _("Кызылординской области") ?></option>
                    <option value="OMNG"><?= _("Мангистауской области") ?></option>
                    <option value="OPVL"><?= _("Павлодарской области") ?></option>
                    <option value="OSK"><?= _("Северо-Казахстанской области") ?></option>
                    <option value="OUK"><?= _("Южно-Казахстанской области") ?></option>
                </select>
            </div>
            <div class="col-sm-9 col-sm-offset-3 major-city__container">
                <div class="checkbox">
                    <input type="hidden" name="vehicles[0][majorCity]" value="0" />
                    <label>
                        <input type="checkbox" name="vehicles[0][majorCity]" class="majorCity" checked="checked" value="1" />
                        <?= _("город областного значения") ?>
                    </label>
                </div>
            </div>
            <div class="form-fader"></div>
        </div>
        <div class="form-group widget-two-col widget-checkbox">
            <div class="col-sm-9 col-sm-offset-3">
                <div class="checkbox">
                    <input type="hidden" value="0" name="vehicles[0][temporaryEntry]" />
                    <label>
                        <input type="checkbox" value="1" name="vehicles[0][temporaryEntry]" class="temporary-entry" />
                        <?= _("временный въезд на территорию РК") ?>
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group widget-two-col">
            <label for="inputName" class="col-sm-3 control-label"><?= _("Ваше имя") ?></label>
            <div class="col-sm-9">
                <input type="text" class="form-control" name="name" id="inputName" required="required" value="" />
            </div>
        </div>
        <div class="form-group widget-two-col">
            <label for="inputPhone" class="col-sm-3 control-label">Телефон</label>
            <div class="col-sm-9">
                <input type="tel" class="form-control" name="phone" id="inputPhone" placeholder="+7 (___) ___-__-__" required="required" value="" />
            </div>
        </div>

        <div class="form-group main-form__btn-container">
            <div class="col-sm-offset-3 col-sm-9">
                <button type="submit" class="btn btn-blue goal-policy-calculate" disabled="disabled" id="how-much"><?= _("Рассчитать стоимость") ?></button>
            </div>
        </div>
    </form>
    <div class="more-details">
        <div class="row">
            <div class="col-sm-offset-3 col-sm-9">
                <?= _('Добавить ещё<br class="visible-xs"/> <a href="" class="btn-additional add-driver">водителя</a><span class="add-car"><span class="add-car__or"> <br class="visible-sm"/>или</span>&nbsp;<a href="" class="btn-additional add-auto">автомобиль</a></span>') ?>
            </div>
        </div>
    </div>
    <form action="policy-request.php" method="post" class="form-horizontal order-form" id="order-form">
        <input type="hidden" name="language" value="<?= $langs[$lang][2] ?>" />
        <input type="hidden" name="phone" value="" />
        <div class="form-group">
            <div class="col-sm-9 col-sm-offset-3">
                <?= _("Стоимость вашей страховки") ?>

                <strong class="price">&nbsp;</strong>
                <?php /** <span class="gray"><?= _('или в рассрочку на 3 месяца по <span class="price-post">&nbsp;</span> тенге в месяц.') ?></span> */ ?>
            </div>
        </div>

        <div class="form-group" id="payment-online-block">
            <div class="col-sm-9 col-sm-offset-3">
                <input type="checkbox" name="payment-online" value="1" id="payment-online" />
                <label for="payment-online"><?= _("оплатить банковской картой в интернете"); ?></label>
                <?php if(defined('PARAM_SHOW_EUBANK_BONUS') && PARAM_SHOW_EUBANK_BONUS == 1): ?>
                    <br/>
                    <small class="gray"><?= _('Бонус до 30% при оплате картой <img src="/i/eubank.png" alt="" /> Евразийского банка'); ?></small>
                <?php endif; ?>
            </div>
        </div>
        <div class="form-group" id="email-block" style="display: none;">
            <label for="inputEmail" class="col-sm-3 control-label toggle-label">Эл. почта</label>
            <div class="col-sm-9">
                <input type="email" class="form-control" name="email" id="inputEmail" value="" />
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9">
                <?php if(isset($_SESSION['policyRequest']) && $_SESSION['policyRequest'] > 5): ?>
                    <div class="g-recaptcha" data-sitekey="6LdQBBsUAAAAADlPZcaxD-VkvvhC6-3K6SjVQ1_a"></div>
                <?php endif; ?>
                <button type="submit" class="btn btn-blue goal-policy-request"><?= _("Заказать полис") ?></button>
                <div>
                    <strong id="result-msg" style="display: none"><?= _("Спасибо. Мы получили вашу заявку") ?></strong>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9 order-hint">
                <?= _("Перезвоним в течение 10 минут. Спросите менеджера о действующих скидках.") ?>
            </div>
            <div class="col-sm-offset-3 col-sm-9 one-more-form">
                <a href="/" id="oneMorePolicy"><?= _("Заполнить еще одну заявку") ?></a>
            </div>
        </div>
    </form>
</div>


<div class="main-form__additional-driver" id="driverTemplate" style="display: none">
    <a href="" class="close-form">&nbsp;</a>
    <div class="form-group">
        <div class="col-xs-12">
            <h3 class="driver-num"><?= _("Ещё один водитель") ?></h3>
        </div>
    </div>
    <div class="form-group widget-two-col">
        <label for="inputInn" class="col-sm-3 control-label"><strong><?= _("ИИН") ?></strong></label>
        <div class="col-sm-9">
            <input type="text" class="form-control inn" name="drivers[][idNumber]" id="inputInn" placeholder="" maxlength="12">
        </div>
    </div>
    <div class="form-group widget-two-col">
        <label class="col-sm-3 control-label toggle-label"><?= _("за рулем") ?></label>
        <div class="col-sm-9 radio-toggles">
            <div class="radio">
                <label>
                    <input type="radio" name="drivers[][expirienceClass]" value="LESS2" />
                    <?= _("менее 2 лет") ?>
                </label>
            </div><!--
                    --><div class="radio active">
                <label>
                    <input type="radio" name="drivers[][expirienceClass]" value="MORE2" checked />
                    <?= _("2 года и больше") ?>
                </label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-9 col-sm-offset-3">
            <div class="checkbox">
                <input type="hidden" value="0"  name="drivers[][privileger]" />
                <label>
                    <input type="checkbox" value="1"  name="drivers[][privileger]" />
                    <?= _("льготные условия для пенсионеров и инвалидов") ?>
                </label>
            </div>
        </div>
    </div>
</div>

<div class="main-form__additional-auto" id="autoTemplate" style="display: none">
    <a href="" class="close-form">&nbsp;</a>
    <div class="form-group">
        <div class="col-xs-12">
            <h3 class="driver-num"><?= _("Ещё один автомобиль") ?></h3>
        </div>
    </div>
    <div class="form-group widget-two-col">
        <label for="inputAuto" class="col-sm-3 control-label"><strong><?= _("Ваше авто") ?></strong></label>
        <div class="col-sm-9">
            <select class="form-control" id="inputAuto" name="vehicles[][typeClass]">
                <option value="CAR" selected="selected"><?= _("Легковая машина") ?></option>
                <option value="MOTO"><?= _("Мотоцикл") ?></option>
                <option value="CARGO"><?= _("Грузовик") ?></option>
                <option value="TRAILER"><?= _("Прицеп") ?></option>
                <option value="BUS16"><?= _("Автобус (до 16 пассажиров)") ?></option>
                <option value="BUSOVR16"><?= _("Автобус (более 16 пассажиров)") ?></option>
                <!--<option value="TRAM"><?= _("Троллейбус или трамвай") ?></option>-->
            </select>
        </div>
    </div>
    <div class="form-group widget-two-col">
        <label class="col-sm-3 control-label toggle-label"><?= _("год выпуска") ?></label>
        <div class="col-sm-9 radio-toggles">
            <div class="radio active">
                <label>
                    <input type="radio" value="OVER7" name="vehicles[][ageClass]" checked />
                    до <?= date('Y') - 7 ?>
                </label>
            </div><!--
                    --><div class="radio">
                <label>
                    <input type="radio" value="UNDER7" name="vehicles[][ageClass]" />
                    <?= date('Y') - 6 ?> и новее
                </label>
            </div>
        </div>
    </div>
    <div class="form-group widget-two-col">
        <label for="inputReg" class="col-sm-3 control-label"><?= _("на учёте в") ?></label>
        <div class="col-sm-9" id="inputReg">
            <select class="form-control region-select" name="vehicles[][area]">
                <option value="GAST"><?= _("Астане") ?></option>
                <option value="GALM" selected="selected"><?= _("Алматы") ?></option>
                <option value="OAKM"><?= _("Акмолинской области") ?></option>
                <option value="OALM"><?= _("Алматинской области") ?></option>
                <option value="OAKT"><?= _("Актюбинской области") ?></option>
                <option value="OATY"><?= _("Атырауской области") ?></option>
                <option value="OVK"><?= _("Восточно-Казахстанской области") ?></option>
                <option value="OZHM"><?= _("Жамбылской области") ?></option>
                <option value="OZK"><?= _("Западно-Казахстанской области") ?></option>
                <option value="OKGD"><?= _("Карагандинской области") ?></option>
                <option value="OKST"><?= _("Костанайской области") ?></option>
                <option value="OKZL"><?= _("Кызылординской области") ?></option>
                <option value="OMNG"><?= _("Мангистауской области") ?></option>
                <option value="OPVL"><?= _("Павлодарской области") ?></option>
                <option value="OSK"><?= _("Северо-Казахстанской области") ?></option>
                <option value="OUK"><?= _("Южно-Казахстанской области") ?></option>
            </select>
        </div>
        <div class="col-sm-9 col-sm-offset-3 major-city__container">
            <div class="checkbox">
                <input type="hidden" name="vehicles[][majorCity]" value="0" />
                <label>
                    <input type="checkbox" name="vehicles[][majorCity]" class="majorCity" value="1" checked="checked" />
                    <?= _("город областного значения") ?>
                </label>
            </div>
        </div>
        <div class="form-fader"></div>
    </div>
    <div class="form-group widget-two-col widget-checkbox">
        <div class="col-sm-9 col-sm-offset-3">
            <div class="checkbox">
                <input type="hidden" value="0" name="vehicles[][temporaryEntry]" />
                <label>
                    <input type="checkbox" value="1" name="vehicles[][temporaryEntry]" class="temporary-entry" />
                    <?= _("временный въезд на территорию РК") ?>
                </label>
            </div>
        </div>
    </div>
</div>