<?php


class OrderRepository
{
    //---------------------------SELECT----------------------------
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

    //----------------------------INSERT----------------------------

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

    //----------------------------UPDATE----------------------------

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

}