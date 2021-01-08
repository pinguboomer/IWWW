<?php
if ($_POST) {
    if (empty($_POST["emailLogin"]) || empty($_POST["passwordLogin"])) {
        echo '<div class="errorLogs">Špatně vyplněné údaje!</div>';
    } else {
        $controller = new MainController();
        $user_controller = new UserController();
        $emailLogin = $_POST["emailLogin"];
        $passwordLogin = $_POST["passwordLogin"];
        if (!($user_controller->checkUniqueEmail($emailLogin))) {
            echo '<div class="errorLogs">Uživatel neexistuje!</div>';
        } else {
            $loggedUser = $user_controller->logUser($emailLogin);
            $passwordResult = password_verify($passwordLogin, $loggedUser["password"]);
            if(!$passwordResult){
                echo '<div class="errorLogs">Špatné heslo!</div>';
            }
            else {
                $_SESSION["logged_user"] = $loggedUser;
                $_SESSION["email"] = $_SESSION["logged_user"]["email"];
                $loggedRole = $user_controller->getRoleByUserId($loggedUser["id_user"]);
                $_SESSION["role"] = $loggedRole["role"];
                $_SESSION["isLogged"] = true;
                $_SESSION["loginTime"] = date("h:i:sa");
                $address = $user_controller->getAddressByEmail($_SESSION["email"]);
                if($address != null) {
                    $_SESSION["address"] = $user_controller->getAddressByEmail($_SESSION["email"]);
                    $_SESSION["UserHasAlreadyAddress"] = true;
                } else {
                    $_SESSION["UserHasAlreadyAddress"] = false;
                }
                $_SESSION["orders"];
                $_SESSION["cart"];
                $_SESSION["totalPrice"] = 0;
                header("Location: /index.php?page=products");
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
        <label></label>
        <input name="submitLogin" type="submit" value="Přihlásit">
    </div>
</form>
</section>
