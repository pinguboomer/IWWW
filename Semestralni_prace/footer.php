<footer>
    <div>
        <span class="footerHeading">Nejoblíbenější produkty</span>
        <ul>
            <?php
            foreach (EshopPostRepository::getAllItemsSortedByReadCounter(5)  as $item) {
            echo '<li><a href="/index.php?page=products&action=detail&id='.$item["id_item"].'">' . $item["name"] . '</a></li>';}
            ?>
        </ul>
    </div>
    <div>
        <span class="footerHeading">Žhavé novinky</span>
        <ul>
            <?php
            foreach (EshopPostRepository::getAllItemsSortedByIdDesc(5) as $item) {
            echo '<li><a href="/index.php?page=products&action=detail&id='.$item["id_item"].'">' . $item["name"] . '</a></li>';}
            ?>
        </ul>
    </div>
    <div>
        <span class="footerHeading">Nejprodávanější produkty</span>
        <ul>
            <?php
            foreach (EshopPostRepository::getAllItemsSortedBySold(5)  as $item) {
                echo '<li><a href="/index.php?page=products&action=detail&id='.$item["id_item"].'">' . $item["name"] . '</a></li>';}
            ?>
        </ul>
    </div>
</footer>