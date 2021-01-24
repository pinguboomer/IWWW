<?php

class AdminController {

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

    public function listAllCategoriesInTable()
    {
        $dataTable = new DataTable(EshopPostRepository::getAllCategories());
        $dataTable->addColumn("id_category", "ID");
        $dataTable->addColumn("name", "Název");
        $dataTable->addColumn("description", "Popis");
        $dataTable->renderCategories();
    }

    public function editProfileAsAdmin($id)
    {
        echo '<form action="/index.php?page=myProfile&action=completeEditAsAdmin&id=' . $id . '" method="post">';
        $editedUser = UserRepository::getUserById($id);
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
            $user = UserRepository::getUserById($_GET["id"]);
            if ($user_controller->checkUniqueEmail($_POST["emailEdit"]) != null &&
                $_POST["emailEdit"] != $user["email"]) {
                FlashMessages::error("Email je již zabrán!");
            }
            if (FlashMessages::containsError()) {
                header('Location: /index.php?page=MyProfile&action=editAsAdmin&id='
                    . $_POST["id_user"]. '');
                exit;
            } else {
                $name = htmlspecialchars($_POST["nameEdit"]);
                $surname = htmlspecialchars($_POST["surnameEdit"]);
                $email = htmlspecialchars($_POST["emailEdit"]);
                UserRepository::updateUserById($_GET["id"], $name, $surname, $email);
                header("Location: /index.php?page=usersList");
            }
        }
    }

    public function editProductByAdmin($id)
    {
        $item = EshopPostRepository::getItemById($id);
        echo '<div class="add_product_form">
        <form action="/index.php?page=itemsList&action=completeEditProduct&id=' . $id . '" method="post" 
        enctype="multipart/form-data">
        <div class="row">
<label>Název:</label>           
        <input type="text" name="name" placeholder="Název" value="' . $item["name"] . '">
        </div>  
        <div class="row">
<label>Obrázek:</label>
        <input type="file" name="newImage" placeholder="Obrázek">
        <img src="' . $item["image"] . '" width="50px" height="50px">
        </div>
        <div class="row">
<label>Popis:</label>
        <textarea name="description" placeholder="popis...">' . $item["description"] . '</textarea>
        </div>
        <div class="row">
<label>Cena:</label>
        <input name="price" type="number" value="' . $item["price"] . '" min="0"</input>
        </div>
        <div class="row">
<label>Počet kusů na skladě:</label>
        <input name="quantity" type="number" value="' . $item["quantity"] . '" min="0" </input>
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
                    $_GET["id"]. '');
                exit;
            }

            if (!empty($_SESSION["messages"])) {
                print_r($_SESSION["messages"]);
                return;
            }
            if ($isNewImage) {
                $pathToFile = FileUpload::upload("./img/", "newImage");
                EshopPostRepository::updateProduct($id, $pathToFile, htmlspecialchars($_POST["name"]),
                    htmlspecialchars($_POST["description"]),
                    $_POST["price"], $_POST["quantity"], $_POST["category"]);
            } else {
                EshopPostRepository::updateProductWithoutPicture($id, htmlspecialchars($_POST["name"]),
                    htmlspecialchars($_POST["description"]),
                    $_POST["price"], $_POST["quantity"], $_POST["category"]);
            }
            header("Location: /index.php?page=itemsList");
        }
    }

    public function executeOrder($id)
    {
        OrderRepository::executeOrder($id, 1);
        header('Location: /index.php?page=usersList');
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

            if (EshopPostRepository::insertProduct($pathToFile, $_POST["name"], $_POST["description"],
                $_POST["price"], $_POST["quantity"], $_POST["category"])) {
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
        $dataTable = new DataTable(UserRepository::getAllUsers());
        $dataTable->addColumn("id_user", "ID");
        $dataTable->addColumn("name", "Jméno");
        $dataTable->addColumn("surname", "Příjmení");
        $dataTable->addColumn("email", "Email");
        //  $dataTable->addColumn("password", "Heslo");
        $dataTable->renderUsers();
    }

    public function addNewCategory()
    {
        $_SESSION["messages"] = array();

        if ($_POST) {
            if (empty($_POST["newCategory"])) {
                FlashMessages::error("Zadejte do pole název nové kategorie!");
            }
            if (FlashMessages::containsError()) {
                header("Location: /index.php?page=categoriesList");
                exit;
            }
            if (!empty($_SESSION["messages"])) {
                print_r($_SESSION["messages"]);
                return;
            } else {
                EshopPostRepository::insertCategory($_POST["newCategory"]);
                header("Location: /index.php?page=categoriesList");
            }
        }

    }

    public function editCategoryByAdmin($id)
    {
        $category = EshopPostRepository::getCategoryById($id);
        echo '<div class="add_product_form">
        <form action="/index.php?page=categoriesList&action=completeEditCategory&id=' . $id . '" method="post" 
        enctype="multipart/form-data">
        <div class="row">
<label>Název:(*)</label>           
        <input type="text" name="name" placeholder="Název" value="' . $category["name"] . '">
        </div>  
        <div class="row">
<label>Popis:</label>
        <textarea name="description" placeholder="popis...">' . $category["description"] . '</textarea>
        </div>
        <div class="row">
        <input name="submitConfirmEdit" type="submit" value="Potvrdit">
        </div>
        </form></div>';
    }

    public function completeEditCategory($id)
    {
        $_SESSION["messages"] = array();

        if ($_POST) {
            if (empty($_POST["name"])) {
                FlashMessages::error("Není vyplněn název!");
            }

            if (!empty($_SESSION["messages"])) {
                print_r($_SESSION["messages"]);
                return;
            }
            EshopPostRepository::updateCategory($id, $_POST["name"], $_POST["description"]);
            header("Location: /index.php?page=categoriesList");
        }
        exit;
    }

    public function deleteCategory($id)
    {
        $items = EshopPostRepository::getAllItemsInCategoryBy($id, "id_item", "asc");

        foreach ($items as $key => $value) {
            EshopPostRepository::updateItemToUndefinedCategory($items[$key]["id_item"]);
        }
        EshopPostRepository::deleteCategory($id);
        header("Location: /index.php?page=categoriesList");
    }


}