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
        $passwordRegister = password_hash($_POST["passwordRegister"], PASSWORD_BCRYPT);
        if($controller->checkUniqueEmail($email) != null){
            echo "Uživatel již existuje";
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

<script>
    const inputFieldToValidate = document.getElementsByClassName("validate")
    for(const input of inputFieldToValidate){
        input.addEventListener("keyup", function (e){
            const value = e.target.value;
            if(value.length < 5){
                e.target.classList.add("error");
                e.target.classList.remove("valid");
            } else {
                e.target.classList.remove("error");
                e.target.classList.add("valid");
            }
        });
    }
</script>