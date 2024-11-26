<?php 
class admin extends controller{

  private $adminModel;

  public function __construct(){
    if(!$_SESSION['user_id']) {
      redirect('users/login');
    }else{
      if($_SESSION['user_role'] != 'Admin'){
        redirect('pages/error');
      }
    }
    $this->adminModel = $this->model('M_Admin');
  }

  public function index(){
    $data = [
      'title' => 'Admin Dashboard'
    ];
    $this->view('admin/v_admin_dashboard', $data);
  }

  public function adduser(){
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $data = [
            'name' => trim($_POST['name']),
            'email' => trim($_POST['email']),
            'password' => trim($_POST['password']),
            'verify_password' => trim($_POST['verify_password']),
            'role' => trim($_POST['role']),
            'name_err' => '',
            'email_err' => '',
            'password_err' => '',
            'verify_password_err' => '',
            'role_err' => ''
        ];

        // Validation
        if(empty($data['name'])){
            $data['name_err'] = 'Please enter name';
        }

        if(empty($data['email'])){
            $data['email_err'] = 'Please enter email';
        } elseif($this->adminModel->findUserByEmail($data['email'])){
            $data['email_err'] = 'Email is already taken';
        }

        if(empty($data['password'])){
            $data['password_err'] = 'Please enter password';
        } elseif(strlen($data['password']) < 6){
            $data['password_err'] = 'Password must be at least 6 characters';
        } elseif(!preg_match('/[A-Z]/', $data['password'])){
            $data['password_err'] = 'Password must contain at least one Uppercase Letter';
        } elseif(!preg_match('/[a-z]/', $data['password'])){
            $data['password_err'] = 'Password must contain at least one Lowercase Letter';
        } elseif(!preg_match('/[0-9]/', $data['password'])){
            $data['password_err'] = 'Password must contain at least one Number';
        }

        if(empty($data['verify_password'])){
            $data['verify_password_err'] = 'Please confirm password';
        } elseif($data['password'] != $data['verify_password']){
            $data['verify_password_err'] = 'Passwords do not match';
        }

        if(empty($data['role'])){
            $data['role_err'] = 'Please select a role';
        }

        if(empty($data['name_err']) && empty($data['email_err']) && 
           empty($data['password_err']) && empty($data['verify_password_err']) && 
           empty($data['role_err'])){
            
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

            if($this->adminModel->addUser($data)){
                redirect('admin/user_detailes');
            } else {
                die('Something went wrong');
            }
        } else {
            $this->view('admin/v_add_user', $data);
        }
    } else {
        $data = [
            'name' => '',
            'email' => '',
            'password' => '',
            'verify_password' => '',
            'role' => '',
            'name_err' => '',
            'email_err' => '',
            'password_err' => '',
            'verify_password_err' => '',
            'role_err' => ''
        ];
        
        $this->view('admin/v_add_user', $data);
    }
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

  
  } // Only one closing brace needed here for the class

?>