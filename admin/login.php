<?php
// Страница авторизации

// Функция для генерации случайной строки
function generateCode($length=6) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI_JKLMNOPRQSTUVWXYZ0123456789";
    $code = "";
    $clen = strlen($chars) - 1;
    while (strlen($code) < $length) {
            $code .= $chars[mt_rand(0,$clen)];
    }
    return $code;
}

// Соединямся с БД
require_once("../crud.php");

$msg = ''; // Переменная для сообщений об ошибке

if(isset($_POST['submit'])) {
    $login = $_POST['login'];
    $password = $_POST['password'];
    // Вытаскиваем из БД запись, у которой логин равняеться введенному
    $query = $pdo->prepare("SELECT `user_id`, `user_password` FROM `users` WHERE `user_login` = :login");
    $query->bindParam(':login', $login, PDO::PARAM_STR);
    $query->execute();
    $data = $query->fetchAll();

    if ($data) {
    
        // Сравниваем пароли
        if ( $data['0']['user_password'] === md5( md5( $_POST['password'] ) ) ) {
            // Генерируем случайное число и шифруем его
            $hash = md5( generateCode(10) );
            if ( !isset($_POST['not_attach_ip']) ) {
                // Если пользователь выбрал привязку к IP
                // Переводим IP в строку
                $serverIP = $_SERVER['REMOTE_ADDR']; 
            } else {
                $_SESSION['loginWithIP'] = false;
            }
    
            // Записываем в БД новый хеш авторизации и IP
            $sql = $pdo->prepare("UPDATE `users` SET `user_hash` = :hash, `user_ip` = INET_ATON(:serverIP) 
                                  WHERE `user_id` = :data");
            $sql->bindParam(':hash', $hash, PDO::PARAM_STR);
            $sql->bindParam(':serverIP', $serverIP, PDO::PARAM_STR);
            $sql->bindParam(':data', $data['0']['user_id'], PDO::PARAM_STR);
            $update = $sql->execute();
    
            // Ставим куки
            setcookie("id", $data['0']['user_id'], time()+60*60*24, "", "", "", $httponly = true);
            setcookie("hash", $hash, time()+60*60*24, "", "", "", $httponly = true);
            
            // Переадресовываем браузер на страницу проверки нашего скрипта
            header("Location: admin.php"); exit();
        } else {
            $msg = "Вы ввели неправильный логин/пароль";
        }
    } else { 
        $msg = "Вы ввели неправильный логин/пароль";
    }
}
?>

<div style="width: 300px; margin: 200px auto; padding: 15px; border: solid grey 1px;">
    <form method="post">
        <span style="display: inline-block; width: 55px;">Логин </span>
        <input style="margin: 5px 0;" name="login" type="text"><br>
        <span style="display: inline-block; width: 55px;">Пароль </span>
        <input style="margin: 5px 0;" name="password" type="password"><br>  
        <p style="margin: 5px 0;">
            <input type="checkbox" name="not_attach_ip">Без контроля IP (менее безопасно)
        </p>   
        <input style="margin: 5px 0;" name="submit" type="submit" value="Войти">
    </form>
    <p style='color: red;'><?php echo $msg; ?></p>
</div>