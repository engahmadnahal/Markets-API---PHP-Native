<?php 

require_once 'model.php';
class User extends Model{

    private $rowTable = "users";
    public function __construct()
    {
        new Model($this->rowTable);

    }

    
}