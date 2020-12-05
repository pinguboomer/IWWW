<?php

class ShopController {

    public function showDetailOfOrder($id)
    {
        echo '<table class="shopcarttable"><tr>
<th colspan="2">Produkt</th>
<th>Počet kusů</th>
<th>Celková cena</th></tr>';
        $order = EshopPostRepository::getOrderById($id);
        $item = EshopPostRepository::getItemsByOrderId($order["id_order"]);
        $addressId = EshopPostRepository::getAddressIdByOrderId($order["id_order"]);
        $address = EshopPostRepository::getAddressById($addressId["id_address"]);
        foreach ($item as $key => $value) {
            //$it = EshopPostRepository::getItemById($item[$key]["id_item"]);
            echo '<tr><td>
<a id="a_in_cart" href="/index.php?page=products&action=detail&id= ' . $item[$key]["id_item"] . '">
<img src="' . $item[$key]["image"] . '" alt="' . $item[$key]["name"] . '" width="40px" height="40px"></a></td>
<td><a id="a_in_cart" href="/index.php?page=products&action=detail&id= ' . $item[$key]["id_item"] . '">
<h3>' . $item[$key]["name"] . '</h3></a></td>
<td><h3>' . $item[$key]["quantity"] . '</h3></td>
<td><h3>' . $item[$key]["price"] . '</h3></td></tr>';
        }
        echo '</table><div style="text-align: center">';
        echo '<div style="width: 50%; margin: auto"><h3>Celková cena: ' . $order["total_price"] . ' Kč</h3>';
        echo '<h3>Způsob platby: ' . $order["typ_platby"] . '</h3>';
        echo '<h3>Způsob dodání: ' . $order["typ_dodani"] . '</h3>';
        echo '<div class="borders_top_and_down">';
        showAddressInDetailOrder($address);
        echo '</div><h3><a href="/index.php?page=orders" class="default-button" >Zpět</a></h3></div>';
    }

    public function showCategories()
    {
        $categories = EshopPostRepository::getAllCategories();
        foreach ($categories as $key => $value) {
            echo '<a href="/index.php?page=products&action=sort&id=' . $categories[$key]["id_category"] . '" 
class="category_button">' . $categories[$key]["name"] . '</a>';
        }
    }

    public function showSortedItems($id)
    {
        $itemsId = null;
        if ($id == -1) {
            switch ($_SESSION["sorted_by"]) {
                case "none":
                    $itemsId = EshopPostRepository::getAllItems();
                    break;
                case "nejlevnejsi":
                    $itemsId = EshopPostRepository::getAllBy("price", "asc");
                    break;
                case "nejdrazsi":
                    $itemsId = EshopPostRepository::getAllBy("price", "desc");
                    break;
                case "nejnovejsi":
                    $itemsId = EshopPostRepository::getAllBy("id_item", "desc");
                    break;
                case "nejstarsi":
                    $itemsId = EshopPostRepository::getAllBy("id_item", "asc");
                    break;
                case "nejoblibenejsi":
                    $itemsId = EshopPostRepository::getAllBy("read_counter", "desc");
                    break;
            }
            foreach ($itemsId as $item) {
                showProduct($item);
            }
        } else {
            switch ($_SESSION["sorted_by"]) {
                case "none":
                    $itemsId = EshopPostRepository::getAllItemsInCategoryBy($id, "id_item", "desc");
                    break;
                case "nejlevnejsi":
                    $itemsId = EshopPostRepository::getAllItemsInCategoryBy($id, "price", "asc");
                    break;
                case "nejdrazsi":
                    $itemsId = EshopPostRepository::getAllItemsInCategoryBy($id, "price", "desc");
                    break;
                case "nejnovejsi":
                    $itemsId = EshopPostRepository::getAllItemsInCategoryBy($id, "id_item", "desc");
                    break;
                case "nejstarsi":
                    $itemsId = EshopPostRepository::getAllItemsInCategoryBy($id, "id_item", "asc");
                    break;
                case "nejoblibenejsi":
                    $itemsId = EshopPostRepository::getAllItemsInCategoryBy($id, "read_counter", "desc");
                    break;
            }
            foreach ($itemsId as $item) {
                showProduct($item);
            }
        }
    }

