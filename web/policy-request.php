<?php

require './api/EurasiaAPI.php';


session_start();


if(!isset($_POST['requester'])) {
    header("HTTP/1.0 403 No data passed");
    die();
}


if(!isset($_SESSION['initMain']) || $_SESSION['initMain'] != 1) {
    header("HTTP/1.0 403 Go some other way");
    die();
}

if (!isset($_SESSION['policyRequestActivity'])) {

} else if (time() - $_SESSION['policyRequestActivity'] > 1800) {
    // session started more than 30 minutes ago

//    session_unset();     // unset $_SESSION variable for the run-time
    unset($_SESSION['policyRequest']);

    session_regenerate_id(true);    // change session ID for the current session and invalidate old session ID

}
$_SESSION['policyRequestActivity'] = time();  // update creation time


$gCaptchaResponse = '';
if(isset($_POST['requester']['g-recaptcha-response'])) {
    $gCaptchaResponse = $_POST['requester']['g-recaptcha-response'];

    unset( $_POST['requester']['g-recaptcha-response'] );
}


if(isset($_SESSION['policyRequest'])) {

    if($_SESSION['policyRequest'] > 5) {

        // check captch

        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = [
            'secret' => '6LdQBBsUAAAAAPiy24nP1yPLyy3jKt7nFwh41w8e',
            'response' => $gCaptchaResponse
        ];

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_USERAGENT, 'grafica-API-client/1.0');
        curl_setopt($curl, CURLOPT_URL, $url);

        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
//        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        curl_setopt($curl, CURLOPT_HEADER, FALSE);
        //curl_setopt($curl, CURLOPT_COOKIEFILE, dirname(__FILE__) . '/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
        //curl_setopt($curl, CURLOPT_COOKIEJAR, dirname(__FILE__) . '/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

        $out = curl_exec($curl); # Initiate a request to the API and stores the response variable
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        $out = json_decode($out, true);

        if(!isset($out['success']) || $out['success'] != true) {
            header("HTTP/1.0 403 Captcha check fail");
            die();
        }

//        echo 'captcha check';
//        var_dump($out);
//        die();
    }

    $_SESSION['policyRequest'] += 1;
} else {
    $_SESSION['policyRequest'] = 1;
}




//
//echo json_encode($_POST);
//die();

$phone = isset($_POST['requester']['phone']) ? trim($_POST['requester']['phone']) : null;
$email = isset($_POST['requester']['email']) ? trim($_POST['requester']['email']) : null;

// проверяем телефон
if(isset($phone)) {

    $phone = str_replace(" ", '', $phone);
    $phone = str_replace("-", '', $phone);
    $phone = str_replace("(", '', $phone);
    $phone = str_replace(")", '', $phone);

    $url = 'check/phone/'.$phone;

    $data = '{}';

    $checkPhone = EurasiaAPI::request($url, $data, 'get');
    $checkPhoneArr = json_decode($checkPhone, true);

    if(isset($checkPhoneArr['error'])) {
        $error = ['error' => true, 'message' => 'Неверно указан номер телефона'];
        die(json_encode($error));
    }

} else {
    $error = ['error' => true, 'message' => 'Вы забыли указать номер телефона'];
    die(json_encode($error));
}


// проверяем мыло, если его указали
if(isset($email)) {

    $url = 'check/email/'.$email;

    $data = '{}';

    $checkEmail = EurasiaAPI::request($url, $data, 'get');
    $checkEmailArr = json_decode($checkEmail, true);

    if(isset($checkEmailArr['error'])) {
        $error = ['error' => true, 'message' => 'Неверно указана эл. почта'];
        die(json_encode($error));
    }

}

$url = 'crm/send-policy-request';

$_POST['type'] = 'EXPRESS';

$data = json_encode($_POST);

//var_dump($_POST);
//die();

$request = EurasiaAPI::request($url, $data);

$json = __DIR__ . '/leads/' . session_id() . '.json';
if(is_file($json)) {
    unlink($json);
}

echo $request;


