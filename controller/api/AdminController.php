<?php 
require_once '../Controller.php';
require_once '../../model/Admin.php';

class AdminController extends Controller {

    public function __construct($m,$id = null)
    {
        $this->auth($m,$id);
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
        $admins = new Admin();
        $data = $admins->select()->get();
        $this->api(true,$data);
    }
    public function show($id){
        if($id == null){
            throw new Exception("Id varible is null , you should be set value !");
        }else{
            $admins = new Admin();
            $data = $admins->select($id)->get();
            $this->api(true,$data);

        }
    }
    public function store(){

        // name email password status title description

        if(
            !empty($_POST['name'])&&
            !empty($_POST['email'])&&
            !empty($_POST['password'])&&
            !empty($_POST['status'])&&
            !empty($_POST['title'])&&
            !empty($_POST['description'])
        ){
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $status = $_POST['status'];
            $title = $_POST['title'];
            $description = $_POST['description'];

            $admin = new Admin();
            $isSave = $admin->insert("name, email, password, status, title, description",
        "$name,$email,$password,$status,$title,$description");
        $this->api($isSave,["Success Save new admin "]);
        }
    }
    public function update($id){
        if($id == null){
            throw new Exception("Id varible is null , you should be set value !");
        }else{

        if(
            !empty($_POST['name'])&&
            !empty($_POST['email'])&&
            !empty($_POST['password'])&&
            !empty($_POST['status'])&&
            !empty($_POST['title'])&&
            !empty($_POST['description'])
        ){
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $status = $_POST['status'];
            $title = $_POST['title'];
            $description = $_POST['description'];

            $admin = new Admin();
            $isSave = $admin->update("name = '$name' , email = '$email' , password = '$password' , status = '$status', title = '$title',description = '$description' ",$id);
        $this->api($isSave,["Success Update new admin "]);
        }
        }
    }
    public function delete($id){
        if($id == null){
            throw new Exception("Id varible is null , you should be set value !");
        }else{
            $admin = new Admin();
            $isDeleted = $admin->delete($id);
            $this->api($isDeleted,["Deleted is success"]);

        }
    }

    public function auth($fun,$id = null){
        if(!empty($_GET['token'])){
            $token = $_GET['token'];
            $admin = new Admin();
            $allowAdmin = $admin->selectToken($token)->count() > 0;
            if($allowAdmin){
                $this->chechRoute($fun,$id);
            }else{
                $this->unAuth();
            }
        }else{
            $this->unAuth();
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
new AdminController($method,$id);