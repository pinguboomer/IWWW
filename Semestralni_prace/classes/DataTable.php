<?php


class DataTable
{
    private $dataSet;
    private $columns;

    /**
     * DataTable constructor.
     */
    public function __construct($dataSet)
    {
        $this->dataSet = $dataSet;
    }

    public function addColumn($key, $humanReadableKey){
        $this->columns[$key] = $humanReadableKey;
    }

    public function renderUsers()
    {
        echo '<table>';
        echo '<thead><tr>';
        foreach ($this->columns as $key => $value){
            echo '<th>' . $value . '</th>';
        }
        echo '</thead><tbody></tr>';
        foreach ($this->dataSet as $row) {
            echo '<tr>';
            foreach ($this->columns as $key => $value){
                echo '<td data-label=' . $value . '>' . $row[$key] . '</td>';
            }
            echo '<td>
<form action="/index.php" method="get">
<input type="hidden" name="page" value="myProfile">
<input type="hidden" name="action" value="editAsAdmin">
<input type="hidden" name="id" value="'.$row["id_user"].'">
<input type="submit" value="Údaje" style="width:90%"></form></td>
<td><form action="/index.php" method="get">
<input type="hidden" name="page" value="orders">
<input type="hidden" name="user" value="'.$row["id_user"].'">
<input type="hidden" name="action" value="editOrdersByAdmin">
<input type="hidden" name="id" value="'.$row["id_user"].'">
<input type="submit" value="Objednávky" style="width:90%">
</form></td>';
            echo '</tr>';
            }
        echo '</tbody></table>';
    }

    public function renderProducts()
    {
        echo '<table style="margin: auto">';
        echo '<thead><tr>';
        foreach ($this->columns as $key => $value){
            echo '<th>' . $value . '</th>';
        }
        echo '</tr></thead>';
        foreach ($this->dataSet as $row) {
            echo '<tr>';
            foreach ($this->columns as $key => $value){
                echo '<td data-label=' . $value . '>' . $row[$key] . '</td>';
            }
            echo '<td>
<form action="/index.php?page=itemsList&action=editAsAdmin&id='.$row["id_item"].'" method="post">
<input name="submitEditProductByAdmin" type="submit" value="UPRAVIT" style="width:auto">
</form></td>';
            echo '</tr>';
        }
        echo '</table>';
    }

    public function renderCategories()
    {
        echo '<table style="margin: auto">';
        echo '<thead><tr>';
        foreach ($this->columns as $key => $value){
            echo '<th>' . $value . '</th>';
        }
        echo '</tr></thead>';
        foreach ($this->dataSet as $row) {
            echo '<tr>';
            foreach ($this->columns as $key => $value){
                echo '<td data-label=' . $value . '>' . $row[$key] . '</td>';
            }
            echo '<td>
<form action="/index.php?page=categoriesList&action=edit&id='.$row["id_category"].'" method="post">
<input name="submitEditCategoryByAdmin" type="submit" value="UPRAVIT" style="width:auto">
</form></td>
<td>
<form action="/index.php?page=categoriesList&action=delete&id='.$row["id_category"].'" method="post">
<input name="submitDeleteCategoryByAdmin" 
onclick="return confirm(\'Opravdu chcete vymazat kategorii? Produkty v kategorii se stanou nezařazené!\')"
type="submit" value="ODEBRAT" style="width:auto">
</form></td>';
            echo '</tr>';
        }
        echo '</table>';
    }
}