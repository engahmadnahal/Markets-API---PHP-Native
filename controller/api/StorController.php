<?php 
require_once '../Controller.php';
require_once '../../model/Stor.php';
require_once '../../model/Category.php';
require_once '../../model/Admin.php';
require_once '../../model/Rating.php';

class StorController extends Controller {

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
        $stor = new Stor();
        $data = $stor->select()->get();
        $rating = new Rating();


        $index = 0;
        foreach($data as $items){
            $stor_id =  $items['id'];
            $dataRaitin = $rating->selectForigen('stor_id',$stor_id)->get();
            $sumLike = 0;
            $sumDisLike = 0;
            foreach($dataRaitin as $items){
                foreach($items as $key => $item){
                    if($key == 'like'){
                        if($item == true){
                            $sumLike += 1;
                        }
                    }elseif($key == 'dislike'){
                        if($item == true){
                            $sumDisLike += 1;
                        }
                    }
                }
            }

            $data[$index]['rating'] = [
                        "like"=>$sumLike,
                        "dislike"=>$sumDisLike,
            ];
        

            $index++;
            
        }

        $category = new Category();
        $iCat = 0;
        foreach($data as $items){
            $catData = $category->select($items['category_id'])->get();
            $data[$iCat]['category'] = [
                "id"=>$catData[0]['id'],
                "name"=>$catData[0]['name'],
                "description"=>$catData[0]['description'],
            ];
            $iCat++;
        }

        $this->api(true,$data);
    }
    public function show($id){
        if($id == null){
            throw new Exception("Id varible is null , you should be set value !");
        }else{
        $stor = new Stor();
        $data = $stor->select($id)->get();

        $rating = new Rating();
        $dataRaitin = $rating->selectForigen('stor_id',$data[0]['id'])->get();
        /**
         * 
         * Get Sum Likes and DisLikes
         */
        $sumLike = 0;
        $sumDisLike = 0;
        foreach($dataRaitin as $items){
            foreach($items as $key => $item){
                if($key == 'like'){
                    if($item == true){
                        $sumLike += 1;
                    }
                }elseif($key == 'dislike'){
                    if($item == true){
                        $sumDisLike += 1;
                    }
                }
            }
        }
        /**
         * 
         * End Get Sum Likes and DisLikes
         */

         $category = new Category();
         $catData = $category->select($data[0]['category_id'])->get();
        $data[0]['rating'] = [
                        "like"=>$sumLike,
                        "dislike"=>$sumDisLike,
            ];
        $data[0]['category'] = [
                "id"=>$catData[0]['id'],
                "name"=>$catData[0]['name'],
                "description"=>$catData[0]['description'],
            ];
        $this->api(true,$data);

        }
    }




    public function store(){

        
            if(
                !empty($_POST['description'])&&
                !empty($_POST['name'])&&
                !empty($_POST['phone'])&&
                !empty($_POST['category_id'])&&
                !empty($_FILES['logo'])
            ){

                $description = $_POST['description'];
                $phone = $_POST['phone'];
                $name = $_POST['name'];
                $category_id = $_POST['category_id'];
                $isUpload = $this->uploadFile("logo");
                $nameFile = $isUpload['name'];
                $stor = new Stor();
                $data = $stor->insert("name ,description , phone , logo , category_id",
                "$name,$description,$phone,$nameFile ,$category_id");

                $category = new Category();
                $catData = $category->select($category_id)->get();

                $this->api($data,[
                    "name"=>$name,
                    "description"=>$description,
                    "phone"=>$phone,
                    "logo"=>$nameFile,
                    "category_id"=>$catData,
                ]);
            }else{
                $this->requiredInput();
            }
        

    }


    public function update($id){
        if($id == null){
            throw new Exception("Id varible is null , you should be set value !");
        }else{

        if(
                !empty($_POST['description'])&&
                !empty($_POST['name'])&&
                !empty($_POST['phone'])&&
                !empty($_POST['category_id'])&&
                !empty($_FILES['logo'])
            ){

                $description = $_POST['description'];
                $phone = $_POST['phone'];
                $name = $_POST['name'];
                $category_id = $_POST['category_id'];
                $isUpload = $this->uploadFile("logo");
                $nameFile = $isUpload['name'];
                $stor = new Stor();
                $data = $stor->update("name = '$name' , description = '$description', phone = '$phone' , category_id = '$category_id' , logo = '$nameFile'",$id);

                $category = new Category();
                $catData = $category->select($category_id)->get();

                $this->api($data,[
                    "name"=>$name,
                    "description"=>$description,
                    "phone"=>$phone,
                    "logo"=>$nameFile,
                    "category_id"=>$catData,
                ]);
            }else{
                $this->requiredInput();
            }

        }
    }
    public function delete($id){
        if($id == null){
            throw new Exception("Id varible is null , you should be set value !");
        }else{
        $stor = new Stor();
        $isDelete = $stor->delete($id);
        $this->api($isDelete,["Deleted is success"]);
        }
    }



    public function auth($fun,$id = null)
    {
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
            if($_GET['m'] != 'index' || $_GET['m'] != 'show'){
                $this->chechRoute($fun,$id);
            }else{
                $this->unAuth();
            }
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
new StorController($method,$id);