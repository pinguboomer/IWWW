<?php
class EshopPostRepository
{

    //---------------------------SELECT----------------------------
    static function getAllItems():array {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT * FROM item ORDER BY id_item DESC "); // DESC = radit od nejnovejsiho
        $stmt->execute();
        return $stmt->fetchAll();
    }

    static function getAllItemsSortedByReadCounter($limit):array {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT * FROM item ORDER BY read_counter DESC limit $limit");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    static function getAllItemsSortedByIdDesc($limit):array {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT * FROM item ORDER BY id_item DESC limit $limit");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getAllItemsSortedBySold($limit)
    {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT * FROM item ORDER BY number_of_sold DESC limit $limit");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getOneById($id)
    {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT * FROM item WHERE id_item = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function checkUniqueEmail($email)
    {
        $conn = Connection::getPdoInstance();
        $sql = "SELECT email FROM user_in_shop WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function getUserByEmail($email)
    {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT * FROM user_in_shop WHERE email = :email");
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function logUser($emailLogin)
    {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT * FROM user_in_shop WHERE email = :emailLogin LIMIT 1");
        $stmt->bindParam(":emailLogin", $emailLogin);
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function getRoleByUserId($id_user)
    {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT * FROM user_in_shop WHERE id_user = :id_user");
        $stmt->bindParam(":id_user", $id_user);
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function getAllUsers()
    {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT * FROM user_in_shop ORDER BY id_user"); // DESC = radit od nejnovejsiho
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getAddressById($id_ad)
    {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT * FROM address WHERE id_address = :id_ad");
        $stmt->bindParam(":id_ad", $id_ad);
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function getAddressByIdUser($id_user)
    {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT id_address FROM user_address WHERE id_user = :id_user");
        $stmt->bindParam(":id_user", $id_user);
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function getAddressIdByData($country, $city, $street, $zipcode, $phone)
    {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT id_address
         FROM address WHERE country = :country AND city = :city AND street =:street AND zipcode =:zipcode AND
          phone =:phone");
        $stmt->bindParam(":country", $country);
        $stmt->bindParam(":city", $city);
        $stmt->bindParam(":street", $street);
        $stmt->bindParam(":zipcode", $zipcode);
        $stmt->bindParam(":phone", $phone);
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function getItemById($id_item)
    {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT * FROM item WHERE id_item = :id_item");
        $stmt->bindParam(":id_item", $id_item);
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function getOrderIdByData($totalPrice, $zpusob_platby, $zpusob_dodani)
    {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT id_order
         FROM order_in_shop WHERE total_price = :totalPrice AND typ_platby = :zpusob_platby
          AND typ_dodani =:zpusob_dodani");
        $stmt->bindParam(":totalPrice", $totalPrice);
        $stmt->bindParam(":zpusob_platby", $zpusob_platby);
        $stmt->bindParam(":zpusob_dodani", $zpusob_dodani);
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function getOrdersByUserId($id_user)
    {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT * FROM user_order WHERE id_user = :id_user");
        $stmt->bindParam(":id_user", $id_user);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getItemsByOrderId($id_order)
    {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT * FROM order_item WHERE id_order = :id_order");
        $stmt->bindParam(":id_order", $id_order);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getOrderById($id_order)
    {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT * FROM order_in_shop WHERE id_order = :id_order");
        $stmt->bindParam(":id_order", $id_order);
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function getAddressIdByOrderId($id_order)
    {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT * FROM order_address WHERE id_order = :id_order");
        $stmt->bindParam(":id_order", $id_order);
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function getAllCategories()
    {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT * FROM category");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getItemByData(string $pathToFile, $name, $text, int $price, int $quantity)
    {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT id_item FROM item WHERE image = :pathToFile
 AND name = :name AND description = :text AND price = :price AND quantity = :quantity");
        $stmt->bindParam(":pathToFile", $pathToFile);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":text", $text);
        $stmt->bindParam(":price", $price);
        $stmt->bindParam(":quantity", $quantity);
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function getAllBy(string $orderby, string $asc_or_desc)
    {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT * FROM item ORDER BY $orderby $asc_or_desc");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getAllItemsInCategoryBy($id, $orderby, string $asc_or_desc)
    {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT * FROM item WHERE id_item IN 
   (SELECT id_item FROM item_category WHERE id_category = :id)
 ORDER BY $orderby $asc_or_desc");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getUserById($id)
    {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT * FROM user_in_shop WHERE id_user = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    //----------------------------INSERT----------------------------

    public static function insertProduct(string $pathToFile, $name, $text, $price, $quantity)
    {
        $conn = Connection::getPdoInstance();
        $sql = "INSERT INTO item (image, name, description, price, quantity)
VALUES (:pathToFile, :name, :text, :price, :quantity)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":pathToFile", $pathToFile);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":text", $text);
        $stmt->bindParam(":price", $price);
        $stmt->bindParam(":quantity", $quantity);
        $stmt->execute();
        return true;
    }

    public static function insertUser($name, $surname, $email, $password, $role)
    {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("INSERT INTO user_in_shop (name, surname, email, password, role)
 VALUES (:name,:surname, :email, :password, :role)");
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":surname", $surname);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $password);
        $stmt->bindParam(":role", $role);
        $stmt->execute();
        return true;
    }

    public static function insertAddress($country, $city, $street, $zipcode, $phone)
    {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("INSERT INTO address (country, city, street, zipcode, phone)
 VALUES (:country,:city, :street, :zipcode, :phone)");
        $stmt->bindParam(":country", $country);
        $stmt->bindParam(":city", $city);
        $stmt->bindParam(":street", $street);
        $stmt->bindParam(":zipcode", $zipcode);
        $stmt->bindParam(":phone", $phone);
        $stmt->execute();
        return true;
    }

    public static function insertUserAddress($id_user, $id_ad)
    {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("INSERT INTO user_address (id_user, id_address)
 VALUES (:id_user, :id_ad)");
        $stmt->bindParam(":id_user", $id_user);
        $stmt->bindParam(":id_ad", $id_ad);
        $stmt->execute();
        return true;
    }

    public static function insertOrder($totalPrice, $zpusob_platby, $zpusob_dodani, $date)
    {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("INSERT INTO order_in_shop (total_price, typ_platby,
 typ_dodani, date_of_order)
 VALUES (:totalPrice,:zpusob_platby, :zpusob_dodani, :date)");
        $stmt->bindParam(":totalPrice", $totalPrice);
        $stmt->bindParam(":zpusob_platby", $zpusob_platby);
        $stmt->bindParam(":zpusob_dodani", $zpusob_dodani);
        $stmt->bindParam(":date", $date);
        $stmt->execute();
        return true;
    }

    public static function insertUserOrder($id_user, $id_order)
    {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("INSERT INTO user_order (id_user, id_order)
 VALUES (:id_user, :id_order)");
        $stmt->bindParam(":id_user", $id_user);
        $stmt->bindParam(":id_order", $id_order);
        $stmt->execute();
        return true;
    }

    public static function insertOrderAddress($id_order, $id_address)
    {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("INSERT INTO order_address (id_order, id_address)
 VALUES (:id_order, :id_address)");
        $stmt->bindParam(":id_address", $id_address);
        $stmt->bindParam(":id_order", $id_order);
        $stmt->execute();
        return true;
    }

    public static function insertOrderItem($id_order, $id, $name, $image, $price, $quantity)
    {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("INSERT INTO order_item (id_order, id_item,
 name, image, price, quantity)
 VALUES (:id_order, :id, :name, :image, :price, :quantity)");
        $stmt->bindParam(":id_order", $id_order);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":image", $image);
        $stmt->bindParam(":price", $price);
        $stmt->bindParam(":quantity", $quantity);
        $stmt->execute();
        return true;
    }

    public static function insertItemCategory($id, $category)
    {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("INSERT INTO item_category (id_item, id_category)
 VALUES (:id, :category)");
        $stmt->bindParam(":category", $category);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return true;
    }


    //----------------------------UPDATE----------------------------

    public static function increaseReadCounter($id){
        $conn = Connection::getPdoInstance();
        $sql = "UPDATE item SET read_counter = read_counter+1 WHERE id_item= :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return true;
    }

    public static function updateUser($name, $surname, $email)
    {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("UPDATE user_in_shop
         SET name = :name, surname = :surname WHERE email = :email");
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":surname", $surname);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return true;
    }

    public static function updateAddress($country, $city, $street, int $zipcode, $phone, $id_address)
    {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("UPDATE address
         SET country = :country, city = :city, street =:street, zipcode =:zipcode,
          phone =:phone WHERE id_address = :id_address");
        $stmt->bindParam(":country", $country);
        $stmt->bindParam(":city", $city);
        $stmt->bindParam(":street", $street);
        $stmt->bindParam(":zipcode", $zipcode);
        $stmt->bindParam(":phone", $phone);
        $stmt->bindParam(":id_address", $id_address);
        $stmt->execute();
        return true;
    }

    public static function updateUserById($id_user, $name, $surname, $email)
    {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("UPDATE user_in_shop
         SET name = :name, surname = :surname, email = :email WHERE id_user = :id_user");
        $stmt->bindParam(":id_user", $id_user);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":surname", $surname);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return true;
    }

    public static function updateProduct($id, string $pathToFile, $name, $text, $price, $quantity)
    {
        $conn = Connection::getPdoInstance();
        $sql = "UPDATE item SET image = :pathToFile, name = :name, description = :text,
 price = :price, quantity = :quantity WHERE id_item = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":pathToFile", $pathToFile);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":text", $text);
        $stmt->bindParam(":price", $price);
        $stmt->bindParam(":quantity", $quantity);
        $stmt->execute();
        return true;
    }

    public static function updateItemCategory($id, $category)
    {
        $conn = Connection::getPdoInstance();
        $sql = "UPDATE item_category SET id_category = :category WHERE id_item = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":category", $category);
        $stmt->execute();
        return true;
    }

    public static function updateItem($id_item, $new_quantity, $sold)
    {
        $conn = Connection::getPdoInstance();
        $sql = "UPDATE item SET quantity = :new_quantity, number_of_sold = :sold WHERE id_item = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $id_item);
        $stmt->bindParam(":new_quantity", $new_quantity);
        $stmt->bindParam(":sold", $sold);
        $stmt->execute();
        return true;
    }

    public static function executeOrder($id, $executed)
    {
        $conn = Connection::getPdoInstance();
        $sql = "UPDATE order_in_shop SET executed = :executed WHERE id_order = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":executed", $executed);
        $stmt->execute();
        return true;
    }

    //----------------------------DELETE----------------------------

    public static function deleteUserOrder($id)
    {
        $conn = Connection::getPdoInstance();
        $sql = "DELETE FROM user_order WHERE id_order = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return true;
    }

    public static function deleteOrderItem($id)
    {
        $conn = Connection::getPdoInstance();
        $sql = "DELETE FROM order_item WHERE id_order = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return true;
    }

    public static function deleteOrderAddress($id)
    {
        $conn = Connection::getPdoInstance();
        $sql = "DELETE FROM order_address WHERE id_order = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return true;
    }

    public static function deleteAddress($id_address)
    {
        $conn = Connection::getPdoInstance();
        $sql = "DELETE FROM address WHERE id_address = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $id_address);
        $stmt->execute();
        return true;
    }

    public static function deleteObjednavka($id)
    {
        $conn = Connection::getPdoInstance();
        $sql = "DELETE FROM order_in_shop WHERE id_order = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return true;
    }




}