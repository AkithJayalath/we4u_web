<?php
class pages extends controller{
   // private $pagesModel;
    public function __construct(){
        $this->pagesModel = $this->model('M_Pages');
       
    }

    public function index(){
       
    }

    public function home(){
        $users=$this->pagesModel->getUsers();
        $data =[
            'users'=>$users
        ];
       $this->view('home/homepage',$data);
    }
}


?>