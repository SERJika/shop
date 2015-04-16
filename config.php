<?php

// Development
ini_set('display_errors', 'On');
ini_set('idisplay_startup_errors', 'On');
ini_set('error_reporting', '-1');
ini_set('log_errors', 'On');

// Production
// ini_set('display_errors', 'Off');
// ini_set('idisplay_startup_errors', 'Off');
// ini_set('error_reporting', 'E_ALL');
// ini_set('log_errors', 'On');

define('BASE_DOMAIN', 'http://epic.midnigli.bget.ru/shop/');

$db_host = 'localhost';
$db_name = 'midnigli_lodki';
$db_charset = 'utf8';
$db_user = 'midnigli_lodki';
$db_pass = 'TF[hFJa7';
$db_opt = array(
    PDO::ATTR_DEFAULT_FETCH_MODE   => PDO::FETCH_ASSOC,
    PDO::ATTR_ERRMODE              => PDO::ERRMODE_EXCEPTION
);

$admin_mail_1 = 'lavrovdoctor@mail.ru';
$admin_mail_2 = '';