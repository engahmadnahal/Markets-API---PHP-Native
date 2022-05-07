<?php 

require_once '../Controller.php';
require_once '../../model/User.php';

class UserController extends Controller {

    public function __construct($m,$id = null)
    {
        $this->chechRoute($m,$id);
    }

    public function chechRoute($m,$id = null){
        switch($m){
            case 'index' :
                $this->index();
            break;
            case 'show' :
                $this->show($id);
            
            break;
            case 'store' :
                $this->store();
            
            break;
            case 'update' :
                $this->update($id);
            
            break;
            case 'delete' :
                $this->delete($id);
            
            break;
        }
    }
    public function index(){
        $user = new User();
        $data = $user->select()->get();
        $this->api(true,$data);
    }
    public function show($id){
        if($id == null){
            throw new Exception("Id varible is null , you should be set value !");
        }else{
            $user = new User();
            $data = $user->select($id)->get();
            $this->api(true,$data);
        }
    }
    public function store(){

        print_r(json_encode($_POST['name']));
    }
    public function update($id){
        if($id == null){
            throw new Exception("Id varible is null , you should be set value !");
        }else{

        echo "update";
        }
    }
    public function delete($id){
        if($id == null){
            throw new Exception("Id varible is null , you should be set value !");
        }else{
        echo "delete";

        }
    }

}

if(!empty($_GET['m'])){
    $method = $_GET['m'];
}else{
    throw new Exception("Bad Call Method Controller !");
}

if(!empty($_GET['id'])){
    $id = $_GET['id'];
}else{
    $id = null;
}
new UserController($method,$id);