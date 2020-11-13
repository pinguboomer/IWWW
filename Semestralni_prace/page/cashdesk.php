<section class="centeredContentWrapper" style="width: 50%">
    <?php
    $controller = new BlogPostController();
    $controller->checkIsLogged();
    if (isset($_SESSION["cart"])) {
        if (empty($_SESSION["cart"])) {
            echo '<div class="empty_cart">
<label>Košík je prázdný</label>
<h3><a href="/index.php?page=products" class="payment-button">Pokračovat v nákupu</a></h3>
</div>';
        } else {
            // 2) vyplneni adresy
            if (isset($_GET["action"]) && isset($_POST)) {
                if ($_GET["action"] == "confirmAddress" && !empty($_POST["completeToTheAddress"])) {
                    echo '<div style="width: 50%; margin: auto; min-width: 300px">';
                    $controller->showConfirmAddress();
                    echo '</div>';
                }
                if ($_GET["action"] == "confirmAddress" && !empty($_POST["backToCart"])) {
                    header("Location: /index.php?page=shoppingCart");
                }
                if ($_GET["action"] == "completeOrder" && !empty($_POST["backToCashDesk"])) {
                    header("Location: /index.php?page=cashdesk");
                }
                // 3) potvrzeni objednavky
                if ($_GET["action"] == "completeOrder" && !empty($_POST["completeOrderPost"])) {
                    $controller->showConfirmOrder();
                }
                if ($_GET["action"] == "completeOrder" && !empty($_POST["backToTheAddress"])) {
                    $controller->showConfirmAddress();
                }
                if ($_GET["action"] == "completeOrder" && !empty($_POST["payment"])) {
                    $controller->completeOrder();
                }
            } else if (isset($_POST)) {
                if (!empty($_POST["payment"])) {
                    $controller->completeOrder();
                    exit;
                } else {

                    // 1) vyber zpusobu dodani a typu platby
                    if (isset($_SESSION["cart"])) {
                        $controller->showCashDesk();
                    }
                }
            }
        }
    }
    ?>
</section>
