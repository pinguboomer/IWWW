<section class="centeredContentWrapper" style="width: 80%">
    <?php
    $controller = new MainController();
    $admin_controller = new AdminController();
    $controller->checkIsLogged();
    if (isset($_GET["action"])) {
        if ($_SESSION["logged_user"]["role"] == 2) {
            // pridani noveho itemu (pouze admin)
            if ($_GET["action"] == "addItem") {
                $admin_controller->addNewItem();
            } else if ($_GET["action"] == "completeAddNewItem") {
                $admin_controller->completeAddNewItem();
            }
            // editace itemu podle idcka (pouze admin)
            if ($_GET["action"] == "editAsAdmin" && !empty($_GET["id"])) {
                $admin_controller->editProductByAdmin($_GET["id"]);
            } else if ($_GET["action"] == "completeEditProduct" && !empty($_GET["id"])) {
                $admin_controller->completeEditProduct($_GET["id"]);
            }

        }
    } else {
        if ($_SESSION["logged_user"]["role"] == 2) {
            // tabulka itemu + button pridat
            echo '<a href="/index.php?page=categoriesList" 
class="products_add_edit_buttons">Seznam kategorií</a>
<a href="/index.php?page=itemsList&action=addItem" 
class="products_add_edit_buttons">PŘIDAT NOVÝ PRODUKT</a>';
            $admin_controller->listAllInTable();
        }
    }


    ?>
</section>