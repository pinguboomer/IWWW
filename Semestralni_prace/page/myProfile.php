<section class="centeredContentWrapper" style="width: 50%">
    <?php
    $controller = new MainController();
    $user_controller = new UserController();
    $admin_controller = new AdminController();
    $controller->checkIsLogged();
    if (isset($_GET["action"])) {
        if ($_SESSION["role"] == 2) {

            // editace uzivatele (jen admin)
            if ($_GET["action"] == "editAsAdmin" && !empty($_GET["id"])) {
                $admin_controller->editProfileAsAdmin($_GET["id"]);
            } else if ($_GET["action"] == "completeEditAsAdmin") {
                $admin_controller->completeEditProfileAsAdmin();
            }
        }

        // editace sveho profilu
        if ($_GET["action"] == "edit") {
            echo '<div style="width: 50%;margin: auto">';
            $user_controller->editProfile();
            echo '</div>';
        } else if ($_GET["action"] == "completeEdit" && !empty($_POST["submitEdit"])) {
            $user_controller->completeEditProfile();
        } else if ($_GET["action"] == "completeEdit" && !empty($_POST["backToProfile"])) {
            header("Location: /index.php?page=MyProfile");
        }
    } else {

        // zobrazeni sveho profilu
        $user_controller->showMyProfileInfo();
echo '<a href="/index.php?page=myProfile&action=edit" class="default-button">Upravit profil</a>';
        if ($_SESSION["UserHasAlreadyAddress"]) {
            echo '<a href="/index.php?page=myAddress" class="default-button">Zobrazit adresu</a>';
        } else {
            echo '<a href="/index.php?page=myAddress&action=add" class="default-button">Přidat adresu</a>';
        }
        echo '</div>';
    }

    ?>
</section>

