<?php

class AdminController {

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
            $user_controller = new UserController();
            if (empty($_POST["nameEdit"])) {
                FlashMessages::error("Není vyplněno jméno");
            }
            if (empty($_POST["surnameEdit"])) {
                FlashMessages::error("Není vyplněno příjmení");
            }
            if (empty($_POST["emailEdit"])) {
                FlashMessages::error("Není vyplněn email");
            }
            if ($user_controller->checkUniqueEmail($_POST["emailEdit"]) != null &&
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