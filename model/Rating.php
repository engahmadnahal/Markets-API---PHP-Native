<?php 

require_once 'model.php';
class Rating extends Model{

    private $rowTable = "rating";
    public function __construct()
    {
        new Model($this->rowTable);

    }

    
}