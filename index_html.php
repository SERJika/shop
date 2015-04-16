<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="js/accounting.js"></script>
    <title></title>
    <style type="text/css">
        ul {
            margin: 0;
            padding: 12px 0;
        }
        ul li {
            display: inline-block;
            margin: 0 0 0 20px;
            list-style-type: none;
        }
        ul li a {
            text-decoration: none;
        }
        .input-right {
        text-align: right;
        }
        .left_float,.example {
            display: inline-block; 
            float: left;
        }
        .example {
            width: 172px; 
            margin: 3px 0; 
            padding-left: 5px; 
            font-size: 12px; 
            color: #c3b6bb;
        }
        .top-nav {
            position: relative;
        }
        .mainMenu a {
            color: black;
            font-size: 19px;
        }
        li.cartMenu {
            margin-left: 137px;
        }
        .cart-counter {
            position: absolute;
            top: 8px;
            right: 575px;
            font-size: 15px;
        }
        .logo {
            position:absolute;
            top: 5px;
            right: 10px;
        }
        h1 {
            margin: 0;
        }
        td, th {
            padding: 5px;
            border: solid grey 1px;
        }
        
    </style>
</head>
<body>
    <div class="top">
    	<div class="top-wrapper" style="width: 960px; height: 60px; margin: 0 auto; background-color: #fefdc5;">
        	<div class="top-nav">
        	    <div class="left_float">
<?php $topMenu->render(); ?>
                </div>
            	<div class="left_float" class="cart">
<?php $cartMenu->render(); ?>
                </div>
            	<div class="cart-counter">(<?php echo $prodInCart ?>)</div>
            	<div class="logo">
    		        <p style="float: left; margin: 23px 15px 45px; font-size: 42px; line-height: 48px;">Купите у HAMSTOR</p>
    		        <a href="?page=about"><img src="img/hamster.png" style="width: 100px;" /></a>
    		    </div>
            </div>
	    </div>
    </div>
    <div style="clear: both;"></div>
    <header style="">
    	<div class="header-wrapper" style="width: 960px; margin: 0 auto; padding-bottom: 15px; background-color: #fefdc5;">
    		<nav>
<?php $mainMenu->render(); ?>
    		</nav>
    	</div>
    </header>
    <main>
        <div class="main-wrapper" style="width: 960px; margin: 0 auto;">
<?php require_once($getFile); ?>
        </div>
    </main>
    <div style="clear: both;"></div>
    <footer style="margin-top:30px;">
    	<div class="footer-wrapper" style="width: 960px; margin: 0 auto;">
    	    <div>______________________________</div>
    		<div style="margin: 10px 20px;">Copyright</div>
    	</div>
    </footer>
</body>
</html>