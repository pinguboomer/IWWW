<?php
//TODO: Zustat prihlasen
echo '<div id="menu">';
   echo '<label for="hamburger">&#9776;</label>
    <input type="checkbox" id="hamburger"/>
    <nav>
        <div>
        <a href="index.php">Úvod</a>
        <a href="index.php?page=products">Produkty</a>
        <a href="index.php?page=contact">Kontakt</a>';
        if(isset($_SESSION["isLogged"])) {
            if($_SESSION["role"] == 2) {
                echo " ";
                echo '<a href="index.php?page=usersList" style="word-spacing: 0">Seznam&nbsp;uživatelů</a>
<a href="index.php?page=itemsList" style="word-spacing: 0">Seznam&nbsp;produktů</a>';
            }
    /*        echo 'Uživatel: ' . $_SESSION["name"] . ' ' . $_SESSION["surname"];
        if(isset($_SESSION["loginTime"])) {
            echo ' User logged at: ' . $_SESSION["loginTime"];
        }
            if(isset($_COOKIE["keepLogin"])){
            echo ', keep login? ' . $_COOKIE["keepLogin"];
            } */
            }
        echo '</div>
    </nav>
</div>';
?>