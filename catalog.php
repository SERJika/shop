<?php

require_once('config.php');
require_once('crud.php');
require_once('paginator2.php');

$prodTbl = new crud();
$pageName = 'catalog';
$newsPerPage = 3;
$paginatorProd = new paginator($prodTbl->countProd($pdo), $newsPerPage, $pageName);

function imgSmall($val) {
    !is_null($val) ? $image = $val : $image = 'box';
    echo $image;
}

$paginatorProd->pageN > 1 ? $firstElement = 1 + $newsPerPage * ($paginatorProd->pageN- 1) : $firstElement = 1;
$endElement = $firstElement + $newsPerPage - 1;
$i = 0;

foreach ($prodTbl->getProducts($pdo) as $key => $value) {
    $i++;
    if ($i < $firstElement || $i > $endElement){
        continue;
    }
?>


<div style='width:100%; background-color: #fafafa;'>
	<div class="product" style="display: inline-block; float: left; width: 248px; height:158px; margin: 20px; padding: 15px; border: solid grey 1px;">
	    <div class="prImg">
	        <img src="img/<?php imgSmall($value['img']); ?>.png" style="max-width: 50px; max-height: 50px;" />
    	</div>
	    <div class="prTitle">
	        <span style="display: inline-block; margin: 2px 0; width: 90px; font-weight: 700;">Название: </span>
    	    <?php echo $value['title']; ?>
    	</div>
    	
    	<div class="prPrice">
    	    <span style="display: inline-block; margin: 2px 0; width: 90px; font-weight: 700;">Цена: </span>
    	    <?php echo number_format($value['price'], 2, ',', ' '); ?>
    	    <span> руб.</span>
        </div>
        <div style="margin: 15px 0; text-align: right;">
            <a href="index.php?page=prod&pageID=<?php echo $value['id']; ?>">Подробнее</a>
        </div>
	</div>
<?php
}
?>
    <div style="height: 5px; width=100%; clear: both;"></div>
    <div>
<?php $paginatorProd->renderPaginator(); ?>
    </div>
</div>