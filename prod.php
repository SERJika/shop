<?php

    require_once('crud.php');
    
    function img($img){
        !is_null($img) ? $image = $img : $image = 'box';
        return $image;
    }

    $prodTbl = new crud();
    if (isset($_GET['pageID'])) {
        $id = $_GET['pageID'];
    }
    $size = getimagesize('img/guffy.png');
    if ($size['0'] < 330) { 
        $height = $size['1'] + 20;
        $imgWidth = $size['0'];
    } else {
        $height = 330 * $size['1'] / $size['0'] + 20;
        $imgWidth = 330;
    }

    foreach ($prodTbl->getOneProd($pdo, $id) as $key => $value) {
    $size = getimagesize('img/' . img($value['img']) . '.png');
    if ($size['0'] < 330) { 
        $height = $size['1'] + 20;
        $imgWidth = $size['0'];
    } else {
        $height = 330 * $size['1'] / $size['0'] + 20;
        $imgWidth = 330;
    }
?>
<div style='position: relative; width:100%; min-height: <?php echo $height; ?>px; padding: 10px 0; background-color: #fafafa;'>
    
        <div style="position: absolute; top: 20px; left: 20px; width: 330px;">
            <img style="display: block; margin: 0 auto; width: <?php echo $imgWidth; ?>px;" src="img/<?php echo img($value['img']); ?>.png" />
        </div>
        <div style="margin: 0 10px 0 351px; padding: 15px; width: 543px;">
            
                <h1 style="font-weight: 700;">
                    <?php echo $value['title']; ?>
                </h1>
                <div>
                    <span style="display: inline-block; margin: 2px 0; width: 90px; font-weight: 700;">Описание: </span>
                    <?php echo $value['description']; ?>
                </div>
                <div>
                    <span style="display: inline-block; margin: 2px 0; width: 90px; font-weight: 700;">Цена: </span>
                    <?php echo number_format($value['price'], 2, ',', ' '); ?>
                </div>
<?php   
}   
?>  
                <form method="post">
                    <input type="hidden" name="ses_prod" value="<?php echo $_GET['pageID']; ?>">
                    <div>
                        <span style="display: inline-block; margin: 2px 0; width: 90px; font-weight: 700;">Кол-во: </span>
                        <input style="width: 50px;" type="number" min="1" name="numb" value="1">
                    </div>
                    <input style="margin-top: 35px;" type="submit" name="ses" value="В корзину" style="margin-top: 10px;">
                    
                    <div style="padding: 15px 0 5px 0; color: orange;">
                        <?php
                        if (!empty($_POST['ses_prod'])) {
                            $id_prod = 0 + $_POST['ses_prod'];
                            $num_prod = 0 + $_POST['numb'];
                            $_SESSION['id'][$id_prod] = $num_prod;
                            echo 'Товар успешно добавлен в корзину';
                        } ?>
                    </div>
                </form>
                <a href="?page=catalog">Назад в каталог</a>
            
        </div>
    
</div>