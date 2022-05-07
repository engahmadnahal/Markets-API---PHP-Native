<?php 

class Model {

    protected static $table;
    protected $host = "localhost";
    protected $dbname = "markets";
    protected $username = "root";
    protected $password = "";
    protected static $conn;
    protected static $result;

    public function __construct($table = null)
    {

        Model::$table = $table;

        /**
         * 
         * Check value for varible table name
         */
         
        if(Model::$table == null || Model::$table == ""){
            throw new Exception("Is name table is inValide");
        }
        
         Model::$conn = mysqli_connect($this->host,$this->username,$this->password,$this->dbname);
         if(!Model::$conn){
            throw new Exception("Error connection database ".mysqli_connect_error());
         }
    }

    /**
     * 
     * Select data from database , when condetion or not
     */

    public function select($id = null){
    
        if($id != null || $id != ""){
            $sql = "select * from ".Model::$table . " where id = ".$id;
            Model::$result = mysqli_query(Model::$conn,$sql);
            return $this;
        }else{
            $sql = "select * from ".Model::$table." order by id asc";
            Model::$result = mysqli_query(Model::$conn,$sql);
            return $this;
        }
    }

    /**
     * 
     * Select data from database , when condetion Email
     */

    public function selectWithEmail($email = null){
    
        if($email != null || $email != ""){
            $sql = "select * from ".Model::$table . " where email = '$email'";
            Model::$result = mysqli_query(Model::$conn,$sql);
            return $this;
        }else{
            
            return "Email is requierd";
        }
    }
    /**
     * 
     * Select data from database , when condetion or Token
     */

    public function selectToken($token){
    
        if($token != null || $token != ""){
            $sql = "select * from ".Model::$table . " where token = '$token'";
            Model::$result = mysqli_query(Model::$conn,$sql);
            return $this;
        }else{
            throw new Exception("Token is inValide !");
            return $this;
        }
    }
    /**
     * 
     * Get count row , in record
     */
    public function count()
    {   
        $count = mysqli_num_rows(Model::$result);
        return $count;
    }
    
    /**
     * 
     * Fetch data and convert to associative array
     * @param $result <- this is result query
     * @return associative array
     */
    public function get(){
        $array = [];
        $index = 0;
        while($row = mysqli_fetch_assoc(Model::$result)){
                $array[$index] = $row;
            $index++;
        }
            return $array;
    }


    /**
     * 
     * Insert data 
     * @param $coloumn , $value this column
     * @return result bool
     */
    public function insert($col,$val) {
        $value = explode(',',$val);
        $resultVal = '';
        foreach($value as $item){
            if($item == 'true' || $item == 'false'){
                $resultVal .= "$item,";
               
            }else{
                $resultVal .= "'$item',";
            }
        }
        $sql = "insert into ".Model::$table . " (".$col.") values (".substr($resultVal,0,strlen($resultVal) - 1 ).")";
        $result = mysqli_query(Model::$conn,$sql);
        return boolval($result);
    }

    /**
     * 
     * Insert data 
     * @param $coloumn , $value this column
     * @return result bool
     */
    public function insertWithId($col,$val,$inEmail = null) {
        $value = explode(',',$val);
        $resultVal = '';
        foreach($value as $item){
            $resultVal .= "'$item',";
        }
        $sql = "insert into ".Model::$table . " (".$col.") values (".substr($resultVal,0,strlen($resultVal) - 1 ).")";
        $result = mysqli_query(Model::$conn,$sql);

        if($inEmail != null){
            $data = $this->selectWithEmail($inEmail);
            return $this->get($data);
        }
        return boolval($result);
    }
    /**
     * 
     * Update this row
     * @param $id , column and value this column in one line is string , ex => "col1 = val1 , col2 = val2 .."
     * @return result bool
     */
    public function update( $colAndVal , $id) { 
        $sql = "update ".Model::$table . " set ".$colAndVal . " where id =".$id;
        // print_r(json_encode(['sql'=>$sql]));

        $result = mysqli_query(Model::$conn,$sql);
        return boolval($result);
    }
    /**
     * 
     * Delete this row
     * @param id row 
     * @return result bool
     */
    public function delete($id){
        $sql = "delete from ".Model::$table . " where id = ".$id;
        // print_r(json_encode(['sql'=>$sql]));
        $result = mysqli_query(Model::$conn,$sql);
        return boolval($result);
    }
    /**
     * 
     * Check is row is exists or not
     * @param id row
     * @return result bool
     */
    public function exists($id)
    {
        $sql = "select * from ".Model::$table . " where id = ".$id;
        $result = mysqli_query(Model::$conn,$sql);
        $count = mysqli_num_rows($result);
        if($count > 0){
            return true;
        }else{
            return false;
        }
    }

    public function map($data)
    {
        return "\'+$data+\'";
    }


    public function with($withTable,$cond){
        $sql = "select * from ".Model::$table . " inner join $withTable on  $cond";
        Model::$result = mysqli_query(Model::$conn,$sql);
        return $this;
    }

    public function selectForigen($forigen,$value){
        $sql = "select * from ".Model::$table . " where $forigen =  $value";
        Model::$result = mysqli_query(Model::$conn,$sql);
        return $this;
    }

    public function where($cond){
        $sql = "select * from ".Model::$table . " where $cond";
        Model::$result = mysqli_query(Model::$conn,$sql);
        return $this;
    }

}