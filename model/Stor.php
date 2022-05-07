<?php 

require_once 'model.php';
class Stor extends Model{

    private $rowTable = "stor";
    public function __construct()
    {
        new Model($this->rowTable);

    }

    
}