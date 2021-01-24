<?php


class UserRepository
{
    //---------------------------SELECT----------------------------
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

    public static function getUserById($id)
    {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT * FROM user_in_shop WHERE id_user = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    //----------------------------INSERT----------------------------
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

    //----------------------------UPDATE----------------------------

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

    public static function updateAddress($country, $city, $street, $zipcode, $phone, $id_address)
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

}