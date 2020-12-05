<?php

class UserController
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
                $user_controller = new UserController();
                $country = htmlspecialchars($_POST["country"]);
                $city = htmlspecialchars($_POST["city"]);
                $street = htmlspecialchars($_POST["street"]);
                $zipcode = htmlspecialchars($_POST["zipcode"]);
                $phone = htmlspecialchars($_POST["phone"]);
                $email = $_SESSION["email"];
                $user_controller->addAddressAndUser_Address($country, $city, $street, $zipcode, $phone, $email);
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
                $user_controller = new UserController();
                $country = htmlspecialchars($_POST["country"]);
                $city = htmlspecialchars($_POST["city"]);
                $street = htmlspecialchars($_POST["street"]);
                $zipcode = htmlspecialchars($_POST["zipcode"]);
                $phone = htmlspecialchars($_POST["phone"]);
                $email = $_SESSION["email"];
                $user_controller->updateAddress($country, $city, $street, $zipcode,
                    $phone, $_SESSION["address"]["id_address"]);
                header("Location: /index.php?page=myAddress");
            }
        }
    }

    private function updateAddress($country, $city, $street, int $zipcode, $phone, $id_address)
    {
        EshopPostRepository::updateAddress($country, $city, $street, $zipcode, $phone, $id_address);
    }
}