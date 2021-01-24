<section class="centeredContentWrapper" style="width: 80%">
    <?php
    $controller = new MainController();
    $admin_controller = new AdminController();
    $controller->checkIsLogged();
    if ($_SESSION["logged_user"]["role"] == 2) {
        echo '<div class="userTable">';
        $admin_controller->listAllUsersInTable();
        echo '</div>';
    }
    ?>
</section>

