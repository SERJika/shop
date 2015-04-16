<?php
require_once('config.php');


class paginator
{
    public $numberOfPages;
    public $newsPerPage;
    public $pageName;
    public $pageN;
    public function __construct($numberOfElements, $newsPerPage, $pageName)
    {
        $this->numberOfElements = $numberOfElements; // ==$number_of_goods из crud.php, подключенного в index.php
        $this->newsPerPage = $newsPerPage;
        $this->numberOfPages = ceil($this->numberOfElements / $this->newsPerPage);
        $this->pageName = $pageName;
        !isset($_GET['pageN']) ? $this->pageN = 1 : $this->pageN = $_GET['pageN'];
        if ($this->pageN > $this->numberOfPages || $this->pageN < 1) {
            echo '<script>location="' . BASE_DOMAIN . '404.php"</script>';
        }
    }
    public function renderPaginator()
    {
?>
            <table style="margin-left: -2px;">
                <tr>
<?php
        $j = 1;
        $this->pageN == 1 ? $disabled1 = 'onclick="return false;"' : $disabled1 = "";
        $this->pageN == $this->numberOfPages ? $disabled2 = 'onclick="return false;"' : $disabled2 = "";    
        for ($i = 1; $i <= $this->numberOfPages; $i++) {
            while ($j <= 1) {       //ссылка назад
?>
                    <td style="padding-top: 3px; border: 1px solid grey;">
                        <a style="margin-top: 0; padding: 3px 5px; text-decoration: none;" <?php echo $disabled1 ?> href="?page=<?php echo $this->pageName ?>&pageN=<?php echo ($this->pageN - 1) ?>&on_page=<?php echo $this->newsPerPage ?>"> << </a>
                    </td>
<?php
                $j++;
            }
            
            if ($i >= $this->pageN - 1 && $i <= $this->pageN + 1 ) {
                if ($i == $this->pageN) {
?>
                    <td style="border: 1px solid grey; padding: 0 5px;">
                        <a style="text-decoration: none; cursor: default; font-size: 20px;" href="?page=<?php echo $this->pageName ?>&pageN=<?php echo $i ?>&on_page=<?php echo $this->newsPerPage ?>"><?php echo $i ?></a>
                    </td>
<?php
                } else {
?>
                    <td style="border: 1px solid grey; padding: 0 5px;">
                        <a href="?page=<?php echo $this->pageName ?>&pageN=<?php echo $i ?>&on_page=<?php echo $this->newsPerPage ?>"><?php echo $i ?></a>
                    </td>
<?php
                }
            }
            
            if ($i == $this->numberOfPages) {       //ссылка вперед
?>
                    <td style="border: 1px solid grey; padding-top: 3px;">
                        <a style="margin-top: 0; padding: 3px 5px; text-decoration: none;" <?php echo $disabled2 ?> href="?page=<?php echo $this->pageName ?>&pageN=<?php echo ($this->pageN + 1) ?>&on_page=<?php echo $this->newsPerPage ?>"> >> </a>
                    </td>
<?php
            }
        }
?>
                </tr>
            </table>
<?php
    }
}