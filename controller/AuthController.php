<?php 

class AuthController{



    public function api($status,$data){
        $array = [
            'status' => $status,
            'msg' => $status ? "Success Get Data" : "Error Get Data",
            'data' => $status ? $data : "Opss !"
        ];
        print_r(json_encode($array));
    }

    public function getToken()
    {
        $serial = "ABCDEFGKLMNOBQRSTUVWXYZabcdefghilavuwqmh[lcxno83/*2rrt123355468";
        $result = "";

        for($i = 0; $i < 30 ; $i++){
            $result .= $serial[rand(0,strlen($serial) - 1)];
        }
        return "eRt".$result."sTkl8R";
    }



    public function unAuth()
    {
        # code...
        $array = [
            'status' => false,
            'msg'=>"Un Authonticated this user",
        ];
        return print_r(json_encode($array));
    }

        public function errPassword()
    {
        # code...
        $array = [
            'status' => false,
            'msg'=>"Password is not correct !",
        ];
        return print_r(json_encode($array));
    }
}
