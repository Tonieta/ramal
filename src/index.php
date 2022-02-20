<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

include_once './controllers/Pjsip.php';
echo "<pre>";
$pjsip = new Pjsip;
$pjsip->criarPjsipExtension();

// include_once './models/Pjsip.php';
// echo "<pre>";
// $pjsip = new PjsipMysql;
// print_r($pjsip->getPjsip());
