<section class="centeredContentWrapper" style="width: 50%">
    <?php
    $controller = new BlogPostController();
    $controller->checkIsLogged();
    if ($_SESSION["role"] == 2) {
        echo '<div>';
        $controller->listAllUsersInTable();
        echo '</div>';
    }
    ?>
</section>

