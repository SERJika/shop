<?php
// Скрипт проверки

// Соединямся с БД
require_once("../crud.php");

// Проверяем авторизацию
if (isset($_COOKIE['id']) && isset($_COOKIE['hash'])) {
    $query = $pdo->prepare("SELECT * , INET_NTOA(`user_ip`) AS user_ip 
                            FROM `users` WHERE `user_id` = :cookie_id ");
    $query->bindParam(':cookie_id', $_COOKIE['id'], PDO::PARAM_INT);
    $query->execute();
    $userdata = $query->fetchAll();

    if ( ( $userdata['0']['user_hash'] !== $_COOKIE['hash']) || ($userdata['0']['user_id'] !== $_COOKIE['id']) 
            && (isset( $_SESSION['loginWithIP']) || ($userdata['0']['user_ip'] !== $_SERVER['REMOTE_ADDR']) 
            && ($userdata['0']['user_ip'] !== "0") ) ) {
        setcookie("id", "", time() - 60*60*24, "/", "", "", $httponly = true);
        setcookie("hash", "", time() - 60*60*24, "/", "", "", $httponly = true);
        die( print '<script>alert("У Вас нет прав просматривать данную страницу");</script>
                    <p>Выполнение программы завершено.</p>' );
    }
} else {
    header("Location: login.php"); exit;
}
?>