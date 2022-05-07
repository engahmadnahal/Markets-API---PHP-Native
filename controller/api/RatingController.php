<?php 
require_once '../Controller.php';
require_once '../../model/Rating.php';
require_once '../../model/Stor.php';
class RatingController extends Controller {

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
       

    }
    public function show($id){
        if($id == null){
            throw new Exception("Id varible is null , you should be set value !");
        }else{
        echo "show";

        }
    }
    public function store(){
        // like dislike users_id stor_id
         if(
                !empty($_POST['like'])&&
                !empty($_POST['dislike'])&&
                !empty($_POST['user_id'])&&
                !empty($_POST['stor_id'])
            ){
                // Get All Data send clint side
                $like = $_POST['like'];
                $dislike = $_POST['dislike'];
                $user_id = $_POST['user_id'];
                $stor_id = $_POST['stor_id'];


                $rating = new Rating();
                // Check User is like already
                $dataIsExist = $rating->where("users_id = $user_id and stor_id = $stor_id");
                $isExist = $dataIsExist->count() > 0;

                if($isExist){
                    $isSave = $rating->update("`like` = $like, dislike = $dislike, users_id = $user_id,stor_id = $stor_id",$dataIsExist->get()[0]['id']);
                }else{
                    $isSave = $rating->insert("`like`, dislike, users_id ,stor_id","$like,$dislike,$user_id,$stor_id");
                }
                $dataRaitin = $rating->selectForigen('stor_id',$stor_id)->get();

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
                $stor = new Stor();
                // $dataStor = $stor->with("rating","stor.id = rating.stor_id")->get();
                $dataStor = $stor->select($stor_id)->get();
                $dataStor[0]['rating'] = [
                        "like"=>$sumLike,
                        "dislike"=>$sumDisLike,
                ];

                $this->api($isSave,$dataStor);

                
            }else{
                $this->requiredInput();
            }

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
new RatingController($method,$id);