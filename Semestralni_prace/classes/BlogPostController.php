<?php


class BlogPostController
{
    public static function changeUserSession($name, $surname, $email)
    {
        $_SESSION["logged_user"]["name"] = $name;
        $_SESSION["logged_user"]["surname"] = $surname;
        $_SESSION["logged_user"]["email"] = $email;
    }

    public function listAllInTable()
    {
        $dataTable = new DataTable(EshopPostRepository::getAllItems());
        $dataTable->addColumn("id_item", "ID");
        $dataTable->addColumn("name", "Název");
        $dataTable->addColumn("price", "Cena");
        $dataTable->addColumn("quantity", "Počet zbývajících kusů");
        $dataTable->addColumn("read_counter", "Počet zobrazení");
        $dataTable->addColumn("description", "Popis");
        $dataTable->renderProducts();
    }

    //--------------------------LOGIN-----------------------------------

    public function checkUniqueEmail($email)
    {
        return EshopPostRepository::checkUniqueEmail($email);
    }

    public function addUser($name, $surname, $email, $password)
    {
        EshopPostRepository::insertUser($name, $surname, $email, $password);
        EshopPostRepository::insertUserRole($email);
    }

    public function logUser($emailLogin)
    {
        $userLog = EshopPostRepository::logUser($emailLogin);
        if (!empty($userLog)) {
            return $userLog;
        }
        return null;
    }

    public function checkIsLogged()
    {
        $isLogged = isset($_SESSION["isLogged"]);
        if (!$isLogged) {
            header("Location: /index.php?page=login");
        }
    }

    //--------------------------PROFILE----------------------------------

    public function showMyProfileInfo()
    {
        echo '<div class="borders_top_and_down"><div class="row-profile">
            <label>Jméno:</label>
            <label>' . $_SESSION["logged_user"]["name"] . '</label>
        </div>
        <div class="row-profile">
            <label>Příjmení:</label>
            <label>' . $_SESSION["logged_user"]["surname"] . '</label>
        </div>
        <div class="row-profile">
            <label>Email:</label>
            <label>' . $_SESSION["logged_user"]["email"] . '</label>
        </div></div>';
        echo '<div id="profile_buttons">';
    }

    public function getRoleByUserId($id_user)
    {
        return EshopPostRepository::getRoleByUserId($id_user);
    }

    public function editProfile()
    {
        echo '<form action="/index.php?page=myProfile&action=completeEdit" method="post">';
        showUsersDataInTable($_SESSION["logged_user"]["name"],
            $_SESSION["logged_user"]["surname"], $_SESSION["logged_user"]["email"]);
        echo '<div class="row">
            <input name="backToProfile" type="submit" style="width: 30%" value="Zpět">
            <label></label>
            <input name="submitEdit" type="submit" value="Potvrdit">
        </div> ';
        echo '</form>';
    }

    public function completeEditProfile()
    {
        $_SESSION["messages"] = array();

        if ($_POST) {
            if (empty($_POST["nameEdit"])) {
                FlashMessages::error("Není vyplněno jméno");
            }
            if (empty($_POST["surnameEdit"])) {
                FlashMessages::error("Není vyplněno příjmení");
            }

            if (FlashMessages::containsError()) {
                header("Location: /index.php?page=MyProfile&action=edit");
                exit;
            } else {

                $name = htmlspecialchars($_POST["nameEdit"]);
                $surname = htmlspecialchars($_POST["surnameEdit"]);
                $email = htmlspecialchars($_SESSION["email"]);
                EshopPostRepository::updateUser($name, $surname, $email);
                self::changeUserSession($name, $surname, $email);
                header("Location: /index.php?page=myProfile");
            }
        }
    }

    public function showAddAddress()
    {
        echo '<form action="/index.php?page=myAddress&action=completeAdd" method="post">';
        showEmptyAddressForm();
        echo '<div class="row">
            <input name="backToProfile" type="submit" style="width: 30%" value="Zpět">
            <label></label>
            <input name="submitAddress" type="submit" value="Přidat adresu">
        </div>';
        echo '</form>';
    }

