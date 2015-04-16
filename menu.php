<?php

class Menu
{
    public $title;
    public $classMenu;
    protected $items = [];
    public function __construct($title, $classMenu, $items)
    {
        $this->title = $title;
        $this->classMenu = $classMenu;
        $this->items = $items;
    }
    public function render()
    {
?>
        <ul>
<?php
        foreach ($this->items as $key => $value) {
?>
            <li class="<?php echo $this->classMenu ?>">
                <a href="<?php echo $value['link'] ?>"><?php echo $value['title'] ?></a>
            </li>
<?php
        }
?>
        </ul>
<?php
    }
}

$itemsMain = [
    [
        'title' => 'О нас',
        'link' => '?page=about',
        'nick' => 'about',
    ],
    [
        'title' => 'Каталог',
        'link' => '?page=catalog',
        'nick' => 'catalog',
    ],
    [
        'title' => 'Как заказать',
        'link' => '?page=howbuy',
        'nick' => 'howbuy',
    ],
    [
        'title' => 'Контакты',
        'link' => '?page=contacts',
        'nick' => 'contacts',
    ],
];

$itemsTop = [
    [
        'title' => 'Войти',
        'link' => 'admin/admin.php',
    ],
    [
        'title' => 'Регистрация',
        'link' => 'admin/regist.php',
    ],
];

$cart = [
    [
        'title' => 'Корзина',
        'link' => '?page=cart',
        'nick' => 'cart',
    ],
];
