<?php

require_once('../crud.php'); 
require_once('check.php');
require_once('../paginator2.php');

isset($_GET['on_page']) ? $newsPerPage = $_GET['on_page'] : $newsPerPage = 3;
$prodTbl = new crud();
$paginatorNew = new paginator($prodTbl->countProd($pdo), $newsPerPage, '');
$paginatorNew->pageN == 1 ? $min = 1 : $min = ($paginatorNew->pageN - 1) * $newsPerPage + 1;
$max = $min + $newsPerPage - 1;

if (isset($_SESSION['form']['new_categ_title'])) {
    $categ_script = "
        <script>
            document.getElementById('new_categ').style.display = 'block';
            document.getElementById('openNewCateg').style.display = 'none'; 
        </script>
        ";
} elseif (isset($_SESSION['form']['up_categ_title'])) {
    $l = $_SESSION['form']['up_categ_numb'];
    $categ_script = "
        <script>
            document.getElementById('categ_name" . $l . "').removeAttribute('disabled');
            document.getElementById('categOk" . $l . "').removeAttribute('disabled');
        </script>
        ";
} else {
    $categ_script = "";
}

function up_categ_title($val_1, $val_2) {
    if (isset($_SESSION['form']['up_categ_title']) && $val_1 == $_SESSION['form']['up_categ_id']) {
        echo $_SESSION['form']['up_categ_title'];
    } else {
        echo $val_2;
    }
}

isset($_SESSION['form']['new_categ_title']) ? $new_categ_title = $_SESSION['form']['new_categ_title'] : $new_categ_title = '';

if (isset($_SESSION['form']['errMsg_Categ'])) {
    $Msg_Categ = '<p style="color: red;">' . $_SESSION['form']['errMsg_Categ'] . '</p>';
} elseif (isset($_SESSION['form']['Ok_Categ'])) {
    $Msg_Categ = '<p style="color: green;">' . $_SESSION['form']['Ok_Categ'] . '</p>';
} else {
    $Msg_Categ = '';
}

function up_value($val_0, $val_1, $val_2) {
    isset($_SESSION['form']['up_id']) && $val_1 == $_SESSION['form']['up_id'] ? $up_value = $_SESSION['form'][$val_0] : $up_value = $val_2;
    echo $up_value;
}

function UpProdMistake() {
    if (isset($_SESSION['form']['up_id'])) {
        $i = $_SESSION['form']['up_num'];
        echo $UpProdMistake = "
            <script>
                document.getElementById('title" . $i . "').removeAttribute('disabled'); 
                document.getElementById('descr" . $i . "').removeAttribute('disabled');
                document.getElementById('price" . $i . "').removeAttribute('disabled');
                document.getElementById('categ" . $i . "').removeAttribute('disabled');
                document.getElementById('ok" . $i . "').removeAttribute('disabled');
                document.getElementById('categ" . $i . "').style.color = '#000000';
                document.getElementById('categ" . $i . "').style.backgroundColor = '#ffffff';
            </script>
            ";
    } 
}

function selected_if_change($val_1, $val_2, $val_3) {
    if (isset($_SESSION['form']['up_id']) && $val_1 == $_SESSION['form']['up_id'] && $val_2 == $val_3 || $val_2 == $val_3) {
        echo $selected = ' selected';
    }
}

function selected($val) {
    if (isset($_SESSION['form']['new_categ']) && $val == $_SESSION['form']['new_categ']) {
        echo $selected = ' selected';
    }
}

isset($_SESSION['form']['new_title']) ? $new_title = $_SESSION['form']['new_title'] : $new_title = '';
isset($_SESSION['form']['new_descr']) ? $new_descr = $_SESSION['form']['new_descr'] : $new_descr = '';
isset($_SESSION['form']['new_price']) ? $new_price = $_SESSION['form']['new_price'] : $new_price = '';

if (isset($_SESSION['form']['okMsg'])) { 
    $Message = '
        <script>
            document.getElementById("message").style.color = "green"; 
        </script>
        ';
    $Message .= $_SESSION['form']['okMsg'];
} elseif (isset($_SESSION['form']['errMsg'])) { 
    $Message = '
        <script>
            document.getElementById("new_row").style.display = "block";
            document.getElementById("openNew").style.display = "none"; 
            document.getElementById("message").style.color = "red"; 
        </script>
        '; 
    $Message .= $_SESSION['form']['errMsg'];
} elseif (isset($_SESSION['form']['errMsg_update'])) {
    $Message = '
        <script>
            document.getElementById("message").style.color = "red"; 
        </script>
        ';
    $Message .= $_SESSION['form']['errMsg_update'];
} else {
    $Message = '';
}

include_once('../admin/admin_html.php');
 
    unset($_SESSION['form']);
?>