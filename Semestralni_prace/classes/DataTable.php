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
<form action="/index.php?page=myProfile&action=editAsAdmin&id='.$row["id_user"].'" method="post">
<input name="submitEditUserByAdmin" type="submit" value="Údaje" style="width:90%"></form></td>
<td><form action="/index.php?page=orders&action=editOrdersByAdmin&id='.$row["id_user"].'" method="post">
<input name="submitEditUserByAdmin" type="submit" value="Objednávky" style="width:90%">
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
}