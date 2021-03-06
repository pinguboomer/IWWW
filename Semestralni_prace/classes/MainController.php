<?php


class MainController
{
    public function checkIsLogged()
    {
        $isLogged = isset($_SESSION["isLogged"]);
        if (!$isLogged) {
            header("Location: /index.php?page=login");
            exit;
        }
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
        echo '<div style="width: 50%; margin: auto; min-width: 300px">
<form action="/index.php?page=cashdesk&action=completeOrder" method="post">
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
        </div></form></div>';
    }

    public function showConfirmOrder()
    {
        if (!checkValidityAddress()) {
            FlashMessages::displayAllMessages();
            self::showConfirmAddress();
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
        OrderRepository::insertOrder($_SESSION["totalPrice"],
            $_SESSION["zpusob_platby"], $_SESSION["zpusob_dodani"], date("Y-m-d"));
        UserRepository::insertAddress($_SESSION["order_country"],
            $_SESSION["order_city"], $_SESSION["order_street"],
            $_SESSION["order_zipcode"], $_SESSION["order_phone"]);
        $user = UserRepository::getUserByEmail($_SESSION["logged_user"]["email"]);
        $address = UserRepository::getAddressIdByData($_SESSION["order_country"],
            $_SESSION["order_city"], $_SESSION["order_street"],
            $_SESSION["order_zipcode"], $_SESSION["order_phone"]);
        $order = OrderRepository::getOrderIdByData($_SESSION["totalPrice"],
            $_SESSION["zpusob_platby"], $_SESSION["zpusob_dodani"]);
        OrderRepository::insertUserOrder($user["id_user"], $order["id_order"]);
        OrderRepository::insertOrderAddress($order["id_order"], $address["id_address"]);

        foreach ($_SESSION["cart"] as $key => $value) {
            $item = EshopPostRepository::getItemById($key);
            OrderRepository::insertOrderItem($order["id_order"], $item["id_item"],
                $item["name"], $item["image"], $item["price"] * $value["quantity"],
                $value["quantity"]);
            $new_quantity = $item["quantity"] - $value["quantity"];
            EshopPostRepository::updateItem($item["id_item"], $new_quantity, $item["sold"] + 1);
        }
        unset($_SESSION["cart"]);
        $_SESSION["totalPrice"] = 0;
        header('Location: /index.php?page=orders&user='.$_SESSION["logged_user"]["id_user"] . '');
    }

    //--------------------------ORDERS----------------------------------------------

    public function getOrders($id)
    {
        echo '<div class="cbSortOrders">
        <label for="myCheck">Zobrazit jen nevyřízené:</label> 
        <input type="checkbox" id="myCheck" onclick="showExecutedFunction()">
            </div>
        <table id="orderTable" class="shopcarttable">
        <thead>
        <tr><th>Číslo objednávky</th>
        <th>Objednáno dne</th>
        <th>Zaplaceno částkou</th>
        <th>Stav</th><th></th></tr></thead>';
        // zjistim vsechna id objednavek uzivatele
        $orders = OrderRepository::getOrdersByUserId($id);
        foreach ($orders as $key => $value) {
            // zjistim objednavku podle id objednavky
            $order = OrderRepository::getOrderById($orders[$key]["id_order"]);
            echo '<tr><td data-label="Číslo objednávky">
                <h3> ' . $order["id_order"] . '</h3></td>
                <td data-label="Objednáno dne"><h3>' . $order["date_of_order"] . '</h3></td>
                <td data-label="Zaplaceno částkou"><h3>' . $order["total_price"] . ' Kč</h3></td>';
                if($order["executed"] == 1){
                echo '<td data-label="Stav"><h3>Vyřízeno</h3></td>';
            } else {
                if ($_SESSION["logged_user"]["role"] == 2) {
                    echo '<td><h3><a href="/index.php?page=orders&action=execute&id=' . $order["id_order"] . '
" class="payment-button" style="background-color: maroon" >Označit za vyřízenou</a></h3></td>';
                } else {
                    echo '<td data-label="Stav"><h3>Čeká na vyřízení</h3></td>';
                }
            }
            echo '<td><h3>
<a href="/index.php?page=orders&user=' . $id . '&action=detail&id=' . $order["id_order"] . '"
 class="payment-button" >Detail objednávky</a></h3></td></tr>';
        }
        echo '</table>';
    }

    public function checkOrdersByUser()
    {
        $orderId = OrderRepository::getOrdersByUserId($_GET["user"]);

        if (empty($orderId)) {
            return false;
        }
        return true;
    }

    public function checkUserInOrder()
    {
        if (isset($_SESSION["logged_user"]["role"])) {
            if ($_SESSION["logged_user"]["role"] == 2) {
                return true;
            } else {
                if($_GET["user"] == $_SESSION["logged_user"]["id_user"]){
                    return true;
                }
            }
        }
        return false;
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
    echo '<div style="width: 50%; margin: auto; min-width: 300px"> <div class="row">
  
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
    echo '<div style="width: 50%; margin: auto; min-width: 300px"><div class="row">
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
            <label>' . $_SESSION["logged_user"]["email"] . '</label>
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
            <label>' . $_SESSION["logged_user"]["email"] . '</label>
        </div>';
}

function showOrderedItems()
{
    echo '<table class="shopcarttable"><thead><tr>
<th colspan="2">Produkt</th>
<th>Cena za kus</th>
<th>Počet kusů v košíku</th>
<th>Celková cena</th></tr></thead>';
    $_SESSION["totalPrice"] = 0;
    foreach ($_SESSION["cart"] as $key => $value) {
        $item = EshopPostRepository::getItemById($key);
        $_SESSION["totalPrice"] = $_SESSION["totalPrice"] + ($value["quantity"] * $item["price"]);
        echo '
<tr><td>
<img src="' . $item["image"] . '" alt="' . $item["name"] . '" width="40px" height="40px"></td>
<td>
' . $item["name"] . '</td>
<td data-label="Cena za kus">
' . $item["price"] . ' Kč</td>
<td data-label="Počet">
' . ($value["quantity"]) . '</td>
<td data-label="Celková cena">
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

<script>
    function showExecutedFunction() {
        var checkBox = document.getElementById("myCheck");
        var table, tr, td, i;
        table = document.getElementById("orderTable");
        tr = table.getElementsByTagName("tr");
        if (checkBox.checked == true){
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[3];
                if (td) {
                    if (td.textContent == "Čeká na vyřízení" || td.textContent == "Označit za vyřízenou") {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
        }
        } else {
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[3];
                if (td) {
                        tr[i].style.display = "";
                }
            }
        }
    }
</script>

