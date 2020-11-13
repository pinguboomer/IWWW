<?php
$controller = new BlogPostController();
echo '<section class="products_section">';
   echo '<div class="categoryMenu">';
$controller->showCategories();
echo '</div>
<div class="products_main">';
if (isset($_GET["action"])) {
    if ($_GET["action"] == "sort" && !empty($_GET["id"])) {
        $controller->showSortByInCategory($_GET["id"]);
    }
    else if ($_GET["action"] != "detail") {
        $controller->showSortBy();
    }
} else {
    $controller->showSortBy();
}
$controller->getSortedBy();
if (isset($_GET["action"])) {
     if ($_GET["action"] == "detail" && !empty($_GET["id"])) {
        $controller->detail($_GET["id"]);
    } else if ($_GET["action"] == "sort" && !empty($_GET["id"])) {
        echo '<div id="blogPostList" class="centeredContentWrapper">';
        $controller->showSortedItems($_GET["id"]);
        echo '</div>';
    }
} else {
    echo '<div id="blogPostList" class="centeredContentWrapper">';
    $controller->showSortedItems(-1);
}
?>
</nav>
</div>
</div>
</section>