    public function completeAddAddress()
    {
        $_SESSION["messages"] = array();

        if ($_POST) {
            if (empty($_POST["country"])) {
                FlashMessages::error("Není vyplněná země");
            }
            if (empty($_POST["city"])) {
                FlashMessages::error("Není vyplněné město");
            }
            if (empty($_POST["street"])) {
                FlashMessages::error("Není zadaná ulice");
            }
            if (empty($_POST["zipcode"])) {
                FlashMessages::error("Není zadané PSČ");
            }
            if (!(ctype_digit($_POST["zipcode"]))) {
                FlashMessages::error("Špatně zadané PSČ");
            }
            if (empty($_POST["phone"])) {
                FlashMessages::error("Není zadaný telefon");
            }

            if (FlashMessages::containsError()) {
                header("Location: /index.php?page=myAddress&action=add");
                exit;
            } else {
                $controller = new BlogPostController();
                $country = htmlspecialchars($_POST["country"]);
                $city = htmlspecialchars($_POST["city"]);
                $street = htmlspecialchars($_POST["street"]);
                $zipcode = htmlspecialchars($_POST["zipcode"]);
                $phone = htmlspecialchars($_POST["phone"]);
                $email = $_SESSION["email"];
                $controller->addAddressAndUser_Address($country, $city, $street, $zipcode, $phone, $email);
                $_SESSION["UserHasAlreadyAddress"] = true;
                header("Location: /index.php?page=myProfile");
            }
        }
    }

    public function addAddressAndUser_Address($country, $city, $street, $zipcode, $phone, $email)
    {
        EshopPostRepository::insertAddress($country, $city, $street, $zipcode, $phone);
        $user = EshopPostRepository::getUserByEmail($email);
        $address = EshopPostRepository::getAddressIdByData($country, $city, $street, $zipcode, $phone);
        EshopPostRepository::insertUserAddress($user["id_user"], $address["id_address"]);
    }

    public function getAddressByEmail($email)
    {
        $id_us = EshopPostRepository::getUserByEmail($email);
        $id_ad = EshopPostRepository::getAddressByIdUser($id_us["id_user"]);
        if ($id_ad != null) {
            return EshopPostRepository::getAddressById($id_ad["id_address"]);
        }
        return null;
    }

    public function editAddress()
    {
        echo '<form action="/index.php?page=myAddress&action=completeEdit" method="post">';
        showAddressFormWithUsersAddress();
        echo '<div class="row">
<input name="backToProfileFromEdit" type="submit" style="width: 30%" value="Zpět">
            <label></label>
            <input name="submitEditedAddress" type="submit" value="Potvrdit">
        </div> ';
        echo '</form>';
    }

    public function showAddress()
    {
        echo '<div class="row-profile">
            <label>Země:</label>
            <label>' . $_SESSION["address"]["country"] . '</label>
        </div>
        <div class="row-profile">
            <label>Město:</label>
            <label>' . $_SESSION["address"]["city"] . '</label>
        </div>
        <div class="row-profile">
            <label>Ulice:</label>
            <label>' . $_SESSION["address"]["street"] . '</label>
        </div>
        <div class="row-profile">
            <label>PSČ:</label>
            <label>' . $_SESSION["address"]["zipcode"] . '</label>
        </div>
        <div class="row-profile">
            <label>Mobil:</label>
            <label>' . $_SESSION["address"]["phone"] . '</label>
        </div>';
    }

    public function completeEditAddress()
    {
        $_SESSION["messages"] = array();

        if ($_POST) {
            if (empty($_POST["country"])) {
                FlashMessages::error("Není vyplněná země");
            }
            if (empty($_POST["city"])) {
                FlashMessages::error("Není vyplněné město");
            }
            if (empty($_POST["street"])) {
                FlashMessages::error("Není zadaná ulice");
            }
            if (empty($_POST["zipcode"])) {
                FlashMessages::error("Není zadané PSČ");
            }
            if (!(ctype_digit($_POST["zipcode"]))) {
                FlashMessages::error("Špatně zadané PSČ");
            }
            if (empty($_POST["phone"])) {
                FlashMessages::error("Není zadaný telefon");
            }

            if (FlashMessages::containsError()) {
                header("Location: /index.php?page=myAddress&action=edit");
                exit;
            } else {
                $controller = new BlogPostController();
                $country = htmlspecialchars($_POST["country"]);
                $city = htmlspecialchars($_POST["city"]);
                $street = htmlspecialchars($_POST["street"]);
                $zipcode = htmlspecialchars($_POST["zipcode"]);
                $phone = htmlspecialchars($_POST["phone"]);
                $email = $_SESSION["email"];
                $controller->updateAddress($country, $city, $street, $zipcode,
                    $phone, $_SESSION["address"]["id_address"]);
                header("Location: /index.php?page=myAddress");
            }
        }
    }

