<?php

require_once('config.php');

session_start();

try {
$pdo = new PDO("mysql:host=$db_host;charset=$db_charset;dbname=$db_name", $db_user, $db_pass, $db_opt);
} catch(PDOException $e) {
echo 'Сайт временно не доступен: проводятся технические работы';
}

// Переменные для сохранения номера страницы и количества товаров на странице при отправке формы
if (isset($_POST['on_page'])) $on_page = (int)$_POST['on_page'];
if (isset($_POST['pageN'])) $pageN = (int)$_POST['pageN'];


/**
* Запросы к БД
*/
class crud
{
	public $pdo;
	public $prodCategTbl;
	function getProducts($pdo)
	{
        $this->pdo = $pdo;
        return $this->prodCategTbl = $this->pdo->query("SELECT p.`id`, p.`title`, p.`description`, p.`price`, p.`img`,
                                    c.`category` AS category 
                                    FROM `products` p 
                                    INNER JOIN `prod_categ_link` pc 
                                    ON p.`id` = pc.`id_prod`
                                    LEFT JOIN `category` c 
                                    ON c.`id` = pc.`id_cat` 
                                    ORDER BY p.`id`")->fetchAll();
	}
	function countProd($pdo)
	{
        $this->pdo = $pdo;
        $this->getProducts($pdo);
        return $number_of_goods = count($this->prodCategTbl);      // Считаем количество товаров
	}
	function getCategory($pdo) 
	{
        // Массив категорий для формирования выпадающего списка поля "Категория"
        $this->pdo = $pdo;
        $category = $this->pdo->query("SELECT * FROM `category`");
        return $categAll = $category->fetchAll();
	}
	function getOneProd($pdo, $id)
	{
	    $this->pdo = $pdo;
	    $this->id = $id;
        $this->prodCategTbl = $this->pdo->prepare("SELECT p.`id`, p.`title`, p.`description`, p.`price`, p.`img`,
                                    c.`category` AS category 
                                    FROM `products` p 
                                    INNER JOIN `prod_categ_link` pc 
                                    ON p.`id` = pc.`id_prod`
                                    LEFT JOIN `category` c 
                                    ON c.`id` = pc.`id_cat` 
                                    WHERE p.`id` = :id");
        $this->prodCategTbl->bindParam('id', $this->id, PDO::PARAM_INT);
        $this->prodCategTbl->execute();
        return $prod =  $this->prodCategTbl->fetchAll();
	}
}

// добавление новой категории
if (isset($_POST['categ_new'])) {
    $new_categ_title = trim($_POST['new_categ_title']);
    
    // переменные для сохранения данных, введенных в форму, при ошибки заполнения
    $_SESSION['form']['new_categ_title'] = $new_categ_title;
    
    if (!empty($new_categ_title)) {
        
        // Проверяю уникальность названия товара
        $pdo = $pdo;
        $issetUpCategTitle = $pdo->prepare("SELECT `id` FROM `category` WHERE `category` = :categ_up");
        $issetUpCategTitle->bindParam(':categ_up', $new_categ_title, PDO::PARAM_STR);
        $issetUpCategTitle->execute();
        $chekIssetCategTitle = $issetUpCategTitle->fetchColumn();
        
        // если название товара уникально
        if (!$chekIssetCategTitle) {
            $sql = "INSERT INTO `category` (`category`) 
                    VALUE (:category)";
            $prod_update = $pdo->prepare($sql);
            $prod_update->bindParam(':category', $new_categ_title, PDO::PARAM_STR);
            $prod_update->execute();
            
            unset($_SESSION['form']);
            $_SESSION['form']['Ok_Categ'] = 'Новая категория добавлена!';
            
            header("Location: admin/admin.php?pageN=$pageN&on_page=$on_page", false);
        
        // если название товара уже есть в БД
        } else {
            $_SESSION['form']['errMsg_Categ'] = 'Ошибка: такое название категории уже присутствует в базе данных!';
            
            header("Location: admin/admin.php?pageN=$pageN&on_page=$on_page", false);
        }
            
    // если название товара не задано        
    } else {
        $_SESSION['form']['errMsg_Categ'] = 'Ошибка: название категории не задано!';
        
        header("Location: admin/admin.php?pageN=$pageN&on_page=$on_page", false);
    }
}	    







// Удаление категории
if (isset($_POST['categ_del'])) {
    $del_categ = $_POST['categ_id'];
    $sql = "DELETE FROM `$db_name`.`category` 
            WHERE `id` = :id";
    $category_del = $pdo->prepare($sql);
    $category_del->bindParam(':id', $del_categ, PDO::PARAM_INT);
    $category_del->execute();
    $_SESSION['form']['Ok_Categ'] = 'Категория успешно удалена!';
    header("Location: admin/admin.php?pageN=$pageN&on_page=$on_page", false);
}


// Изменение категории
if (isset($_POST['categ_change'])) {
    $up_categ_id = $_POST['categ_id'];
    $up_categ_numb = $_POST['categ_numb'];
    $up_categ_title = htmlspecialchars(trim($_POST['categ_title']));
    
    // переменные для сохранения данных, введенных в форму, при ошибки заполнения
    $_SESSION['form']['up_categ_title'] = $up_categ_title;
    $_SESSION['form']['up_categ_id'] = $up_categ_id;
    $_SESSION['form']['up_categ_numb'] = $up_categ_numb;
    
    if (!empty($up_categ_title)) {
        
        
        // Проверяю уникальность названия товара
        $issetUpCategTitle = $pdo->prepare("SELECT `id` FROM `category` WHERE `category` = :categ_up AND `id` != :categ_id");
        $issetUpCategTitle->bindParam(':categ_up', $up_categ_title, PDO::PARAM_STR);
        $issetUpCategTitle->bindParam(':categ_id', $up_categ_id, PDO::PARAM_INT);
        $issetUpCategTitle->execute();
        $chekIssetCategTitle = $issetUpCategTitle->fetchColumn();
        
        // если название товара уникально
        if (!$chekIssetCategTitle) {
            $sql = "UPDATE `$db_name`.`category` 
                    SET `category` = :category 
                    WHERE `id` = :id";
            $prod_update = $pdo->prepare($sql);
            $prod_update->bindParam(':id', $up_categ_id, PDO::PARAM_INT);
            $prod_update->bindParam(':category', $up_categ_title, PDO::PARAM_STR);
            $prod_update->execute();
            
            unset($_SESSION['form']);
            $_SESSION['form']['Ok_Categ'] = 'Название категории успешно изменено!';
            header("Location: admin/admin.php?pageN=$pageN&on_page=$on_page", false);
        
        // если название категории уже есть в БД
        } else {
            $_SESSION['form']['errMsg_Categ'] = 'Ошибка: такое название категории уже присутствует в базе данных!';
            header("Location: admin/admin.php?pageN=$pageN&on_page=$on_page", false);
        }
            
    // если название каегории не задано        
    } else {
        $_SESSION['form']['errMsg_Categ'] = 'Ошибка: название категории не задано!';
        header("Location: admin/admin.php?pageN=$pageN&on_page=$on_page", false);
    }
}

// Запрос на создание Нового товара в БД
if (isset($_POST['p_new'])) {
    $new_descr = strip_tags($_POST['new_descr'], '<br><ul><ol><li>'); // strip_tags выбран, чтобы не нарушить форматирование описание разрешенными тегами; достаточна ли обработка?
    $new_price = 0 + str_replace(",",".",str_replace(" ","",$_POST['new_price'])); // через сложение преобразую в float
    $new_categ = htmlspecialchars($_POST['new_categ']); // категории подгружаются из БД, пользователь только выбирает из списка
    
    // переменные для сохранения данных, введенных в форму, при ошибки заполнения
    $_SESSION['form']['new_descr'] = $new_descr;
    $_SESSION['form']['new_price'] = $new_price;
    $_SESSION['form']['new_categ'] = $new_categ;
    
    if (!empty($_POST['new_title'])) {
        $new_title = strip_tags(trim($_POST['new_title']), '<br>');
        
        // Проверяю уникальность названия товара
        $issetProdTitle = $pdo->prepare("SELECT `id` FROM `products` WHERE `title` = ?");
        $issetProdTitle->bindParam(1, $new_title, PDO::PARAM_STR);
        $issetProdTitle->execute();
        $title = $issetProdTitle->fetchColumn();
        
        // если название товара уникально
        if (!$title) {
            $sql = "INSERT INTO `$db_name`.`products` (`title`,`description`,`price`) 
                    VALUES (:title,:descr,:price)";
            $newProduct = $pdo->prepare($sql);
            $bindArr = [
                ':title' => $new_title,
                ':descr' => $new_descr,
                ':price' => $new_price,
                ];
            $newProduct->execute($bindArr);
        
            // Получаю id-товара и id-категории для нового товара
            $getProductID = $pdo->prepare("SELECT `id` FROM `$db_name`.`products` WHERE `title` = ?");
            $getProductID->bindParam(1, $new_title, PDO::PARAM_STR);
            $getProductID->execute();
            $productID = $getProductID->fetchColumn();
            
            $getCategoryID = $pdo->prepare("SELECT `id` FROM `$db_name`.`category` WHERE `category` = ?");
            $getCategoryID->bindParam(1, $new_categ, PDO::PARAM_STR);
            $getCategoryID->execute();
            $categoryID = $getCategoryID->fetchColumn();
        
            // Формирую таблицу связи товаров и категорий
            $sql = "INSERT INTO `$db_name`.`prod_categ_link` (`id_prod`, `id_cat`) 
                    VALUES (:id_prod, :id_cat)";
            $products_new = $pdo->prepare($sql);
            $products_new->bindParam(':id_prod', $productID, PDO::PARAM_INT);
            $products_new->bindParam(':id_cat', $categoryID, PDO::PARAM_INT);
            $products_new->execute();
            
            $_SESSION['form']['okMsg'] = 'Новый товар успешно добавлен в базу данных';
            header("Location: admin/admin.php?pageN=$pageN&on_page=$on_page", false); exit;
        
        // если название не уникально 
        } else {
            $_SESSION['form']['errMsg'] = 'Ошибка: такое название товара уже присутствует в базе данных!';
            $_SESSION['form']['new_title'] = $new_title;        // Сохранение в поле ошибочного названия для удобства исправления ошибки пользователем
            header("Location: admin/admin.php?pageN=$pageN&on_page=$on_page", false); exit;
        }
    
    // если не задано название товара
    } else {
        $_SESSION['form']['errMsg'] = 'Ошибка: не задано название товара!';
        header("Location: admin/admin.php?pageN=$pageN&on_page=$on_page", false);
    }
}

// Удаление товара
if (isset($_POST['p_del'])) {
    $del_id = $_POST['p_id'];
    $sql = "DELETE FROM `$db_name`.`products` 
            WHERE `products`.`id` = :id";
    $products_del = $pdo->prepare($sql);
    $products_del->bindParam(':id', $del_id, PDO::PARAM_INT);
    $products_del->execute();
    $_SESSION['form']['okMsg'] = 'Данные товара успешно удалены';
    header("Location: admin/admin.php?pageN=$pageN&on_page=$on_page", false);
}

// Изменение товара
if (isset($_POST['p_subm'])) {
    $up_id = $_POST['p_id'];
    $up_num = $_POST['p_num'];
    $up_title = strip_tags(trim($_POST['p_title']), '<br>');
    $up_descr = strip_tags($_POST['p_descr'], '<br><ul><ol><li>');
    $up_price = 0 + str_replace(",",".",str_replace(" ","",$_POST['p_price']));
    $up_categ = htmlspecialchars($_POST['p_categ']);
        
    // переменные для сохранения данных, введенных в форму, при ошибки заполнения
    $_SESSION['form']['up_descr'] = $up_descr;
    $_SESSION['form']['up_price'] = $up_price;
    $_SESSION['form']['up_categ'] = $up_categ;
    $_SESSION['form']['up_id'] = $up_id;
    $_SESSION['form']['up_title'] = $up_title;
    $_SESSION['form']['up_num'] = $up_num;
    
    if (!empty($up_title)) {
        
        // Проверяю уникальность названия товара
        $issetUpdateTitle = $pdo->prepare("SELECT `id` FROM `products` WHERE `title` = ? AND `id` != ?");
        $issetUpdateTitle->bindParam(1, $up_title, PDO::PARAM_STR);
        $issetUpdateTitle->bindParam(2, $up_id, PDO::PARAM_INT);
        $issetUpdateTitle->execute();
        $chekIssetTitle = $issetUpdateTitle->fetchColumn();
        
        // если название товара уникально
        if (!$chekIssetTitle) {
            $sql = "UPDATE `$db_name`.`products` 
                    SET `title` = :title, `description` = :descr, `price` = :price 
                    WHERE `products`.`id` = :id";
            $prod_update = $pdo->prepare($sql);
            $prod_update->bindParam(':id', $up_id, PDO::PARAM_INT);
            $prod_update->bindParam(':title', $up_title, PDO::PARAM_STR);
            $prod_update->bindParam(':descr', $up_descr, PDO::PARAM_STR);
            $prod_update->bindParam(':price', $up_price); // price - float, not int
            $prod_update->execute();
            
            $getCategoryID = $pdo->prepare("SELECT `id` FROM `$db_name`.`category` WHERE `category` = ?");
            $getCategoryID->bindParam(1, $up_categ, PDO::PARAM_STR);
            $getCategoryID->execute();
            $categoryID = $getCategoryID->fetchColumn();
        
            $sql = "UPDATE `$db_name`.`prod_categ_link` 
                    SET `id_cat` = ? 
                    WHERE `id_prod` = ?";
            $products_new = $pdo->prepare($sql);
            $products_new->bindParam(1, $categoryID, PDO::PARAM_INT);
            $products_new->bindParam(2, $up_id, PDO::PARAM_INT);
            $products_new->execute();
            
            unset($_SESSION['form']);
            $_SESSION['form']['okMsg'] = 'Данные товара успешно изменены';
            header("Location: admin/admin.php?pageN=$pageN&on_page=$on_page", false);
        
        // если название товара уже есть в БД
        } else {
            $_SESSION['form']['errMsg_update'] = 'Ошибка: такое название товара уже присутствует в базе данных!';
            header("Location: admin/admin.php?pageN=$pageN&on_page=$on_page", false);
        }
            
    // если название товара не задано        
    } else {
        $_SESSION['form']['errMsg_update'] = 'Ошибка: название товара не задано!';
        header("Location: admin/admin.php?pageN=$pageN&on_page=$on_page", false);
    }
}