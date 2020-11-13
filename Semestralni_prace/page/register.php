<?php

$validation["username"] = array();

if ($_POST) {
    if (empty($_POST["name"]) || empty($_POST["surname"]) ||
        empty($_POST["emailRegister"]) || empty($_POST["passwordRegister"])) {
        $validation["username"] = "Špatně vyplněné údaje";
        print_r($validation);
    }
    else {
        $controller = new BlogPostController();
        $name = $_POST["name"];
        $surname = $_POST["surname"];
        $email = $_POST["emailRegister"];
        $passwordRegister = $_POST["passwordRegister"];
        if($controller->checkUniqueEmail($email) != null){
            echo "Uživatel se stejným emailem už existuje!";
        } else {
            $controller->addUser($name, $surname, $email, $passwordRegister);
            echo "Registrace úspěšná!";
        }
    }
}

?>

<section class="centeredContentWrapper" style="width: 400px">
    <form action="/index.php?page=register" method="post">
        <div class="row">
            <label>Jméno: (*)</label>
            <input name="name" type="text">
        </div>
        <div class="row">
            <label>Přijmení: (*)</label>
            <input name="surname" type="text">
        </div>
        <div class="row">
            <label>Email: (*)</label>
            <input name="emailRegister" type="email">
        </div>
        <div class="row">
            <label>Heslo: (*)</label>
            <input name="passwordRegister" type="password">
        </div>
        <div class="row">
            <label></label>
            <input name="submitRegister" type="submit">
        </div>
    </form>
</section>