    private function updateAddress($country, $city, $street, int $zipcode, $phone, $id_address)
    {
        EshopPostRepository::updateAddress($country, $city, $street, $zipcode, $phone, $id_address);
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

    //-----------------------------CASHDESK------------------------------------------

    public function showCashDesk()
    {
        echo '<form action="/index.php?page=cashdesk&action=confirmAddress" method="post">
        <div class="row-profile">
            <label>Způsob platby:</label>
            <div class="typeofpayment">
            <div class="row-cashdesk">
            <input type="radio" name="zpusob_platby" value="Dobírka" checked="checked">
            <label>Dobírka</label>
            </div>
            <div class="row-cashdesk">
            <input type="radio" name="zpusob_platby" value="Platba kartou">
            <label>Platba kartou</label>
            </div>
            <div class="row-cashdesk">
            <input type="radio" name="zpusob_platby" value="Bankovní převod">
            <label>Bankovní převod</label>
            </div>
        </div>
        </div>
        <div class="borders_top_and_down" style="border-bottom: none">
        <div class="row-profile">
            <label>Způsob dodání:</label>
            <div class="typeofpayment">
            <div class="row-cashdesk">
            <input type="radio" name="zpusob_dodani" value="Osobní odběr" checked="checked">
            <label>Osobní odběr</label></div>
            <div class="row-cashdesk">
            <input type="radio" name="zpusob_dodani" value="Česká pošta">
            <label>Česká pošta</label></div>
            <div class="row-cashdesk">
            <input type="radio" name="zpusob_dodani" value="PPL">
            <label>PPL</label></div>
            <div class="row-cashdesk">
            <input type="radio" name="zpusob_dodani" value="DPD">
            <label>DPD</label></div>
            </div>
            </div>
        </div>        
        <div class="row" style="justify-content: center">
            <input style="max-width: 200px" name="backToCart" type="submit" value="Zpět do košíku">
            <label></label>
            <input style="max-width: 200px" name="completeToTheAddress" type="submit" value="Pokračovat na adresu">
        </div>
    </form>';
    }

    public static function showConfirmAddress()
    {
        if (isset($_POST)) {
            if (!empty($_POST["zpusob_platby"])) {
                $_SESSION["zpusob_platby"] = $_POST["zpusob_platby"];
                $_SESSION["zpusob_dodani"] = $_POST["zpusob_dodani"];
            }
        }
        echo '<form action="/index.php?page=cashdesk&action=completeOrder" method="post">
<div class="row">
            <label>Platba:</label>
            <label>' . $_SESSION["zpusob_platby"] . '</label>
        </div>
        <div class="row">
            <label>Dodání:</label>
            <label>' . $_SESSION["zpusob_dodani"] . '</label>
        </div> ';
        if (isset($_SESSION["UserHasAlreadyAddress"])) {
            if (!($_SESSION["UserHasAlreadyAddress"])) {
                showEmptyAddressForm();
            } else {
                showAddressFormWithUsersAddress();
            }
        }
        echo '<div class="row">
            <input name="backToCashDesk" type="submit" value="Zpět">
            <input name="completeOrderPost" type="submit" value="Dokončit objednávku">
        </div></form>';
    }

    public function showConfirmOrder()
    {
        if (!checkValidityAddress()) {
            self::showConfirmAddress();
            FlashMessages::displayAllMessages();
        } else {
            showOrderedItems();
            echo '<div style="width: 50%; margin: auto; min-width: 400px">';
            echo '<form action="/index.php?page=cashdesk&action=completeOrder" method="post">
<div class="row">
            <label>Platba:</label>
            <label>' . $_SESSION["zpusob_platby"] . '</label>
        </div>
        <div class="row">
            <label>Dodání:</label>
            <label>' . $_SESSION["zpusob_dodani"] . '</label>
        </div> ';
            showAddressFormWithUsersAddressInOrder();
            echo '<div class="row">
            <input name="backToTheAddress" type="submit" value="Zpět">
            <input name="payment" type="submit" value="ZAPLATIT">
        </div></form></div>';
        }
    }

    public function completeOrder()
    {
        EshopPostRepository::insertOrder($_SESSION["totalPrice"],
            $_SESSION["zpusob_platby"], $_SESSION["zpusob_dodani"], date("Y-m-d"));
        EshopPostRepository::insertAddress($_SESSION["order_country"],
            $_SESSION["order_city"], $_SESSION["order_street"],
            $_SESSION["order_zipcode"], $_SESSION["order_phone"]);
        $user = EshopPostRepository::getUserByEmail($_SESSION["email"]);
        $address = EshopPostRepository::getAddressIdByData($_SESSION["order_country"],
            $_SESSION["order_city"], $_SESSION["order_street"],
            $_SESSION["order_zipcode"], $_SESSION["order_phone"]);
        $order = EshopPostRepository::getOrderIdByData($_SESSION["totalPrice"],
            $_SESSION["zpusob_platby"], $_SESSION["zpusob_dodani"]);
        EshopPostRepository::insertUserOrder($user["id_user"], $order["id_order"]);
        EshopPostRepository::insertOrderAddress($order["id_order"], $address["id_address"]);

        foreach ($_SESSION["cart"] as $key => $value) {
            $item = EshopPostRepository::getItemById($key);
            EshopPostRepository::insertOrderItem($order["id_order"], $item["id_item"],
                $item["name"], $item["image"], $item["price"] * $value["quantity"],
                $value["quantity"]);
            $new_quantity = $item["quantity"] - $value["quantity"];
            EshopPostRepository::updateItem($item["id_item"], $new_quantity, $item["sold"] + 1);
        }
        unset($_SESSION["cart"]);
        $_SESSION["totalPrice"] = 0;
        header("Location: /index.php?page=orders");
    }

    //--------------------------ORDERS----------------------------------------------

    public function getOrders($id)
    {
        echo '<table class="shopcarttable">
<tr><th>Číslo objednávky</th>
<th>Objednáno dne</th>
<th>Zaplaceno částkou</th>';
        if ($_SESSION["role"] == 2) {
            echo '<th></th><th></th></tr>';
        } else {
            echo '<th></th></tr>';
        }
        if ($id == -1) {
            $user = EshopPostRepository::getUserByEmail($_SESSION["email"]);
        } else {
            $user = EshopPostRepository::getUserById($id);
        }
        $orderId = EshopPostRepository::getOrdersByUserId($user["id_user"]);
        foreach ($orderId as $k => $value) {
            $order = EshopPostRepository::getOrderById($orderId[$k]["id_order"]);
            echo '<tr><td>
                <h3> ' . $order["id_order"] . '</h3></td>
                <td><h3>' . $order["date_of_order"] . '</h3></td>
                <td><h3>' . $order["total_price"] . ' Kč</h3></td>
            <td><h3><a href="/index.php?page=orders&action=detail&id=' . $orderId[$k]["id_order"] . '
" class="payment-button" >Detail objednávky</a></h3></td>';
            if ($_SESSION["role"] == 2) {
                echo '<td><h3><a href="/index.php?page=orders&action=delete&id=' . $orderId[$k]["id_order"] . '
" class="payment-button" >Odstranit objednávku</a></h3></td></tr>';
            } else {
                echo '</tr>';
            }
        }
        echo '</table>';
    }

    public function getOrdersByUser()
    {
        $user = EshopPostRepository::getUserByEmail($_SESSION["email"]);
        $orderId = EshopPostRepository::getOrdersByUserId($user["id_user"]);
        if (empty($orderId)) {
            return false;
        }
        return true;
    }

    //--------------------------PRODUCTS-----------------------------------------------

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


    //--------------------------ADMIN-----------------------------------------------

    public function editProfileAsAdmin($id)
    {
        echo '<form action="/index.php?page=myProfile&action=completeEditAsAdmin" method="post">';
        $editedUser = EshopPostRepository::getUserById($id);
        showUsersDataInTableAsAdmin($editedUser);
        echo '<div class="row">
            <label></label>
            <input name="submitEdit" type="submit" value="Potvrdit">
        </div> ';
        echo '</form>';
    }

    public function completeEditProfileAsAdmin()
    {
        $_SESSION["messages"] = array();

        if ($_POST) {
            if (empty($_POST["nameEdit"])) {
                FlashMessages::error("Není vyplněno jméno");
            }
            if (empty($_POST["surnameEdit"])) {
                FlashMessages::error("Není vyplněno příjmení");
            }
            if (empty($_POST["emailEdit"])) {
                FlashMessages::error("Není vyplněn email");
            }
            if ($this->checkUniqueEmail($_POST["emailEdit"]) != null &&
                $_POST["emailEdit"] != $_SESSION["edited_user"]["email"]) {
                FlashMessages::error("Email je již zabrán!");
            }
            if (FlashMessages::containsError()) {
                header('Location: /index.php?page=MyProfile&action=editAsAdmin&id='
                    . $_SESSION["edited_user"]["id_user"] . '');
                exit;
            } else {
                $name = htmlspecialchars($_POST["nameEdit"]);
                $surname = htmlspecialchars($_POST["surnameEdit"]);
                $email = htmlspecialchars($_POST["emailEdit"]);
                EshopPostRepository::updateUserById($_SESSION["edited_user"]["id_user"], $name, $surname, $email);
                header("Location: /index.php?page=usersList");
            }
        }
    }

    public function editProductByAdmin($id)
    {
        $_SESSION["edited_product"] = EshopPostRepository::getItemById($id);
        echo '<div class="add_product_form">
        <form action="/index.php?page=itemsList&action=completeEditProduct&id=' . $id . '" method="post" 
        enctype="multipart/form-data">
        <div class="row">
<label>Název:</label>           
        <input type="text" name="name" placeholder="Název" value="' . $_SESSION["edited_product"]["name"] . '">
        </div>  
        <div class="row">
<label>Obrázek:</label>
        <input type="file" name="newImage" placeholder="Obrázek">
        <img src="' . $_SESSION["edited_product"]["image"] . '" width="50px" height="50px">
        </div>
        <div class="row">
<label>Popis:</label>
        <textarea name="description" placeholder="popis...">' . $_SESSION["edited_product"]["description"] . '</textarea>
        </div>
        <div class="row">
<label>Cena:</label>
        <input name="price" type="number" value="' . $_SESSION["edited_product"]["price"] . '" min="0"</input>
        </div>
        <div class="row">
<label>Počet kusů na skladě:</label>
        <input name="quantity" type="number" value="' . $_SESSION["edited_product"]["quantity"] . '" min="0" </input>
        </div>
        <div class="row">
<label>Kategorie:</label>
<select name="category" id="categ">';
        $categories = EshopPostRepository::getAllCategories();
        foreach ($categories as $key => $value) {

            echo '<option value="' . $categories[$key]["id_category"] . '">
            ' . $categories[$key]["name"] . '</option>';
        }
        echo '</select>
        </div>
        <div class="row">
<label></label>
        <input name="submitConfirmEdit" type="submit" value="Potvrdit">
        </div>
        </form></div>';
    }

    public function completeEditProduct($id)
    {
        $_SESSION["messages"] = array();

        if ($_POST) {
            $isNewImage = true;
            if (empty($_POST["name"])) {
                FlashMessages::error("Není vyplněn nadpis");
            }
            if (empty($_POST["description"])) {
                FlashMessages::error("Není vyplněn text");
            }
            if (empty($_FILES["newImage"]["name"])) {
                $isNewImage = false;
                // FlashMessages::error("Není vybrán obrázek");
            }

            if (FlashMessages::containsError()) {
                header('Location: /index.php?page=itemsList&action=editAsAdmin&id=' .
                    $_SESSION["edited_product"]["id"] . '');
                exit;
            }

            if (!empty($_SESSION["messages"])) {
                print_r($_SESSION["messages"]);
                return;
            }
            if ($isNewImage) {
                $pathToFile = FileUpload::upload("./img/", "newImage");
                echo $pathToFile;
            } else {
                $pathToFile = $_SESSION["edited_product"]["image"];
            }

            if (EshopPostRepository::updateProduct($id, $pathToFile, htmlspecialchars($_POST["name"]),
                htmlspecialchars($_POST["description"]),
                $_POST["price"], $_POST["quantity"])) {
                EshopPostRepository::updateItemCategory($id, $_POST["category"]);
                header("Location: /index.php?page=itemsList");
            }
            exit;
        }
    }

    public function getOrdersByUserId($id)
    {
        $orderId = EshopPostRepository::getOrdersByUserId($id);
        if (empty($orderId)) {
            return false;
        }
        return true;
    }

    public function deleteOrder($id)
    {
        EshopPostRepository::deleteUserOrder($id);
        EshopPostRepository::deleteOrderItem($id);
        $address = EshopPostRepository::getAddressIdByOrderId($id);
        EshopPostRepository::deleteOrderAddress($id);
        EshopPostRepository::deleteObjednavka($id);
        EshopPostRepository::deleteAddress($address["id_address"]);
        header("Location: /index.php?page=usersList");
    }

    public function completeAddNewItem()
    {
        $_SESSION["messages"] = array();

        if ($_POST) {
            if (empty($_POST["name"])) {
                FlashMessages::error("Není vyplněn název");
            }
            if (empty($_POST["description"])) {
                FlashMessages::error("Není vyplněn text");
            }
            if (empty($_FILES["image"]["name"])) {
                FlashMessages::error("Není vybrán obrázek");
            }

            if (FlashMessages::containsError()) {
                header("Location: /index.php?page=itemsList&action=addItem");
                exit;
            }

            if (!empty($_SESSION["messages"])) {
                print_r($_SESSION["messages"]);
                return;
            }

            $pathToFile = FileUpload::upload("./img/", "image");
            echo $pathToFile;

            if (EshopPostRepository::insertProduct($pathToFile, $_POST["name"], $_POST["description"],
                $_POST["price"], $_POST["quantity"])) {
                $newIt = EshopPostRepository::getItemByData($pathToFile, $_POST["name"], $_POST["description"],
                    $_POST["price"], $_POST["quantity"]);
                EshopPostRepository::insertItemCategory($newIt["id_item"], $_POST["category"]);
                header("Location: /index.php?page=itemsList");
            }
            exit;
        }

    }

    public function addNewItem()
    {
        echo '<div class="add_product_form">
        <form method="post" action="/index.php?page=itemsList&action=completeAddNewItem" 
        enctype="multipart/form-data">
        <div class="row">
<label>Název:</label>           
        <input type="text" name="name" placeholder="Název">
        </div>  
        <div class="row">
<label>Obrázek:</label>
        <input type="file" name="image" placeholder="Obrázek">
        </div>
        <div class="row">
<label>Popis:</label>
        <textarea name="description" placeholder="popis..."></textarea>
        </div>
        <div class="row">
<label>Cena:</label>
        <input name="price" type="number" value="0" min="0"</input>
        </div>
        <div class="row">
<label>Počet kusů na skladě:</label>
        <input name="quantity" type="number" value="0" min="0" </input>
        </div>
        <div class="row">
<label>Kategorie:</label>
<select name="category" id="categ">';
        $categories = EshopPostRepository::getAllCategories();
        foreach ($categories as $key => $value) {

            echo '<option value="' . $categories[$key]["id_category"] . '">
            ' . $categories[$key]["name"] . '</option>';
        }
        echo '</select>
        </div>
        <div class="row">
<label></label>
        <input name="submit" type="submit">
        </div>
        </form></div>';
    }

    public function listAllUsersInTable()
    {
        $dataTable = new DataTable(EshopPostRepository::getAllUsers());
        $dataTable->addColumn("id_user", "ID");
        $dataTable->addColumn("name", "Jméno");
        $dataTable->addColumn("surname", "Příjmení");
        $dataTable->addColumn("email", "Email");
        //  $dataTable->addColumn("password", "Heslo");
        $dataTable->renderUsers();
    }


}

//--------------------FUNCTIONS----------------------------

function showUsersDataInTable($name, $surname, $email)
{
    echo ' <div class="row">
            <label>Jméno: (*)</label>
            <input name="nameEdit" type="text" value="' . $name . '">
        </div>
        <div class="row">
            <label>Přijmení: (*)</label>
            <input name="surnameEdit" type="text" value="' . $surname . '">
        </div>
        <div class="row">
            <label>Email: (*)</label>
            <label>' . $email . '</label>
        </div>';
}

function showUsersDataInTableAsAdmin($editedUser)
{
    $_SESSION["edited_user"] = $editedUser;
    echo ' <div class="row">
            <label>Jméno: (*)</label>
            <input name="nameEdit" type="text" value="' . $editedUser["name"] . '">
        </div>
        <div class="row">
            <label>Přijmení: (*)</label>
            <input name="surnameEdit" type="text" value="' . $editedUser["surname"] . '">
        </div>
        <div class="row">
            <label>Email: (*)</label>
            <input name="emailEdit" type="text" value="' . $editedUser["email"] . '">
        </div>';
}

function showEmptyAddressForm()
{
    echo '<div class="row">
            <label>Země: (*)</label>
            <input style="min-width: 100px" name="country" type="text">
        </div>
        <div class="row">
            <label>Město: (*)</label>
            <input style="min-width: 100px" name="city" type="text">
        </div>
        <div class="row">
            <label>Ulice: (*)</label>
            <input style="min-width: 100px" name="street" type="text">
        </div>
        <div class="row">
            <label>PSČ: (*)</label>
            <input style="min-width: 100px" name="zipcode" type="text">
        </div>
        <div class="row">
            <label>Mobil: (*)</label>
            <input style="min-width: 100px" name="phone" type="text">
        </div>';
}

function showAddressFormWithUsersAddress()
{
    echo '<div class="row">
            <label>Země: (*)</label>
            <input style="min-width: 100px" name="country" type="text" value="' . $_SESSION["address"]["country"] . '">
        </div>
        <div class="row">
            <label>Město: (*)</label>
            <input style="min-width: 100px" name="city" type="text" value="' . $_SESSION["address"]["city"] . '">
        </div>
        <div class="row">
            <label>Ulice: (*)</label>
            <input style="min-width: 100px" name="street" type="text" value="' . $_SESSION["address"]["street"] . '">
        </div>
        <div class="row">
            <label>PSČ: (*)</label>
            <input style="min-width: 100px" name="zipcode" type="text" value="' . $_SESSION["address"]["zipcode"] . '">
        </div>
        <div class="row">
            <label>Mobil: (*)</label>
            <input style="min-width: 100px" name="phone" type="text" value="' . $_SESSION["address"]["phone"] . '">
        </div>';
}

function showAddressFormWithUsersAddressInOrder()
{
    echo '<div class="row">
            <label>Země:</label>
            <label>' . htmlspecialchars($_POST["country"]) . '</label>
        </div>
        <div class="row">
            <label>Město:</label>
            <label>' . htmlspecialchars($_POST["city"]) . '</label>
        </div>
        <div class="row">
            <label>Ulice:</label>
            <label>' . htmlspecialchars($_POST["street"]) . '</label>
        </div>
        <div class="row">
            <label>PSČ:</label>
            <label>' . htmlspecialchars($_POST["zipcode"]) . '</label>
        </div>
        <div class="row">
            <label>Mobil:</label>
            <label>' . htmlspecialchars($_POST["phone"]) . '</label>
        </div>
        <div class="row">
            <label>Email:</label>
            <label>' . $_SESSION["email"] . '</label>
        </div>';
}

function showAddressInDetailOrder($address)
{
    echo '<div class="row-profile">
            <label>Země:</label>
            <label>' . $address["country"] . '</label>
        </div>
        <div class="row-profile">
            <label>Město:</label>
            <label>' . $address["city"] . '</label>
        </div>
        <div class="row-profile">
            <label>Ulice:</label>
            <label>' . $address["street"] . '</label>
        </div>
        <div class="row-profile">
            <label>PSČ:</label>
            <label>' . $address["zipcode"] . '</label>
        </div>
        <div class="row-profile">
            <label>Mobil:</label>
            <label>' . $address["phone"] . '</label>
        </div>
        <div class="row-profile">
            <label>Email:</label>
            <label>' . $_SESSION["email"] . '</label>
        </div>';
}

function showOrderedItems()
{
    echo '<table class="shopcarttable"><tr>
<th colspan="2">Produkt</th>
<th>Cena za kus</th>
<th>Počet kusů v košíku</th>
<th>Celková cena</th></tr>';
    $_SESSION["totalPrice"] = 0;
    foreach ($_SESSION["cart"] as $key => $value) {
        $item = EshopPostRepository::getItemById($key);
        $_SESSION["totalPrice"] = $_SESSION["totalPrice"] + ($value["quantity"] * $item["price"]);
        echo '
<tr><td>
<img src="' . $item["image"] . '" alt="' . $item["name"] . '" width="40px" height="40px"></td>
<td>
' . $item["name"] . '</td>
<td>
' . $item["price"] . ' Kč</td>
<td>
' . ($value["quantity"]) . '</td>
<td>
' . ($value["quantity"] * $item["price"]) . ' Kč</td></tr>';
    }
    echo '<tr><td colspan="3" style="border-bottom-color:transparent">Celkem:</td>
<td style="border-bottom-color:transparent">' . $_SESSION["totalQuantity"] . '</td>
<td style="border-bottom-color:transparent">' . $_SESSION["totalPrice"] . ' Kč</td></tr></table>';
    echo '<div class="borders_top_and_down">
<h3 style="text-align: center">Celková cena: ' . $_SESSION["totalPrice"] . ' Kč</h3></div>';
}

function checkValidityAddress()
{
    $_SESSION["messages"] = array();

    if ($_POST) {
        if (empty($_POST["country"])) {
            FlashMessages::error("Není vyplněná země");
        }
        if (empty($_POST["city"])) {
            FlashMessages::error("Není vyplněné město");
        }
        if (empty($_POST["street"])) {
            FlashMessages::error("Není zadaná ulice");
        }
        if (empty($_POST["zipcode"])) {
            FlashMessages::error("Není zadané PSČ");
        }
        if (!(ctype_digit($_POST["zipcode"]))) {
            FlashMessages::error("Špatně zadané PSČ");
        }
        if (empty($_POST["phone"])) {
            FlashMessages::error("Není zadaný telefon");
        }

        if (FlashMessages::containsError()) {
            return false;
        } else {
            $_SESSION["order_country"] = htmlspecialchars($_POST["country"]);
            $_SESSION["order_city"] = htmlspecialchars($_POST["city"]);
            $_SESSION["order_street"] = htmlspecialchars($_POST["street"]);
            $_SESSION["order_zipcode"] = htmlspecialchars($_POST["zipcode"]);
            $_SESSION["order_phone"] = htmlspecialchars($_POST["phone"]);
            return true;
        }
    }
}

function showProduct($item)
{
    if ($item["quantity"] > 0) {
        echo '<article class="blogPostPreview">
<a href="/index.php?page=products&action=detail&id=' . $item["id_item"] . '">
<img src="' . $item["image"] . '" alt="' . $item["name"] . '"></a>
<a href="/index.php?page=products&action=detail&id=' . $item["id_item"] . '">
' . $item["name"] . '</a>
<p>' . $item["price"] . ' Kč</p>
<a href="/index.php?page=shoppingCart&action=add&id=' . $item["id_item"] . '"
 class="buy-button"> VLOŽIT DO KOŠÍKU</a>
            </article>';
    }
}

?>


