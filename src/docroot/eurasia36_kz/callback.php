<?php

require './api/EurasiaAPI.php';

session_start();

$apiLang = isset($_SESSION['apiLang']) ? $_SESSION['apiLang'] : 'ru';


if(!isset($_POST['requester'])) {
    header("HTTP/1.0 403 No data passed");
    die();
}


if(!isset($_SESSION['initMain']) || $_SESSION['initMain'] != 1) {
    header("HTTP/1.0 403 Go some other way");
    die();
}


if (!isset($_SESSION['callbackRequestActivity'])) {

} else if (time() - $_SESSION['callbackRequestActivity'] > 1800) {
    // session started more than 30 minutes ago

//    session_unset();     // unset $_SESSION variable for the run-time
    unset($_SESSION['callbackRequest']);

    session_regenerate_id(true);    // change session ID for the current session and invalidate old session ID

}
$_SESSION['callbackRequestActivity'] = time();  // update creation time


if(isset($_SESSION['callbackRequest'])) {

    if($_SESSION['callbackRequest'] >= 5) {
        header("HTTP/1.0 403 Too much requests");
        die();
    }

    $_SESSION['callbackRequest'] += 1;
} else {
    $_SESSION['callbackRequest'] = 1;
}


//
//echo json_encode($_POST);
//var_dump($_POST);
//die();

$phone = isset($_POST['requester']['phone']) ? trim($_POST['requester']['phone']) : null;
$email = isset($_POST['requester']['email']) ? trim($_POST['requester']['email']) : null;

// проверяем телефон
if(isset($phone)) {

    $phone = str_replace(" ", '', $phone);
    $phone = str_replace("-", '', $phone);
    $phone = str_replace("(", '', $phone);
    $phone = str_replace(")", '', $phone);

    $url = 'insurance/check/phone/'.$phone;

    $data = '{}';

    $checkPhone = EurasiaAPI::request($url, $data, 'get', $apiLang);
    $checkPhoneArr = json_decode($checkPhone, true);

    if(isset($checkPhoneArr['error'])) {

        $msg = json_decode($checkPhoneArr['message'], true);

        $error = ['error' => true, 'message' => $msg[0]['message']];

        die(json_encode($error));
    }

} else {
    $error = ['error' => true, 'message' => 'Вы забыли указать номер телефона'];
    die(json_encode($error));
}


// проверяем мыло, если его указали
if(isset($email)) {

    $url = 'insurance/check/email/'.$email;

    $data = '{}';

    $checkEmail = EurasiaAPI::request($url, $data, 'get');
    $checkEmailArr = json_decode($checkEmail, true);

    if(isset($checkEmailArr['error'])) {
        $error = ['error' => true, 'message' => 'Неверно указана эл. почта', 'systemMessage' => $checkEmailArr['message']];
        die(json_encode($error));
    }

}

$url = 'insurance/crm/send-callback-request';

$data = json_encode($_POST);

$request = EurasiaAPI::request($url, $data);

echo $request;


