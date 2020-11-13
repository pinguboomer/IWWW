<?php
if ($_POST) {
    if (empty($_POST["emailLogin"]) || empty($_POST["passwordLogin"])) {
        $validation["username"] = "Špatně vyplněné údaje";
        print_r($validation);
    } else {
        $controller = new BlogPostController();
        $emailLogin = $_POST["emailLogin"];
        $passwordLogin = $_POST["passwordLogin"];
        if (!($controller->checkUniqueEmail($emailLogin))) {
            echo "Uživatel neexistuje!";
        } else {
            $loggedUser = $controller->logUser($emailLogin, $passwordLogin);
            if ($loggedUser == null) {
                echo "Špatné heslo!";
            } else {
                $_SESSION["logged_user"] = $loggedUser;
                $_SESSION["email"] = $_SESSION["logged_user"]["email"];
                $loggedRole = $controller->getRoleByUserId($loggedUser["id_user"]);
                $_SESSION["role"] = $loggedRole["id_role"];
                $_SESSION["isLogged"] = true;
                $_SESSION["loginTime"] = date("h:i:sa");
                $address = $controller->getAddressByEmail($_SESSION["email"]);
                if($address != null) {
                    $_SESSION["address"] = $controller->getAddressByEmail($_SESSION["email"]);
                    $_SESSION["UserHasAlreadyAddress"] = true;
                } else {
                    $_SESSION["UserHasAlreadyAddress"] = false;
                }
                $_SESSION["orders"];
                $_SESSION["cart"];
                $_SESSION["totalPrice"] = 0;
                if (isset($_POST["keepLogin"])) {
                    setcookie("keepLogin", $_POST["keepLogin"], time() + (86400 * 30), "/");
                }
                header("Location: /index.php");
            }
        }
    }
}
?>

<section class="centeredContentWrapper" style="width: 400px">
<form action="index.php?page=login" method="post">
    <div class="row">
        <label>Email:</label>
        <input name="emailLogin" type="email">
    </div>
    <div class="row">
        <label>Heslo:</label>
        <input name="passwordLogin" type="password">
    </div>
    <div class="row">
        <label>Zůstat přihlášen:</label>
        <input name="keepLogin" type="checkbox">
    </div>
    <div class="row">
        <label></label>
        <input name="submitLogin"  type="submit">
    </div>
</form>
</section>