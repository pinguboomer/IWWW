<section class="centeredContentWrapper" style="width: 50%">
    <?php
    $controller = new MainController();
    $admin_controller = new AdminController();
    $controller->checkIsLogged();
    if ($_SESSION["role"] == 2) {
        echo '<div>';
        $admin_controller->listAllUsersInTable();
        echo '</div>';
    }
    ?>
</section>

