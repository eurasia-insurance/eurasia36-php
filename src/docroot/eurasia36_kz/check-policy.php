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

if(!isset($_POST['policyNumber']) || empty($_POST['policyNumber'])) {
    header("HTTP/1.0 403 Empty request");
    die();
}

$url = 'insurance/policy/check-policy';

$data = array('policyNumber' => $_POST['policyNumber']);
$data = json_encode($data);

$request = EurasiaAPI::request($url, $data, 'post', $apiLang);

echo $request;
