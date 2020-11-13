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
        echo '<tr>';
        foreach ($this->columns as $key => $value){
            echo '<th>' . $value . '</th>';
        }
        echo '</tr>';
        foreach ($this->dataSet as $row) {
            echo '<tr>';
            foreach ($this->columns as $key => $value){
                echo '<td>' . $row[$key] . '</td>';
            }
            echo '<td>
<form action="/index.php?page=myProfile&action=editAsAdmin&id='.$row["id_user"].'" method="post">
<input name="submitEditUserByAdmin" type="submit" value="Upravit údaje" style="width:auto"></form></td>
<td><form action="/index.php?page=orders&action=editOrdersByAdmin&id='.$row["id_user"].'" method="post">
<input name="submitEditUserByAdmin" type="submit" value="Spravovat objednávky" style="width:auto">
</form></td>';
            echo '</tr>';
            }
        echo '</table>';
    }
    public function renderProducts()
    {
        echo '<table>';
        echo '<tr>';
        foreach ($this->columns as $key => $value){
            echo '<th>' . $value . '</th>';
        }
        echo '</tr>';
        foreach ($this->dataSet as $row) {
            echo '<tr>';
            foreach ($this->columns as $key => $value){
                echo '<td>' . $row[$key] . '</td>';
            }
            echo '<td>
<form action="/index.php?page=itemsList&action=editAsAdmin&id='.$row["id_item"].'" method="post">
<input name="submitEditProductByAdmin" type="submit" value="UPRAVIT" style="width:auto">
</form></td>';
            echo '</tr>';
        }
        echo '</table>';
    }
}