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

  public function blog(){
    $data = [
      'title' => 'Blog'
    ];
    $this->view('admin/v_blog', $data);
  }

  public function viewblog(){
    $data = [
      'title' => 'View Blog'
    ];
    $this->view('admin/v_view_blog', $data);
  }

  public function editblog(){
    $data = [
      'title' => 'Add Blog'
    ];
    $this->view('admin/v_edit_blog', $data);
  }

  public function addblog(){
    $data = [
      'title' => 'Add Blog'
    ];
    $this->view('admin/v_add_blog', $data);
  }

  public function viewannouncement(){
    $data = [
      'title' => 'View Announcement'
    ];
    $this->view('admin/v_viewannouncements', $data);
  }

  public function editannouncement(){
    $data = [
      'title' => 'Edit Announcement'
    ];
    $this->view('admin/v_editannouncement', $data);
  }

  public function addannouncement(){
    $data = [
      'title' => 'Add Announcement'
    ];
    $this->view('admin/v_addannouncement', $data);
  }



}

?>