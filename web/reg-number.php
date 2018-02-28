<?php

require './api/EurasiaAPI.php';

session_start();

$apiLangs = [
    'ru' => ['Русский', 'ru_RU', 'RUSSIAN'],
    'kk' => ['Қазақша', 'kk_KZ', 'KAZAKH'],
    /*, 'en' => ['English', 'en_US']*/
];

$apiLang = isset($_SESSION['apiLang']) ? $_SESSION['apiLang'] : 'ru';


if(!isset($_SESSION['initMain']) || $_SESSION['initMain'] != 1) {
    header("HTTP/1.0 403 Go some other way");
    die();
}


if (!isset($_SESSION['regRequestActivity'])) {

} else if (time() - $_SESSION['regRequestActivity'] > 1800) {
    // session started more than 30 minutes ago

//    session_unset();     // unset $_SESSION variable for the run-time
    unset($_SESSION['regRequest']);

    session_regenerate_id(true);    // change session ID for the current session and invalidate old session ID
}
$_SESSION['iinRequestActivity'] = time();  // update creation time


if(isset($_SESSION['regRequest'])) {

    if($_SESSION['regRequest'] >= 20) {
        header("HTTP/1.0 403 Too much requests");
        die();
    }

    $_SESSION['regRequest'] += 1;
} else {
    $_SESSION['regRequest'] = 1;
}



$url = 'insurance/policy/fetch-vehicle';

$data = array('regNumber' => $_POST['regNumber']);
$data = json_encode($data);


$request = EurasiaAPI::request($url, $data, 'post', $apiLang);

echo $request;
