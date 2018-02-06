/*
<?php
require_once './../.settings.php';
header('Content-Type: application/javascript');
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$host = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];
if ( ($_SERVER['REQUEST_SCHEME'] == 'https' && $_SERVER['SERVER_PORT'] != '443') || ($_SERVER['REQUEST_SCHEME'] == 'http' && $_SERVER['SERVER_PORT'] != '80')) {
    $host .= ':' . $_SERVER['SERVER_PORT'];
}
?>
*/
var host = '<?=$host?>';
<?php

if(defined('PARAM_SHOW_EUBANK_BONUS') && PARAM_SHOW_EUBANK_BONUS == 1) {
    echo "var showBonus = 1;\n";
} else {
    echo "var showBonus = 0;\n";
}

?>
<?php include_once 'jquery.widget-call.js'; ?>
