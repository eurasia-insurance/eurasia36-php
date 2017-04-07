<?php
require_once './.settings.php';

require './api/EurasiaAPI.php';

session_start();

if(!isset($_SESSION['initMain']) || $_SESSION['initMain'] != 1) {
    header("HTTP/1.0 403 Go some other way");
    die();
}


if (!isset($_SESSION['iinRequestActivity'])) {

} else if (time() - $_SESSION['iinRequestActivity'] > 1800) {
    // session started more than 30 minutes ago

//    session_unset();     // unset $_SESSION variable for the run-time
    unset($_SESSION['iinRequest']);

    session_regenerate_id(true);    // change session ID for the current session and invalidate old session ID
}
$_SESSION['iinRequestActivity'] = time();  // update creation time


if(isset($_SESSION['iinRequest'])) {

    if($_SESSION['iinRequest'] >= 20) {
        header("HTTP/1.0 403 Too much requests");
        die();
    }

    $_SESSION['iinRequest'] += 1;
} else {
    $_SESSION['iinRequest'] = 1;
}



//$driver = file_get_contents($PARAM_WSAPI_URL . '/order/ws/policy/fetch-driver/' . str_replace(" ", '', $_POST['iin']));
//
//if($driver) {
//    echo $driver;
//}



$clientByIin = EurasiaAPI::getPersonByIIN($_POST['iin']);

echo $clientByIin;
