<?php

require './api/EurasiaAPI.php';

session_start();

$apiLang = isset($_SESSION['apiLang']) ? $_SESSION['apiLang'] : 'ru';

if(!isset($_POST['drivers']) || !isset($_POST['vehicles'])) {
    header("HTTP/1.0 403 No data passed");
    die();
}


if(!isset($_SESSION['initMain']) || $_SESSION['initMain'] != 1) {
    header("HTTP/1.0 403 Go some other way");
    die();
}


if (!isset($_SESSION['priceRequestActivity'])) {

} else if (time() - $_SESSION['priceRequestActivity'] > 1800) {
    // session started more than 30 minutes ago

//    session_unset();     // unset $_SESSION variable for the run-time
    unset($_SESSION['priceRequest']);

    session_regenerate_id(true);    // change session ID for the current session and invalidate old session ID
}
$_SESSION['priceRequestActivity'] = time();  // update creation time


if(isset($_SESSION['priceRequest'])) {

    if($_SESSION['priceRequest'] >= 10) {
        header("HTTP/1.0 403 Too much requests");
        die();
    }

    $_SESSION['priceRequest'] += 1;
} else {
    $_SESSION['priceRequest'] = 1;
}



//var_dump($_POST);
//die();



// Подготовим данные водителей

$drivers = [];
$vehicles = [];

foreach($_POST['drivers'] as $key => $driver) {

//    var_dump($driver);

    /**
     * Проверим клиента по ИИНу
     */
    $clientByIin = EurasiaAPI::getPersonByIIN($driver['idNumber']);

    // предполагаем, что всё ок и ответ в json
    $clientByIin = json_decode($clientByIin, TRUE);


    if(!isset($clientByIin['error'])) { // нашли по ИИН

        if(!isset($clientByIin['ageClass'])) {

            $iin = $clientByIin['idNumber'];
            $iinDate = substr($iin, 0, 6);
            $iinYear = substr($iin, 0, 2);

            if($iinYear <= date('y')) {
                $iinDate = "20$iinDate";
            } else {
                $iinDate = "19$iinDate";
            }

            $datetime1 = date_create(date('Ymd'));
            $datetime2 = date_create($iinDate);
            $yearOld = date_diff($datetime1, $datetime2);

            if($yearOld->y < 25) {
                $clientByIin['ageClass'] = 'UNDER25';
            } else {
                $clientByIin['ageClass'] = 'OVER25';
            }

        }
        if(!isset($clientByIin['expirienceClass'])) {
            $clientByIin['expirienceClass'] = $driver['expirienceClass'];
        }
        if(!isset($clientByIin['privileger'])) {
            $clientByIin['privileger'] = (boolean)$driver['privileger'];
        }

        $drivers[] = $clientByIin;

    } else {// не нашли водителя по ИИН

    }

}


foreach($_POST['vehicles'] as $key => $vehicle) {

    $vehicle['majorCity'] = (boolean)$vehicle['majorCity'];

//    $vehicle['temporaryEntry'] = false;
    $vehicle['temporaryEntry'] = (boolean)$vehicle['temporaryEntry'];
    if($vehicle['temporaryEntry'] == 1) {
        $vehicle['area'] = 'UNDEFINED';
        $vehicle['majorCity'] = false;
    }

    $vehicles[] = $vehicle;
}


////////

$data = [
        'drivers' => $drivers,
        'vehicles' => $vehicles
    ];

//var_dump($data);
//die();

$data = json_encode($data);

//    echo '<pre>';
//    echo $data;
//    echo '</pre>';
//    die();

$url = 'policy/fetch-policy/';

$price = EurasiaAPI::request($url, $data, 'post', $apiLang);

echo $price;




$phone = isset($_POST['phone']) ? trim($_POST['phone']) : null;

// проверяем телефон
if(isset($phone)) {

    $phone = str_replace(" ", '', $phone);
    $phone = str_replace("-", '', $phone);
    $phone = str_replace("(", '', $phone);
    $phone = str_replace(")", '', $phone);

    $url = 'check/phone/'.$phone;

    $data = '{}';

    $checkPhone = EurasiaAPI::request($url, $data, 'get', $apiLang);
    $checkPhoneArr = json_decode($checkPhone, true);

    if(isset($checkPhoneArr['error'])) {
        $error = ['error' => true, 'message' => 'Неверно указан номер телефона'];
//        die(json_encode($error));
    }

} else {
    $error = ['error' => true, 'message' => 'Вы забыли указать номер телефона'];
//    die(json_encode($error));
}



if(!isset($error)) {

    $url = 'crm/send-policy-request';

    $price = json_decode($price, true);

    $data = [];

    $data['policy'] = $price;
    $data['requester']['phone'] = $phone;
    $data['requester']['name'] = trim($_POST['name']);
    $data['requester']['language'] = 'RUSSIAN';
    $data['type'] = 'UNCOMPLETE';


//    var_dump($data);
//    die();

    $data = json_encode($data);


    //var_dump($_POST);
    //die();


    $dir =  __DIR__ . '/leads';

    if(!is_dir($dir)) {
        mkdir($dir, 0777);
    }

    // пишем в файл
    file_put_contents($dir . '/' . session_id() . '.json', $data);

//    // отправляем в ЦРМку
//    $request = EurasiaAPI::request($url, $data, 'post', $apiLang);
}