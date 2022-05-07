<?php 

require_once '../Controller.php';
require_once '../AuthController.php';
require_once '../../model/Admin.php';
require_once '../../model/User.php';
class LoginController extends AuthController{

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
        $email = $_POST['email'];
        $password = $_POST['password'];
        $admin = new Admin();
        $select = $admin->selectWithEmail($email);
        $data = $select->get();
        $isHear = $select->count() > 0;

        if($isHear){
            if($data[0]['password'] == $password){
                $token = $this->getToken();
                $admin->update("token = '$token'",$data[0]['id']);
                $infoUser = $admin->select($data[0]['id'])->get();
                $this->api(true,$infoUser);
            }else{
                $this->errPassword();
            }
        }else{
            $this->unAuth();
        }
    }

    public function AuthUser()
    {
        # code...
        $email = $_POST['email'];
        $password = $_POST['password'];
        $user = new User();
        $select = $user->selectWithEmail($email);
        $data = $select->get();
        $isHear = $select->count() > 0;

        if($isHear){
            if($data[0]['password'] == $password){
                $token = $this->getToken();
                $user->update("token = '$token'",$data[0]['id']);
                $infoUser = $user->select($data[0]['id'])->get();
                $this->api(true,$infoUser);
            }else{
                $this->errPassword();
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

// if(!empty($_GET['id'])){
//     $id = $_GET['id'];
// }else{
//     $id = null;
// }
new LoginController($method);