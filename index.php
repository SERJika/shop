<?php

require_once('config.php');
require_once('crud.php');
require_once('menu.php');

$cartMenu = new Menu('Корзина', 'cartMenu', $cart);
$topMenu = new Menu('Верхнее меню', 'topMenu', $itemsTop);
$mainMenu = new Menu('Основное меню', 'mainMenu', $itemsMain);

empty($_SESSION['id']) ? $prodInCart = 0 : $prodInCart = count($_SESSION['id']);

!isset($_GET['page']) ? $page = 'about' : $page = $_GET['page'];

foreach ( $itemsMain as $key => $value){
    $menu[] = $value['nick'];
}
foreach ( $cart as $key => $value){
    $menu[] = $value['nick'];
    }

in_array($page, $menu) || $page == 'prod'|| $page == 'mail' ? $getFile = $page . '.php' : $getFile = '404.php';

require_once('index_html.php');
?>