    public function showSortBy()
    {
        echo '<form action="/index.php?page=products" method="post">
<div class="sorting_radios">
<div class="row_sort">
            <input type="radio" name="sorting_by" id="cheap" value="nejlevnejsi">
            <label for="cheap">Nejlevnější</label>
            </div>
            <div class="row_sort">
            <input type="radio" name="sorting_by" id="expensive" value="nejdrazsi">
            <label for="expensive">Nejdražší</label>           
            </div>
            <div class="row_sort">
            <input type="radio" name="sorting_by" id="newest" value="nejnovejsi">
            <label for="newest">Nejnovější</label>           
            </div>
            <div class="row_sort">
            <input type="radio" name="sorting_by" id="popular" value="nejoblibenejsi">
            <label for="popular">Nejoblíbenější</label>            
            </div>
            <input type="submit" name="submit_sort" value="Seřadit">
        </div>';
    }

    public function showSortByInCategory($id)
    {

        echo '<form action="/index.php?page=products&action=sort&id= ' . $id . '" method="post">
<div class="sorting_radios">
<div class="row_sort">
            <input type="radio" name="sorting_by" id="cheap" value="nejlevnejsi">
            <label for="cheap">Nejlevnější</label>
            </div>
            <div class="row_sort">
            <input type="radio" name="sorting_by" id="expensive" value="nejdrazsi">
            <label for="expensive">Nejdražší</label>           
            </div>
            <div class="row_sort">
            <input type="radio" name="sorting_by" id="newest" value="nejnovejsi">
            <label for="newest">Nejnovější</label>           
            </div>
            <div class="row_sort">
            <input type="radio" name="sorting_by" id="popular" value="nejoblibenejsi">
            <label for="popular">Nejoblíbenější</label>            
            </div>
            <input type="submit" name="submit_sort" value="Seřadit">
        </div>';
    }

    public function getSortedBy()
    {
        if (isset($_POST)) {
            if (!empty($_POST["sorting_by"])) {
                switch ($_POST["sorting_by"]) {
                    case "nejlevnejsi":
                        $_SESSION["sorted_by"] = "nejlevnejsi";
                        break;
                    case "nejdrazsi":
                        $_SESSION["sorted_by"] = "nejdrazsi";
                        break;
                    case "nejnovejsi":
                        $_SESSION["sorted_by"] = "nejnovejsi";
                        break;
                    case "nejoblibenejsi":
                        $_SESSION["sorted_by"] = "nejoblibenejsi";
                        break;
                }
            } else {
                $_SESSION["sorted_by"] = "none";
            }
        }
    }

    public function detail($id)
    {
        $item = EshopPostRepository::getOneById($id);
        if (!empty($item)) {
            echo '<div class="product">
<img src="' . $item["image"] . '" alt="' . $item["name"] . '" width="400" height="400">
<div class="product_info">
<h1 class="product_name">' . $item["name"] . '</h1>
<div class="borders_top_and_down">
<span class="product_price"> Cena za kus: ' . $item["price"] . ' Kč</span>
<div class="product_quantity"> Počet kusů na skladě: ' . $item["quantity"] . '</div></div>
<div class="product_quantity">
 <div class="product_q_label"> Počet:</div>
        <form action="/index.php?page=shoppingCart&action=add&id=' . $item["id_item"] . '" method="post">
            <input type="number" name="product_quantity" value="1" min="1" max="' . $item["quantity"] . '">
            <input type="hidden" name="product_id" value="' . $item["quantity"] . '">
            <input type="submit" value="Vložit do košíku"></div>
        </form>
        <div class="product_description">
            ' . $item["description"] . '
            </div>
        </div>
    <div>';
            EshopPostRepository::increaseReadCounter($id);
        }
    }

    //-----------------------------SHOPPING CART-------------------------------------------

    public function getItemById(int $id_item)
    {
        return EshopPostRepository::getItemById($id_item);
    }

