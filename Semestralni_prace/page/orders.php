<section class="centeredContentWrapper" style="50%">
    <?php
    $controller = new MainController();
    $admin_controller = new AdminController();
    $shop_controller = new ShopController();
    // kontrola jestli je prihlasen
    $controller->checkIsLogged();
    $hasRights = false;
    // kontrola jestli jsou to jeho objednavky nebo to zobrazuje admin
    if(isset($_GET["user"])){
        if (!empty($_GET["user"])) {
           $hasRights = $controller->checkUserInOrder();
        }
    }
    // kontrola jestli ma nejake objednavky
    $hasOrders = $controller->checkOrdersByUser();

    if ($_SESSION["logged_user"]["role"] == 2) {
        // adminova sprava objednavek
        if (isset($_GET["action"]) && isset($_GET["id"])) {
            if ($_GET["action"] == "editOrdersByAdmin" && !empty($_GET["id"])) {
                $controller->getOrders($_GET["id"]);
            } else if ($_GET["action"] == "execute" && !empty($_GET["id"])) {
                $admin_controller->executeOrder($_GET["id"]);
            }
        } else {
            header("Location: /index.php?page=usersList");
        }
    }
    // zobrazeni objednavek uzivatele
        if ($hasRights) {
            if ($hasOrders) {
                // detail objednavky
                if (isset($_GET["action"]) && isset($_GET["id"])) {
                    if ($_GET["action"] == "detail" && !empty($_GET["id"])) {
                        $shop_controller->showDetailOfOrder($_GET["id"]);
                    }
                // vypis objednavek
                } else {
                    $controller->getOrders($_GET["user"]);
                }
            } else {
                // zadne objednavky zakaznik nema
                echo '<div class="empty_cart">
<label>Žádné objednávky</label>
<h3><a href="/index.php?page=products" class="payment-button">Pokračovat v nákupu</a></h3>
</div>';
            }
        }

    ?>
</section>