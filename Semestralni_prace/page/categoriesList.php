<section class="centeredContentWrapper" style="width: 50%">
    <?php
    $controller = new MainController();
    $admin_controller = new AdminController();
    $controller->checkIsLogged();
    if (isset($_GET["action"])) {
        if ($_SESSION["logged_user"]["role"] == 2) {
            // pridani noveho itemu (pouze admin)
            if ($_GET["action"] == "add") {
                $admin_controller->addNewCategory();
            } if ($_GET["action"] == "delete" && !empty($_GET["id"])) {
                $admin_controller->deleteCategory($_GET["id"]);
            }
            // editace itemu podle idcka (pouze admin)
            if ($_GET["action"] == "edit" && !empty($_GET["id"])) {
                $admin_controller->editCategoryByAdmin($_GET["id"]);
            } else if ($_GET["action"] == "completeEditCategory" && !empty($_GET["id"])) {
                $admin_controller->completeEditCategory($_GET["id"]);
            }

        }
    } else {
        if ($_SESSION["logged_user"]["role"] == 2) {
            $admin_controller->listAllCategoriesInTable();
            echo '<div id="profile_buttons">
<a href="/index.php?page=itemsList" class="default-button">Zpět</a>
<form action="index.php?page=categoriesList&action=add" method="post">
    <div class="row">
        <input name="newCategory" type="text">
        <input name="submitNewCategory" type="submit" value="Přidat kategorii">
        </div>
</form></div>
            ';
        }
    }
    ?>
</section>