    public function addItemToCart($item_id)
    {
        if (isset($_SESSION)) {
            $item = EshopPostRepository::getItemById($item_id);
            if (!array_key_exists($item_id, $_SESSION["cart"])) {
                if (!empty($_POST["product_quantity"])) {
                    if ($_SESSION["cart"][$item_id]["quantity"] + $_POST["product_quantity"] <= $item["quantity"]) {
                        $_SESSION["cart"][$item_id]["quantity"] = $_POST["product_quantity"];
                    }
                } else {
                    if ($_SESSION["cart"][$item_id]["quantity"] + 1 <= $item["quantity"]) {
                        $_SESSION["cart"][$item_id]["quantity"] = 1;
                    }
                }
            } else {
                if (!empty($_POST["product_quantity"])) {
                    if ($_SESSION["cart"][$item_id]["quantity"] + $_POST["product_quantity"] <= $item["quantity"]) {
                        $_SESSION["cart"][$item_id]["quantity"] = $_SESSION["cart"]
                            [$item_id]["quantity"] + $_POST["product_quantity"];
                    }
                } else {
                    if ($_SESSION["cart"][$item_id]["quantity"] + 1 <= $item["quantity"]) {
                        $_SESSION["cart"][$item_id]["quantity"]++;
                    }
                }
            }
        }

        header("Location: /index.php?page=shoppingCart");
    }

    public function getItemsToCart()
    {
        echo '<table class="shopcarttable"><tr>
<th colspan="2">Produkt</th>
<th>Cena za kus</th>
<th>Počet kusů v košíku</th>
<th>Celková cena</th>
<th></th>
<th></th>
<th></th></tr>';
        $_SESSION["totalPrice"] = 0;
        $_SESSION["totalQuantity"] = 0;
        foreach ($_SESSION["cart"] as $key => $value) {
            $item = self::getItemById($key);
            $_SESSION["totalPrice"] = $_SESSION["totalPrice"] + ($value["quantity"] * $item["price"]);
            $_SESSION["totalQuantity"] = $_SESSION["totalQuantity"] + $value["quantity"];
            echo '
<tr><td>
<a id="a_in_cart" href="/index.php?page=products&action=detail&id= ' . $item["id_item"] . '">
<img src="' . $item["image"] . '" alt="' . $item["name"] . '"></a></td>
<td>
<a id="a_in_cart" href="/index.php?page=products&action=detail&id= ' . $item["id_item"] . '" >
' . $item["name"] . '</a></td>
<td>
' . $item["price"] . ' Kč</td>
<td>
' . ($value["quantity"]) . '</td>
<td>
' . ($value["quantity"] * $item["price"]) . ' Kč</td>
<td>
<a href="/index.php?page=shoppingCart&action=add&id=' . $item["id_item"] . '" class="cart-buttonAdd">
Přidat kus</a></td>
<td>
<a href="/index.php?page=shoppingCart&action=remove&id=' . $item["id_item"] . '" class="cart-button">
Odebrat kus</a></td>
<td>
<a href="/index.php?page=shoppingCart&action=delete&id=' . $item["id_item"] . '" class="cart-button">
Odebrat z košíku</a></td></tr>';
        }
        echo '<tr><td colspan="3" style="border-bottom-color:transparent">Celkem:</td>
<td style="border-bottom-color:transparent">' . $_SESSION["totalQuantity"] . '</td>
<td style="border-bottom-color:transparent">' . $_SESSION["totalPrice"] . ' Kč</td>
<td colspan="3" style="border-bottom-color:transparent">
<h3>Celková cena: ' . $_SESSION["totalPrice"] . ' Kč</h3></td></tr></table>';
    }

    public function removeItemFromCart($id)
    {
        if (isset($_SESSION)) {
            if (array_key_exists($id, $_SESSION["cart"])) {
                if ($_SESSION["cart"][$id]["quantity"] <= 1) {
                    unset($_SESSION["cart"][$id]);
                    $_SESSION["totalPrice"] = 0;

                } else {
                    $_SESSION["cart"][$id]["quantity"]--;
                }
            }
        }
        header("Location: /index.php?page=shoppingCart");
    }

    public function deleteItemFromCart($id)
    {
        if (isset($_SESSION)) {
            unset($_SESSION["cart"][$id]);
            $_SESSION["totalPrice"] = 0;
        }
        header("Location: /index.php?page=shoppingCart");
    }
}