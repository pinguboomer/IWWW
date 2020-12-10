<?php
$controller = new MainController();
$shop_controller = new ShopController();

echo '<section class="products_section">
<div class="categoryMenu">';
$shop_controller->showCategories();
echo '</div>
<div class="products_main">';
if (isset($_GET["action"])) {
    if ($_GET["action"] == "sort" && !empty($_GET["id"])) {
        $shop_controller->showSortByInCategory($_GET["id"]);
    }
    else if ($_GET["action"] != "detail") {
        $shop_controller->showSortBy();
    }
} else {
    $shop_controller->showSortBy();
}
$shop_controller->getSortedBy();
if (isset($_GET["action"])) {
     if ($_GET["action"] == "detail" && !empty($_GET["id"])) {
         $shop_controller->detail($_GET["id"]);
    } else if ($_GET["action"] == "sort" && !empty($_GET["id"])) {
        echo '<div id="blogPostList" class="centeredContentWrapper">';
         $shop_controller->showSortedItems($_GET["id"]);
        echo '</div>';
    }
} else {
    echo '<div id="blogPostList" class="centeredContentWrapper">';
    $shop_controller->showSortedItems(-1);
}
echo '</section>';



