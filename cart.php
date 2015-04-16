<?php
    require_once('crud.php');

        // Если товар не выбран
        $cartEmpty = '<div style="margin: 55px 20px;">Корзина пуста: вы еще не выбрали товар для покупки.
                    <br /><br /><a href="?page=catalog">Перейти в каталог товаров</a></div>';

        // для кнопки "Пересчитать"
    if (!empty($_POST)) {
        foreach ($_POST as $key => $value) {
            // Изменение количества выбранного товара
            $changeValue_id = strpos($key, 'id_custom');
            if ($changeValue_id !== false) {
                $id_this = 'amount'. $value;
                $_SESSION['id'][$value] = $_POST[$id_this];
            }
            // Удаление выбранного товара
            $delProd = strpos($key, 'del');
            if ($delProd !== false) {
                unset($_SESSION['id'][$value]);
            }
        }
    }
        
function getData($name, $key, $pdo) {
            $sql = "SELECT `$name` FROM `products` WHERE `id` = ?";
            $take = $pdo->prepare($sql);
            $take->bindParam(1, $key, PDO::PARAM_INT);
            $take->execute();
            return $selectedName = $take->fetchColumn();
            
}
?>



<?php
    if (!empty($_SESSION['id'])) {
?>
<div style="padding: 20px;">
                    <!-- Автоподсчет суммы по цене и количеству -->    
                <script>
                    function price(key, price) {
                        var x = document.getElementById('amount'+key).value;
                        var y = Number(price);
                        var z = x * y;
                        var zxy = z.toFixed(2);
                        document.getElementById('summ_this'+key).innerHTML = accounting.formatNumber(zxy, 2, " ", ",") + ' руб.';
                    }
                </script>
    <form method="post">
        <table>
            <!-- Шапка таблицы -->
            <tr>
                <td style="width: 45px; text-align: center;">N п/п</td>
                <td style="width: 250px; padding-left: 15px; text-align: left;">Название</td>
                <td style="width: 130px; text-align: center;">Цена</td>
                <td style="width: 75px; text-align: center;">Кол-во</td>
                <td style="width: 150px; text-align: center;">Сумма</td>
                <td>Удалить</td>
            </tr>
            
<?php
            $summ_price = 0;
            $i = 1;
            foreach ($_SESSION['id'] as $key => $value) { 
?> 
            <input type="hidden" name="id_custom<?php echo $key; ?>" value="<?php echo $key; ?>">

            <!-- Часть таблицы с выбранным товаром -->
            <tr>
                <td style="padding-right: 10px; text-align: right;">
                    <?php echo $i; ?>
                </td>
                <td style="padding-left:10px;">
                    <?php echo getData('title', $key, $pdo); ?>
                </td>
                <td style="padding-right: 5px; text-align: right;">
                    <?php echo $price = number_format(getData('price', $key, $pdo), 2, ',', ' '); ?> руб.
                </td>
                <td style="text-align: center; padding: 0;">
                    <input class="input-right" id="amount<?php echo $key; ?>" type="number" id="amount<?php echo $key; ?>" 
                    name="amount<?php echo $key; ?>" value="<?php echo $value; ?>" min="1" style="height: 100%; margin: 0; border: 0;" oninput="price(<?php echo $key . ", '" . getData('price', $key, $pdo) . "'"; ?>)">
                </td>
                <td id="summ_this<?php echo $key; ?>" style="padding-right: 5px; text-align: right;">
                    <?php $count = getData('price', $key, $pdo) * $value; echo $countPrinted = number_format($count, 2, ',', ' '); ?> руб.
                </td>
                <td  style="text-align: center;">
                    <input type="checkbox" name="del<?php echo $key; ?>" value="<?php echo $key; ?>">
                </td>
            </tr>
                <div>
                    <!-- Считаем итоговую сумму -->
                    <input type="hidden" name="summ_price" value="<?php $summ_price += $count; ?>">
                </div>
            </div>
            <br />
          
<?php
    $i++;
    } 
?>
        
        </table>
        <div style="margin: 15px 0;">
            Итого: выбрано товарных позиций - <?php echo count($_SESSION['id']) ?> шт 
            на общую сумму <?php echo $summ_price = number_format($summ_price, 2, ',', ' '); ?> руб.
        </div>
        <input type="submit" value="Пересчитать" name="change">
        <input type="submit" value="Заказать" name="order" style="margin-left: 295px;">
    </form>
</div>    
<?php 
    } else {
        echo $cartEmpty;
    }
    
    if (isset($_POST['order'])) {
?>
<div style="padding: 20px;">        
    <div style="margin-top: 25px;">
    
    <!-- Оформление заказа --> 
    <form method="post"> 
        <input type="hidden" name="order" value="<?php echo $_POST['order']; ?>">
        <input type="text" name="cl_name" placeholder="Ваше имя" value="<?php if (isset($_POST['cl_name'])) echo $_POST['cl_name']; ?>">
        <input type="number" name="cl_phone"placeholder="Ваш телефон" value="<?php if (isset($_POST['cl_phone'])) echo $_POST['cl_phone']; ?>">
        <input type="email" name="cl_email" placeholder="Ваш e-mail" value="<?php if (isset($_POST['cl_email'])) echo $_POST['cl_email']; ?>">
        <input type="submit" name="submit" value="OK">
        <div style="height: 0; width=100%; clear: both;"></div>
        <p class="example">Иван Иванович</p>
        <p class="example">Тел. 89215554433 или 4566543</p>
        <p class="example">email@mail.ru</p>
        <div style="height: 0; width=100%; clear: both;"></div>
    </form>
            
<?php
        if (isset($_POST['submit'])) {  
            if (!empty($_POST['cl_name']) && !empty($_POST['cl_phone']) && !empty($_POST['cl_email'])) {
                    $cl_name = trim($_POST['cl_name']);
                    $cl_phone = trim($_POST['cl_phone']);
                    $cl_email = trim($_POST['cl_email']);

                    if (filter_var($cl_email, FILTER_VALIDATE_EMAIL) != 'false' ) {    
                        $sql = $pdo->prepare( "INSERT INTO `clients` (`name`,`phone`,`email`) VALUES (:name,:phone,:email) ");
                        $sql->execute([':name' => $cl_name, ':phone' => $cl_phone, ':email' => $cl_email]);
                        $client_id = $pdo->lastInsertId();
                        
                        $now = time();
                        date_default_timezone_set('Europe/Moscow');
                        $now = new \DateTime();
                        $date = $now->format('d/m/Y H:i:s');
                        
                        $new_order = $pdo->prepare("INSERT INTO `orders` (`date`,`client_id`) VALUES (:date,:client_id)");
                        $new_order->execute([':date' => $date, ':client_id' => $client_id]);
                        $order_id = $pdo->lastInsertId();
                               
                    foreach ($_SESSION['id'] as $key => $value) {
                            $prod_id =$key;
                            $amount = $value;;
                            $order_prod = $pdo->prepare("INSERT INTO `order_prod` (`order_id`, `prod_id`, `amount`) VALUES (:order_id, :prod_id, :amount) ");
                            $order_prod->execute([':order_id' => $order_id, ':prod_id' => $prod_id, ':amount' => $amount]);
                    }
                        
                        $_SESSION['order_id'] = $order_id;
                        ?>

                        <script language="JavaScript" type="text/javascript">
                            location="index.php?page=mail";
                        </script>

                        <?php
                        exit;
                } else {
                    echo '<p style="color: red;">Ошибка: неверный e-mail</p>';
                }
            
            } else {
                echo $msg = '<p style="color: red;">Ошибка: нужно заполнить все поля!</p>';
            }
        }
    }
    
?>
</div>