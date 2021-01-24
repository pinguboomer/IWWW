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

    public static function getItemById($id_item)
    {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT * FROM item WHERE id_item = :id_item");
        $stmt->bindParam(":id_item", $id_item);
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function getCategoryById($id_category)
    {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT * FROM category WHERE id_category = :id_category");
        $stmt->bindParam(":id_category", $id_category);
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

    public static function getAllBy($orderby, $asc_or_desc)
    {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT * FROM item ORDER BY $orderby  $asc_or_desc");
        $stmt->bindParam(":order_by", $orderby);
        $stmt->bindParam(":asc_or_desc", $asc_or_desc);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getAllItemsInCategoryBy($id_category, $orderby, $asc_or_desc)
    {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT * FROM item WHERE id_category = :id_category
 ORDER BY $orderby $asc_or_desc");
        $stmt->bindParam(":id_category", $id_category);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    //----------------------------INSERT----------------------------

    public static function insertProduct($pathToFile, $name, $text, $price, $quantity, $category_id)
    {
        $conn = Connection::getPdoInstance();
        $sql = "INSERT INTO item (image, name, description, price, quantity, id_category)
VALUES (:pathToFile, :name, :text, :price, :quantity, :category_id)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":pathToFile", $pathToFile);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":text", $text);
        $stmt->bindParam(":price", $price);
        $stmt->bindParam(":quantity", $quantity);
        $stmt->bindParam(":category_id", $category_id);
        $stmt->execute();
        return true;
    }

    public static function insertCategory($newCategory)
    {
        $conn = Connection::getPdoInstance();
        $sql = "INSERT INTO category (name)
VALUES (:newCategory)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":newCategory", $newCategory);
        $stmt->execute();
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

    public static function updateProduct($id, $pathToFile, $name, $text, $price, $quantity, $category_id)
    {
        $conn = Connection::getPdoInstance();
        $sql = "UPDATE item SET image = :pathToFile, name = :name, description = :text,
 price = :price, quantity = :quantity, id_category = :category_id WHERE id_item = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":pathToFile", $pathToFile);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":text", $text);
        $stmt->bindParam(":price", $price);
        $stmt->bindParam(":quantity", $quantity);
        $stmt->bindParam(":category_id", $category_id);
        $stmt->execute();
        return true;
    }

    public static function updateProductWithoutPicture($id, $name, $text, $price, $quantity, $category_id)
    {
        $conn = Connection::getPdoInstance();
        $sql = "UPDATE item SET name = :name, description = :text,
 price = :price, quantity = :quantity, id_category = :category_id WHERE id_item = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":text", $text);
        $stmt->bindParam(":price", $price);
        $stmt->bindParam(":quantity", $quantity);
        $stmt->bindParam(":category_id", $category_id);
        $stmt->execute();
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

    public static function updateCategory($id_category, $name, $description)
    {
        $conn = Connection::getPdoInstance();
        $sql = "UPDATE category SET name = :name, description = :description WHERE id_category = :id_category";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id_category", $id_category);
        $stmt->bindParam(":description", $description);
        $stmt->bindParam(":name", $name);
        $stmt->execute();
    }

    public static function updateItemToUndefinedCategory($id_item)
    {
        $conn = Connection::getPdoInstance();
        $sql = "UPDATE item SET id_category = 0 WHERE id_item = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $id_item);
        $stmt->execute();
    }

    //----------------------------DELETE----------------------------

    public static function deleteCategory($id)
    {
        $conn = Connection::getPdoInstance();
        $sql = "DELETE FROM category WHERE id_category = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
    }

}