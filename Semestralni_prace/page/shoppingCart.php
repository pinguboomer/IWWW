<section class="centeredContentWrapper">
    <?php
    $controller = new MainController();
    $shop_controller = new ShopController();

    $controller->checkIsLogged();

    if (isset($_GET["action"])) {
        if ($_GET["action"] == "add" && !empty($_GET["id"])) {
            $shop_controller->addItemToCart($_GET["id"]);
        }
        if ($_GET["action"] == "remove" && !empty($_GET["id"])) {
            $shop_controller->removeItemFromCart($_GET["id"]);
        }

        if ($_GET["action"] == "delete" && !empty($_GET["id"])) {
            $shop_controller->deleteItemFromCart($_GET["id"]);
        }
    }
    if (isset($_SESSION["cart"])) {
        if (empty($_SESSION["cart"])) {
            echo '<div class="empty_cart">
<label>Košík je prázdný</label>
<h3><a href="/index.php?page=products" class="payment-button">Pokračovat v nákupu</a></h3>
</div>';
        } else {
            $shop_controller->getItemsToCart();
            echo '<h3><a href="/index.php?page=cashdesk" class="payment-button">Přejít k platbě</a></h3>';
        }
    } else {
        echo '<div class="empty_cart">
<label>Košík je prázdný</label>
<h3><a href="/index.php?page=products" class="payment-button">Pokračovat v nákupu</a></h3>
</div>';
    }

    ?>
</section>