<section class="centeredContentWrapper" style="50%">
    <?php
    $controller = new MainController();
    $admin_controller = new AdminController();
    $shop_controller = new ShopController();
    $controller->checkIsLogged();
    $_SESSION["orders"] = $controller->getOrdersByUser();
    if ($_SESSION["role"] == 2) {

        // adminova sprava objednavek
        if (isset($_GET["action"]) && isset($_GET["id"])) {
            if ($_GET["action"] == "editOrdersByAdmin" && !empty($_GET["id"])) {
                $_SESSION["orders"] = $admin_controller->getOrdersByUserId($_GET["id"]);
                $controller->getOrders($_GET["id"]);
            } else if ($_GET["action"] == "execute" && !empty($_GET["id"])) {
                $admin_controller->executeOrder($_GET["id"]);
            }
        }
    }
    // zobrazeni objednavek uzivatele
    if (isset($_SESSION["orders"])) {
        if (isset($_GET["action"]) && isset($_GET["id"])) {

            // detail objednavky
            if ($_GET["action"] == "detail" && !empty($_GET["id"])) {
                $shop_controller->showDetailOfOrder($_GET["id"]);
            }
        } else if (!($_SESSION["orders"])) {
            echo '<div class="empty_cart">
<label>Žádné objednávky</label>
<h3><a href="/index.php?page=products" class="payment-button">Pokračovat v nákupu</a></h3>
</div>';
        } else {
            $controller->getOrders(-1);
        }
    } else {
        echo '<div class="empty_cart">
<label>Žádné objednávky</label>
<h3><a href="/index.php?page=products" class="payment-button">Pokračovat v nákupu</a></h3>
</div>';
    }

    ?>
</section>