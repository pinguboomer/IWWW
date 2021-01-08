<?php
if ($_POST) {
    if (empty($_POST["name"]) || empty($_POST["surname"]) ||
        empty($_POST["emailRegister"]) || empty($_POST["passwordRegister"])) {
        echo '<div class="errorLogs">Špatně vyplněné údaje!</div>';
    }
    else {
        $controller = new MainController();
        $user_controller = new UserController();
        $name = $_POST["name"];
        $surname = $_POST["surname"];
        $email = $_POST["emailRegister"];
        $passwordRegister = password_hash($_POST["passwordRegister"], PASSWORD_BCRYPT);
        if($user_controller->checkUniqueEmail($email) != null){
            echo '<div class="errorLogs">Uživatel již existuje!</div>';
        } else {
            $user_controller->addUser($name, $surname, $email, $passwordRegister, 1);
            echo '<div class="errorLogs">Registrace úspěšná!</div>';
        }
    }
}

?>
<section class="centeredContentWrapper" style="width: 400px">
    <form action="/index.php?page=register" method="post">
        <div class="row">
            <label>Jméno: (*)</label>
            <input name="name" type="text" id="name_reg">
        </div>
        <div class="row">
            <label>Přijmení: (*)</label>
            <input name="surname" type="text" id="surname_reg">
        </div>
        <div class="row">
            <label>Email: (*)</label>
            <input name="emailRegister" type="email" id="email_reg">
        </div>
        <div class="row">
            <label>Heslo: (*)</label>
            <input name="passwordRegister" type="password" id="password_reg">
        </div>
        <div class="row">
            <label></label>
            <input name="submitRegister" type="submit" value="Registrovat">
        </div>
    </form>
</section>
