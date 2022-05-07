<?php 

require_once 'model.php';
class Category extends Model{

    private $rowTable = "category";
    public function __construct()
    {
        new Model($this->rowTable);

    }
    
}