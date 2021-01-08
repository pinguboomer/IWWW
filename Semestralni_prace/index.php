<?php
function __autoload($class){ //zavola se pokazdy, kdyz najde novou tridu a nacte ji
    require_once "./classes/" . $class . '.php';
}
session_start();
ob_start(); // pro spravne nacteni stranky, nejdriv se nacte stranka do bufferu a pak az na obrazovku

?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Fotbal Shop</title>
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="products.css">
    <link rel="stylesheet" href="cart.css">
    <link rel="stylesheet" href="responsive.css">
    <link rel="stylesheet" href="menu.css">
    <link rel="stylesheet" href="form.css">
    <link rel="stylesheet" href="print.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon.png">
</head>
<body>
<?php
include "./page/header.php";
include "menu.php";
FlashMessages::displayAllMessages();
$_SESSION["sorted_by"] = "none";
if(isset($_GET["page"])) {
   if(preg_match("/^[a-z-A-Z-0-9-\.]+$/", $_GET["page"])) {
        $pathToFile = "./page/" . $_GET["page"] . ".php";
        if (file_exists($pathToFile)) {
            include $pathToFile;
       }
    }
}else {
    if(isset($_GET['picture'])){
        $image = $_GET['picture'];
        echo "<img class='fullImageFromIndexGallery' src='$image'>";
    } else {
    include "./page/main.php";
}
}
//TODO: stav objednavky misto odstranit objednavku, a vymazat sold a role, prejmenovat objednavka
include "footer.php";
?>
</body>
</html>