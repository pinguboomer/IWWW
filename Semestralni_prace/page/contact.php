<?php

$servername = "localhost";
$username = "root";
$password = "password";
$db = "db_dev";
$validation["username"] = array();

if ($_POST) {
    if (empty($_POST["userName"])) {
        $validation["username"] = "Username je prázdný";
        print_r($validation);
    } else {
        try {
            $conn = new PDO("mysql:host=$servername;dbname=$db", $username, $password);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected successfully";

            $userName = $_POST["userName"];
            $email = $_POST["email"];
            $message = $_POST["message"];

            $stmt = $conn->prepare("INSERT INTO iwww (name, email, message)
 VALUES (:userName, :email, :message)");

            $stmt->bindParam(":userName", $userName);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":message", $message);

            $stmt->execute();
            echo "New record created successfully";
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }


    }
}

?>

<section class="centeredContentWrapper" style="width: 400px">
    <form action="/index.php?page=contact" method="post">
        <div class="row">
            <label>Jméno:</label>
            <input name="userName" type="text">
        </div>
        <div class="row">
            <label>Email:</label>
            <input name="email" type="email">
        </div>
        <div class="row">
            <label>Zpráva:</label>
            <textarea name="message"></textarea>
        </div>
        <div class="row">
            <label></label>
            <input name="submit" type="submit">
        </div>
    </form>
</section>
