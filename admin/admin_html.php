<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title></title>
    <style type="text/css">
        ul li {
            display: inline-block;
            margin: 0 5px;
        }
        input {
            text-align: center;
        }
        .input-right {
            text-align: right;
        }
        .input-left {
            text-align: left;
        }
        .noscript {
            margin: 10px 0 10px 30%; 
            font-size: 21px;
            color: red;
        }
    </style>
</head>
<body>
    <div style="width: 1250px; margin: 20px auto;">
    <noscript><div class="noscript">Для правильной работы сайта включите JavaScript в браузере!</div></noscript>        
    <script>
        function newCateg() {
            document.getElementById('new_categ').style.display = "block";
            document.getElementById('openNewCateg').style.display = "none"; 
        }
        function escapeCateg() {
            document.getElementById('new_categ').style.display = "none"; 
            document.getElementById('openNewCateg').style.display = "block"; 
        }
        function enableCategChg($l) {
            document.getElementById('categ_name'+$l).removeAttribute('disabled');
            document.getElementById('categOk'+$l).removeAttribute('disabled');
        }
        function enableChg(i) {
            document.getElementById('title'+i).removeAttribute('disabled'); 
            document.getElementById('descr'+i).removeAttribute('disabled');
            document.getElementById('price'+i).removeAttribute('disabled');
            document.getElementById('categ'+i).removeAttribute('disabled');
            document.getElementById('ok'+i).removeAttribute('disabled');
            document.getElementById('categ'+i).style.color = "#000000";
            document.getElementById('categ'+i).style.backgroundColor = "#ffffff";
        }
        function newRow() {
            document.getElementById('new_row').style.display = "block";
            document.getElementById('openNew').style.display = "none"; 
        }
        function escape() {
            document.getElementById('new_row').style.display = "none"; 
            document.getElementById('openNew').style.display = "block"; 
        }
    </script>     
    <div style="margin: 25px 0;">
        <div style="margin: 20px 0;">
            <a href="../index.php">Вернуться на главную</a>
        </div>
        <h2>Категории товаров</h2>
        <form>
            <input type="text" name="catg_numb" disabled value="№" style="width: 45px;">
            <input type="text" name="catg_id" disabled value="Код" style="width: 45px;">
            <input type="text" name="catg_name" value="Наименование" disabled style="width: 145px;">
            <input type="text" size="5" name="catg-edit" value="Изменить" disabled style="width: 92px;">
            <input type="submit" size="5" name="catg_change" value="Сохранить" disabled style="width: 90px;">
            <input type="button" size="5" name="catg_del" value="Удалить" disabled style="width: 76px;">
        </form>
        <div style="height: 5px; width=100%; clear: both;" ></div>
<?php
    $l = 1;
    foreach ($prodTbl->getCategory($pdo) as $value) {
?>
            <form action="../crud.php" method="post">
                <input type="hidden" name="pageN" value="<?php echo $paginatorNew->pageN; ?>">
                <input type="hidden" name="on_page" value="<?php echo $newsPerPage; ?>">
                <input type="hidden" name="categ_numb" value="<?php echo $l; ?>">
                <input type="hidden" name="categ_id" value="<?php echo $value['id']; ?>">
                <input type="text" name="" value="<?php echo $l; ?>" style="width: 45px;" disabled>
                <input type="text" name="" value="<?php echo $value['id']; ?>" style="width: 45px;" disabled>
                <input class="input-left" type="text" id="categ_name<?php echo $l; ?>" name="categ_title" value="<?php up_categ_title($value['id'], $value['category']); ?>" style="width: 145px;" disabled>
                <input type="button" size="5" name="categ_edit" value="Change" style="width: 96px;" onclick="enableCategChg(<?php echo $l; ?>)">
                <input type="submit" id="<?php echo 'categOk'.$l; ?>" size="5" name="categ_change" value="Ok" style="width: 90px;" disabled>
                <input type="submit" size="5" name="categ_del" value="Del" style="width: 76px;">
                <div style="height: 0; width=100%; clear: both;" ></div>
            </form>
<?php
    $l++;
    }
?>
            <div style="height: 10px; width=100%; clear: both;" ></div>
            <div>
                <input id="openNewCateg" type="button" size="17" name="p_new" value="Добавить категорию"  onclick="newCateg()">
            </div>
            <div id="new_categ" style="display: none">
                <form action="../crud.php" method="post">
                    <input type="hidden" name="pageN" value="<?php echo $paginatorNew->pageN; ?>">
                    <input type="hidden" name="on_page" value="<?php echo $newsPerPage; ?>">
                    <input class="input-left" type="text" size="31" name="new_categ_title" value="<?php echo $new_categ_title; ?>">
                    <input type="reset" size="5" name="categ_res" value="Очистить" style="width: 78px;">
                    <input type="submit" size="5" name="categ_new" value="OK" style="width: 90px;">
                    <input type="button" size="13" name="categ_esc" value="Закрыть" style="width: 100px;" onclick="escapeCateg()">
                </form>
            </div>
            <div id="msg_categ" style="margin: 5px 0;">
<?php 
    echo $Msg_Categ;
    echo $categ_script;
