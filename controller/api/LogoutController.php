<?php 

require_once '../Controller.php';
require_once '../AuthController.php';
require_once '../../model/Admin.php';
require_once '../../model/User.php';
class LogoutController extends AuthController{

    public function __construct($m)
    {
        $this->chechRoute($m);
    }

    public function chechRoute($m){
        switch($m){
            case 'admin' :
                $this->AuthAdmin();
            break;
            case 'user' :
                $this->AuthUser();
            
            break;
           
        }
    }

    public function AuthAdmin()
    {
        # code...
        $userId = $_POST['id'];
        $admin = new Admin();
        $data = $admin->select($userId)->get();
        $admin->update("token = NULL",$data[0]['id']);
        $this->unAuth();
    }

    public function AuthUser()
    {
        # code...
        $userId = $_POST['id'];
        $user = new User();
        $data = $user->select($userId)->get();
        $user->update("token = NULL",$data[0]['id']);
        $this->unAuth();

    }


}

if(!empty($_GET['m'])){
    $method = $_GET['m'];
}else{
    throw new Exception("Bad Call Method Controller !");
}

// if(!empty($_GET['id'])){
//     $id = $_GET['id'];
// }else{
//     $id = null;
// }
new LogoutController($method);