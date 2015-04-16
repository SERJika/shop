<?php
require_once('crud.php');

$client_order = $pdo->prepare("SELECT o.`id` AS order_N, p.`id` AS prod_id, p.`title`, p.`price`, op.`amount` AS amount, o.`date` AS order_date,
                            cl.`name` AS client_name,
                            cl.`phone` AS client_phone,
                            cl.`email`AS client_email
                            FROM `products` p INNER JOIN `order_prod` op ON p.`id` = op.`prod_id`
                            LEFT JOIN `orders` o ON o.`id` = op.`order_id`
                            LEFT JOIN `clients` cl ON cl.`id` = o.`client_id`
                            WHERE o.`id` = :id
                            ORDER BY order_N");
$client_order->bindParam(':id', $_SESSION['order_id'], PDO::PARAM_INT);
$client_order->execute();
$client_order_end = $client_order->fetchAll();

// Получатель
$toCl  = $client_order_end['0']['client_email'];

// тема письма
$subject = 'Подтверждение заказа на сайте ' . BASE_DOMAIN;

// текст письма
    $message = '
<html>
<head>
    <title>Подтверждение заказа</title>
</head>
<body>
    <p>' . $client_order_end['0']['client_name'] . '! На сайте ' . BASE_DOMAIN . ' Вами было заказано:</p>
    <table>
        <tr>
            <th style="border: solid 1px;">Наименование товара</th>
            <th style="border: solid 1px;">Цена</th>
            <th style="border: solid 1px;">Количество</th>
            <th style="border: solid 1px;">Сумма</th>
        </tr>';
        
    $summ ='';
foreach ($client_order_end as $key => $order) {
    $message .= '
        <tr>
            <td style="padding: 5px; border: solid 1px;">' . $order['title'] . '</td>
            <td style="padding: 5px; border: solid 1px;">' . number_format($order['price'], 2, ',', ' ') . '</td>
            <td style="padding: 5px; border: solid 1px;">' . $order['amount'] . '</td>
            <td style="padding: 5px; border: solid 1px;">' . number_format($order['amount'] * $order['price'], 2, ',', ' ') . '</td>
        </tr>';
    $summ += $order['amount'] * $order['price'];
}
    
    $message .= '
    </table>
    <p>Итого: наименований товара - ' . count($client_order_end) . ' шт на общую сумму ' . number_format($summ, 2, ',', ' ') . ' рублей</p>
    <p>Наш менеджер свяжется с Вами по указанному телефону ' . $client_order_end['0']['client_phone'] . ' в течение рабочего дня</p>
</body>
</html>
';

// Для отправки HTML-письма должен быть установлен заголовок Content-type
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=utf8' . "\r\n";

// Дополнительные заголовки
$headers .= 'To: ' . $client_order_end['0']['client_name'] . '<' . $client_order_end['0']['client_email'] . '>' . "\r\n";
$headers .= 'From: Epic Shop <lavrovdoctor@mail.ru>' . "\r\n";
$headers .= 'Reply-to: Epic Shop <lavrovdoctor@mail.ru>' . "\r\n";

// Отправляем
mail($toCl, $subject, $message, $headers);


// уведомление для администратора
$toAdm  = $admin_mail_1 . ', '; // админ-1
$toAdm .= $admin_mail_2;   // админ-2

// тема письма
$subject = 'Уведомление о заказе на сайте http://epic.midnigli.bget.ru/shop/';

// текст письма
$message = '
<html>
<head>
    <title>Уведомление о заказе</title>
</head>
<body>
    <p>' . $client_order_end['0']['client_name'] . ' заказал на сайте http://epic.midnigli.bget.ru/shop/ следующие товары:</p>
    <table>
        <tr>
            <th style="border: solid 1px;">Наименование товара</th>
            <th style="border: solid 1px;">Цена</th>
            <th style="border: solid 1px;">Количество</th>
            <th style="border: solid 1px;">Сумма</th>
        </tr>';
    $summ ='';
foreach ($client_order_end as $key => $order) {
    $message .= '
        <tr>
            <td style="padding: 5px; border: solid 1px;">' . $order['title'] . '</td>
            <td style="padding: 5px; padding: 5px; border: solid 1px;">' . number_format($order['price'], 2, ',', ' ') . '</td>
            <td style="padding: 5px; padding: 5px; border: solid 1px;">' . $order['amount'] . '</td>
            <td style="padding: 5px; padding: 5px; border: solid 1px;">' . number_format($order['amount'] * $order['price'], 2, ',', ' ') . '</td>
        </tr>';
    $summ += $order['amount'] * $order['price'];
  }
    
    $message .= '
    </table>
    <p>Итого: наименований товара - ' . count($client_order_end) . ' шт на общую сумму ' . number_format($summ, 2, ',', ' ') . ' рублей</p><br />
    <p>Дата и время заказа: ' . $client_order_end['0']['order_date'] . '</p><br />
    <p>Для связи указано <br />
        <b>Имя:</b> ' . $client_order_end['0']['client_name'] . ' <br />
        <b>Телефон:</b> ' . $client_order_end['0']['client_phone'] . ' <br />
        <b>Эл.почта:</b> ' . $client_order_end['0']['client_email'] . '
    </p>
</body>
</html>
';

// Для отправки HTML-письма должен быть установлен заголовок Content-type
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=utf8' . "\r\n";

// Дополнительные заголовки
$headers .= 'To: ' . $admin_mail_1 . '<' . $admin_mail_1 . '>' . "\r\n";
$headers .= 'From: Epic Shop <lavrovdoctor@mail.ru>' . "\r\n";

// Отправляем
mail($toAdm, $subject, $message, $headers);

// Сообщение пользователю об успешной отправке подтверждения заказа
echo '<div style="margin: 50px 20px; ">Заказ оформлен. <br />На Вашу почту отправлено  уведомление о принятии заказа.</div>';
unset($_SESSION['id']);
?>