?>
            </div>
            <div style="margin: 10px 0;">
                <h2>Товары и их описние</h2>
                <form method="get" action="admin.php">
                    <select size="1" name="on_page">
                        <option disabled selected>Товаров на странице</option>
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <input type="submit" value="OK">
                </form>
            </div>
            <div>
                <form>
                    <input type="text" size="7" name="n_p_num" value="№" disabled style="width: 45px;">
                    <input type="text" size="7" name="n_p_id" value="Код" disabled style="width: 45px;">
                    <input type="text" size="35" name="n_p_title" value="Название" disabled>
                    <input type="text" size="35" name="n_p_descr" value="Описание" style="width: 350px;" disabled>
                    <input type="text" size="7" name="n_p_price" value="Цена" disabled style="width: 80px;">
                    <input type="text" size="35" name="n_p_categ" value="Категория" disabled style="width: 130px;">
                    <input type="text" size="5" name="n_p_num" value="Изменить" disabled style="width: 96px;">
                    <input type="submit" size="5" name="n_p_subm" value="Сохранить" disabled style="width: 90px;">
                    <input type="button" size="5" name="n_p_del" value="Удалить" disabled style="width: 76px;">
                </form>
            </div>
            <div style="height: 5px; width=100%; clear: both;" ></div>
            <div>
<?php 
    $i = 1;
    foreach($prodTbl->getProducts($pdo) as $key => $value) {
        if ($i < $min || $i > $max) {
            $i++;
            continue;
        }
?>
            <form action="../crud.php" method="post">
                <input type="hidden" name="pageN" value="<?php echo $paginatorNew->pageN; ?>">
                <input type="hidden" name="on_page" value="<?php echo $newsPerPage; ?>">
                <input type="hidden" name="p_id" value="<?php echo $value['id']; ?>">
                <input type="hidden" name="p_num" value="<?php echo $i; ?>">
                <input type="text" size="7" name="" value="<?php echo $i; ?>" disabled style="width: 45px;">
                <input type="text" size="7" name="" value="<?php echo $value['id']; ?>" disabled style="width: 45px;">
                <input class="input-left" id="title<?php echo $i; ?>" type="text" size="35" name="p_title" value="<?php up_value('up_title', $value['id'], $value['title']); ?>" disabled>
                <input class="input-left" id="descr<?php echo $i; ?>" type="text" size="35" name="p_descr" value="<?php up_value('up_descr', $value['id'], $value['description']); ?>" style="width: 350px;" disabled>
                <input class="input-right" id="price<?php echo $i; ?>" type="text" size="10" name="p_price" value="<?php up_value('up_price', $value['id'], $value['price']); ?>" style="width: 80px;" disabled>
                <select id="categ<?php echo $i; ?>" type="text" size="" name="p_categ" style="width: 134px; display: inline-block; color: #a9a9a9; background-color: #ebebe4;" disabled>
<?php
    $val_id = $value['id'];
    $val_categ = $value['category'];
    foreach ($prodTbl->getCategory($pdo) as $key => $value) {
?>
                    <option value="<?php echo $value['category']; ?>" <?php selected_if_change($val_id, $val_categ, $value['category']); ?>><?php echo $value['category']; ?></option>
<?php
    }
?>
                </select>
                <input type="button" size="13" name="p_num" value="Change" style="width: 100px;" onclick="enableChg(<?php echo $i; ?>)">
                <input id="ok<?php echo $i; ?>" type="submit" size="5" name="p_subm" value="OK" style="width: 90px;" disabled>
                <input type="submit" size="5" name="p_del" value="DEL" style="width: 76px;">
            </form>
<?php
        UpProdMistake();
        $i++;    
    }
?>
        </div>
        <div style="height: 5px; width=100%; clear: both;"></div>
        <div style="margin: 10px 0">
<?php $paginatorNew->renderPaginator(); ?>
        </div>
        <div>
            <input id="openNew" type="button" size="17" name="p_new" value="Добавить новый товар"  onclick="newRow()">
        </div>
        <div id="new_row" style="display: none">
            <form action="../crud.php" method="post">
                <input type="hidden" name="pageN" value="<?php echo $paginatorNew->pageN; ?>">
                <input type="hidden" name="on_page" value="<?php echo $newsPerPage; ?>">
                <input type="text" size="7" name="new_num" value="" disabled style="width: 45px;">
                <input type="text" size="7" name="new_id" value="" disabled style="width: 45px;">
                <input class="input-left" type="text" size="35" name="new_title" value="<?php echo $new_title ?>">
                <input class="input-left" type="text" size="35" name="new_descr" value="<?php echo $new_descr ?>" style="width: 350px;">
                <input class="input-right" type="text" size="10" name="new_price" value="<?php echo $new_price ?>" style="width: 80px;">
                <select type="text" size="" name="new_categ" style="width: 134px;">
<?php
    foreach ($prodTbl->getCategory($pdo) as $key => $value) {
?>
                    <option value="<?php echo $value['category']; ?>"<?php selected($value['category']); ?>><?php echo $value['category']; ?></option>
<?php 
    } 
?>
                </select>
                <input type="reset" size="5" name="p_res" value="Очистить" style="width: 76px;">
                <input type="submit" size="5" name="p_new" value="OK" style="width: 90px;">
                <input type="button" size="13" name="p_esc" value="Закрыть" style="width: 100px;" onclick="escape()">
            </form>
            <p>ВНИМАНИЕ! Поле "название" обязательно к заполнению; название должно быть уникальным.</p>
        </div>
        <div id="message" style="margin: 5px 0;">
<?php print $Message; ?>
        </div>    
    </div>
</body>
</html>