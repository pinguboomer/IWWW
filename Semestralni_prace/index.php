<?php
function __autoload($class){ //zavola se pokazdy, kdyz najde novou tridu a nacte ji
    require_once "./classes/" . $class . '.php';
}
session_start();
ob_start(); // pro spravne nacteni stranky, nejdriv se nacte stranka do bufferu a pak az na obrazovku

//TODO: dodelat responzivitu
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Fotbal Shop</title>
    <link rel="stylesheet" href="headeer.css">
    <link rel="stylesheet" href="indexx.css">
    <link rel="stylesheet" href="product.css">
    <link rel="stylesheet" href="cart.css">
    <link rel="stylesheet" href="responsive.css">
    <link rel="stylesheet" href="menu.css">
    <link rel="stylesheet" href="formm.css">
    <link rel="stylesheet" href="print.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon.png">
</head>
<body>
<?php
include "./page/header.php";
include "menu.php";
FlashMessages::displayAllMessages(); // zobrazuje chybove hlasky TODO: predelat do lepsi podoby
$_SESSION["sorted_by"] = "none";
if(isset($_GET["page"])) {
    $pathToFile = "./page/" . $_GET["page"] . ".php";
    if (file_exists($pathToFile)) {
        include $pathToFile;
    }
}else {
    if(isset($_GET['picture'])){
        $image = $_GET['picture'];
        echo "<img class='fullImageFromIndexGallery' src='$image'>";
    } else {
    include "./page/main.php";
}
}
include "footer.php";
?>
</body>
</html>