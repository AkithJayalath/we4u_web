<?php 
class admin extends controller{

  private $adminModel;
  public function __construct(){
        $this->adminModel = $this->model('M_Admin');
  }

  public function index(){
    $data = [
      'title' => 'Admin Dashboard'
    ];
    $this->view('admin/v_admin_dashboard', $data);
  }

  public function adduser(){
    $data = [
      'title' => 'Add User'
    ];
    $this->view('admin/v_add_user', $data);
  }

  public function user_detailes(){
    $data = [
      'title' => 'User Detailes'
    ];
    $this->view('admin/v_users', $data);
  }




}

?>