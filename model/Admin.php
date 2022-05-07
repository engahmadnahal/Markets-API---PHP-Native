<?php 

require_once 'model.php';
class Admin extends Model{

    private $rowTable = "admins";
    public function __construct()
    {
        new Model($this->rowTable);

    }

    
}