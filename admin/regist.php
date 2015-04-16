<?php
// Страница регистрации нового пользователя

// Соединямся с БД
require_once("../crud.php");

    $err = [];

if (isset($_POST['submit']))
{
    $login = $_POST['login'];
    $password = $_POST['password'];
    
    // проверям логин
    if ( !preg_match("/^[a-zA-Z0-9]+$/",$_POST['login']) ) {
        $err[] = "Логин может состоять только из&nbsp;букв английского алфавита и&nbsp;цифр";
    }

    if ( strlen( $_POST['login'] ) < 5 || strlen( $_POST['login'] ) > 30 ) {
        $err[] = "Логин должен быть не&nbsp;меньше 5&nbsp;символов и&nbsp;не&nbsp;больше&nbsp;30";
    }
    
    if ( strlen( $_POST['password'] ) < 5 || strlen( $_POST['password'] ) > 250 ) {
        $err[] = "Пароль должен быть не&nbsp;меньше 5&nbsp;символов и&nbsp;не&nbsp;больше&nbsp;250";
    }

    // проверяем, не сущестует ли пользователя с таким именем
    $query = $pdo->prepare("SELECT COUNT(`user_id`) FROM `users` WHERE `user_login` = :login");
    $query->bindParam(':login', $login, PDO::PARAM_STR);
    $query->execute();
    $suchLogin = $query->fetchColumn();

    if ( !empty($suchLogin) ) {
        $err[] = "Пользователь с&nbsp;таким логином уже существует в&nbsp;базе данных";
    }

    // Если нет ошибок, то добавляем в БД нового пользователя
    if (count($err) == 0)
    {
        // Убираем лишние пробелы и делаем двойное шифрование
        $pass = md5( md5( trim( $password ) ) );

        $query = $pdo->prepare("INSERT INTO `users` (`user_login`, `user_password`) VALUES (:login, :pass)");
        $query->bindParam(':login', $login, PDO::PARAM_STR);
        $query->bindParam(':pass', $pass, PDO::PARAM_STR);
        $query->execute();
        header("Location: login.php"); exit();
    }
}

function errMsg($err) {
    if ($err) {
        print "<b>При регистрации произошли <br />следующие ошибки:</b><br>";
        echo '<ol>';
        foreach($err AS $error) {
            echo "<li style='color: red;'>$error</li>";
        }
        echo '</ol>';
    }
}
?>

<div style="width: 300px; margin: 170px auto; padding: 15px; border: solid grey 1px;">
    <form method="post">
        <span style="display: inline-block; width: 55px;">Логин </span>
        <input style="margin: 5px 0;" name="login" type="text"><br>
        <span style="display: inline-block; width: 55px;">Пароль </span>
        <input style="margin: 5px 0;" name="password" type="password"><br>
        <input style="margin: 5px 0;" name="submit" type="submit" value="Зарегистрироваться">
    </form>
    <p style="color: red;"><?php errMsg($err); ?></p>
</div>