<section class="centeredContentWrapper" style="width: 50%">
    <?php
    $controller = new BlogPostController();
    $controller->checkIsLogged();
    if (isset($_GET["action"])) {

        // obsluha editace adresy (jen kdyz uz uzivatel ma adresu)
        if ($_GET["action"] == "edit") {
            if (isset($_SESSION["UserHasAlreadyAddress"])) {
                if (($_SESSION["UserHasAlreadyAddress"])) {
                    echo '<div style="width: 50%;margin: auto">';
                    $controller->editAddress();
                    echo '</div>';
                }
            }
        } if ($_GET["action"] == "completeEdit" && !empty($_POST["submitEditedAddress"])) {
            $controller->completeEditAddress();
        } else if ($_GET["action"] == "completeEdit" && !empty($_POST["backToProfileFromEdit"])) {
                header("Location: /index.php?page=MyAddress");

            // obsluha pridani adresy (jen kdyz uz uzivatel nema adresu)
        } else if ($_GET["action"] == "add") {
            if (isset($_SESSION["UserHasAlreadyAddress"])) {
                if (!($_SESSION["UserHasAlreadyAddress"])) {
                    $controller->showAddAddress();
                }
            }
        } else if ($_GET["action"] == "completeAdd" && !empty($_POST["submitAddress"])) {
            $controller->completeAddAddress();
        } else if ($_GET["action"] == "completeAdd" && !empty($_POST["backToProfile"])) {
            header("Location: /index.php?page=MyProfile");
        }
    } else {

        // zobrazeni adresy uzivatele
        $_SESSION["address"] = $controller->getAddressByEmail($_SESSION["email"]);
        echo '<div class="borders_top_and_down">';
        $controller->showAddress();
        echo '</div><div id="profile_buttons">
<a href="/index.php?page=myProfile" class="default-button">Zpět</a>
            <a href="/index.php?page=myAddress&action=edit" class="default-button">Upravit adresu</a>';
    }
    ?>
</section>
