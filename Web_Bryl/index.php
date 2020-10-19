<?php
session_start();

if(isset($_POST["password"]) == "123") {
        $_SESSION["isLogged"] = true;
        $_SESSION["loginTime"] = date("h:i:sa");
        if(isset($_POST["keepLogin"])) {
            setcookie("keepLogin", $_POST["keepLogin"], time() + (86400 * 30), "/");
        }
    echo "Přihlášeno";
    }
?>


<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Fotbalu zdar</title>
    <link rel="stylesheet" href="indexx.css">
    <link rel="stylesheet" href="responsivee.css">
    <link rel="stylesheet" href="menux.css">
    <link rel="stylesheet" href="form.css">
    <link rel="stylesheet" href="print.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon.png">
</head>
<body>

<?php
include "./page/header.php";
if(isset($_GET["page"])) {
    $pathToFile = "./page/" . $_GET["page"] . ".php";
    if (file_exists($pathToFile)) {
        include "menu.php";
        include $pathToFile;
    }
}else {
    if(isset($_GET['picture'])){
        include "menu.php";
        $image = $_GET['picture'];
        echo "<img class='plnyObrazek' src='$image'>";

    } else {
    include "menu.php";
    include "./page/main.php";
}
}
?>


<?php

?>
<footer>
	<div>
		<span class="footerHeading">Nejčtenější články</span>
		<ul>
			<li><a href="#">ABCD</a></li>
			<li><a href="#">ABCD</a></li>
			<li><a href="#">ABCD</a></li>
			<li><a href="#">ABCD</a></li>
		</ul>
	</div>
	<div>
		<span class="footerHeading">Nejčtenější články</span>
		<ul>
			<li><a href="#">ABCD</a></li>
			<li><a href="#">ABCD</a></li>
			<li><a href="#">ABCD</a></li>
			<li><a href="#">ABCD</a></li>
		</ul>
	</div>
	<div>
		<span class="footerHeading">Nejčtenější články</span>
		<ul>
			<li><a href="#">ABCD</a></li>
			<li><a href="#">ABCD</a></li>
			<li><a href="#">ABCD</a></li>
			<li><a href="#">ABCD</a></li>
		</ul>
	</div>
</footer>
</body>
</html>