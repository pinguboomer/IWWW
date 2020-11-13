<div id="menu">
    <div></div>
    <label for="hamburger">&#9776;</label>
    <input type="checkbox" id="hamburger"/>
    <nav>
        <a href="index.php">Úvod</a>
        <a href="index.php?page=contact">Kontakt</a>
        <?php
        if(isset($_SESSION["isLogged"])) {
            echo '<a href="index.php?page=messageList">Zprávy</a>';
            echo '<a href="index.php?page=logout">Odhlásit</a>';
        if(isset($_SESSION["loginTime"])) {
            echo 'User logged at: ' . $_SESSION["loginTime"];
        }
            if(isset($_COOKIE["keepLogin"])){
            echo ', keep login? ' . $_COOKIE["keepLogin"];
            }
            } else {
                echo '<a href="index.php?page=login">Přihlásit</a>';
            }

        ?>
    </nav>
</div>
