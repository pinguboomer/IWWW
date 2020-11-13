<header>
    <div class="hero">
        <a href="index.php?page=products">
        <img src="/img/favicon.png" height="85px" width="70px">
            <h3 class="pageHeading">Fotbal Shop</h3></a>
    </div><div id="log_and_cart">
        <div class="log">
        <?php
        //TODO: udelat responzivne
        if(isset($_SESSION["isLogged"])){
            if($_SESSION["isLogged"]){
                echo'              
            <a href="index.php?page=myProfile">
        <img src="/img/login_pic.png" height="60px" width="60px">
        <h3 class="logheader">Můj profil</h3></a></div>
        
        <div class="log" style="margin-left: 30px">
<a href="index.php?page=orders">
<img src="/img/orders_icon.png" height="60px" width="60px">
<h3 class="logheader">Moje objednávky</h3></a></div>
<div class="log" style="margin-left: 30px">
<a href="index.php?page=shoppingCart">
<img src="/img/shopcart.jpg">
<h3 class="logheader_cart">'. $_SESSION["totalPrice"].' Kč</h3></a></div>
</div>
<div class="logout"> 
<a href="index.php?page=logout">
<img src="/img/logout_icon.png" height="60px" width="60px">
<h3 class="logheader">Odhlásit</h3></a></div>';
            } else {
                echo'
<div id="log_and_cart">
<div class="log">
<a href="index.php?page=login">
        <img src="/img/login_pic.png" height="60px" width="60px">
        <h3 class="logheader">Přihlásit</h3><a href="index.php?page=login"></a></div>
        <div class="log">
        <a href="index.php?page=register">
        <img src="/img/register_icon.png" height="60px" width="60px">
        <h3 class="logheader">Registrace</h3></a></div></div></div>';
            }
        } else {
            echo'
<div id="log_and_cart">
<div class="log" style="margin-left: 30px">
<a href="index.php?page=login">
        <img src="/img/login_pic.png" height="60px" width="60px">
        <h3 class="logheader">Přihlásit</h3><a href="index.php?page=login"></a></div>
        <div class="log" style="margin-left: 30px">
        <a href="index.php?page=register">
        <img src="/img/register_icon.png" height="60px" width="60px">
        <h3 class="logheader">Registrace</h3></a></div></div></div>';
        }
        ?>
</header>
