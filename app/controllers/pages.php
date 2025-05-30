<?php
class pages extends controller{
    private $pagesModel;
    public function __construct(){
        $this->pagesModel = $this->model('M_Pages');
       
    }

    public function index(){
       $data = [];
       $this->view('careseeker/v_createProfile',$data);
    }

    public function home(){
        $users=$this->pagesModel->getUsers();
        $data =[
            'users'=>$users
        ];
       $this->view('home/homepage',$data);
    }

    public function permissonerror(){
        $data = [];
        $this->view('v_permissionerror',$data);
    }

    public function about() {
        require_once APPROOT . '/views/v_about.php';
    }
    public function consultantv() {
        require_once APPROOT . '/views/v_consultant.php';
    }
}


?>