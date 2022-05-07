<?php 
require_once '../Controller.php';
require_once '../../model/Category.php';
require_once '../../model/Admin.php';
require_once '../../model/Stor.php';
class CategoryController extends Controller {

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
        $category = new Category();
        $data = $category->select()->get();
        $this->api(true,$data);
    }

    public function show($id){
        if($id == null){
            throw new Exception("Id varible is null , you should be set value !");
        }else{
            $stor = new Stor();
            $data = $stor->selectForigen('category_id',$id)->get();

            $category = new Category();
            $cat = $category->select($id)->get();

            $index = 0;

            foreach($data as $items){
                $data[$index]['category'] = $cat;
                $index++;
            }
            $this->api(true,$data);

        }
    }

    public function store(){

        if(
            !empty($_POST['name']) &&
            !empty($_POST['description'])
        ){
            $name = $_POST['name'];
            $description = $_POST['description'];
            
            $category = new Category();
            $isSave = $category->insert("name , description","$name,$description");
            $data = [
                "name" => $name,
                "description" => $description
            ];
            $this->api($isSave,$data);
        }else{
            $this->requiredInput();
        }
    }


    public function update($id){
        if($id == null){
            throw new Exception("Id varible is null , you should be set value !");
        }else{

            if(
                !empty($_POST['name']) &&
                !empty($_POST['description'])
            ){
                $name = $_POST['name'];
                $description = $_POST['description'];
                
                $category = new Category();
                $isSave = $category->update("name = '$name' , description = '$description'",$id);
                $data = [
                    "id" => $id,
                    "name" => $name,
                    "description" => $description
                ];
                $this->api($isSave,$data);
            }else{
                $this->requiredInput();
            }
        }
    }



    public function delete($id){
        if($id == null){
            throw new Exception("Id varible is null , you should be set value !");
        }else{
            $category = new Category();
            $isDeleted = $category->delete($id);
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
new CategoryController($method,$id);