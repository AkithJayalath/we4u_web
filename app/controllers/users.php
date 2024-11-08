<?php
  class users extends controller{
    private $usersModel;
    public function __construct(){
      $this->usersModel = $this->model('M_Users');
    }

    public function register(){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Now the form is submitting
        // Value the data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        // INPUT DATA
        $data = [
          'name' => trim($_POST['name']),
          'email' => trim($_POST['email']),
          'password' => trim($_POST['password']),
          'confirm_password' => trim($_POST['confirm_password']),

          'name_err' => '',
          'email_err' => '',
          'password_err' => '',
          'confirm_password_err' => ''
        ];

        // validation part
        // validate name
        if(empty($data['name'])){
          $data['name_err'] = 'Please enter name';
        }

        // validate email
        if(empty($data['email'])){
          $data['email_err'] = 'Please enter email';
        }else{
          // check email
          if($this->usersModel->findUserByEmail($data['email'])){
            $data['email_err'] = 'Email is already taken';
          }
        }

        // validate password
        if(empty($data['password'])){
          $data['password_err'] = 'Please enter password';
        } elseif(strlen($data['password']) < 6){
          $data['password_err'] = 'Password must be at least 6 characters';
        } elseif(!preg_match('/[A-Z]/', $data['password'])){
          $data['password_err'] = 'Password must contain at least one Uppercasr Letter';
        } elseif(!preg_match('/[a-z]/', $data['password'])){
          $data['password_err'] = 'Password must contain at least one Lowercase Letter';
        } elseif(!preg_match('/[0-9]/', $data['password'])){
          $data['password_err'] = 'Password must contain at least one Number';
        } elseif ($data['password'] != $data['confirm_password']){
          $data['confirm_password_err'] = 'Passwords do not match';
        }

        // confirm password
        if(empty($data['confirm_password'])){
          $data['confirm_password_err'] = 'Please confirm password';
        } else{
          if($data['password'] != $data['confirm_password']){
            $data['confirm_password_err'] = 'Passwords do not match';
          }
        }

        // if the validation completes successfully
        if(empty($data['name_err']) && empty($data['email_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])){
          // now we can register the user
          // Hash the password
          $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

          // REGISTER THE USER
          if($this->usersModel->register($data)){
            // after registering the user, redirect him to the login page
            redirect('users/login');
            // die('User Registered');
          } else {
            die('Something went wrong');
          }   
        }
        else {
        $this->view('users/v_register', $data);
        }

      } else {
        // the form is not submitting
        $data =[
          'name' => '',
          'email' => '',
          'password' => '',
          'confirm_password' => '',

          'name_err' => '',
          'email_err' => '',
          'password_err' => '',
          'confirm_password_err' => ''
        ];

        // load the view
        $this->view('users/v_register', $data);
      }

  }

  public function login(){
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      // FORM IS SUBMITTING
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

      $data = [
        
        'email' => trim($_POST['email']),
        'password' => trim($_POST['password']),

        'email_err' => '',
        'password_err' => '',
      ];

      // VALIDATE
      // validate email
      if(empty($data['email'])){
        $data['email_err'] = 'Please Enter The Email' ; 
      }
      else {
        if($this->usersModel->findUserByEmail($data['email'])){
          // user is found
        }
        else{
          // user is not found
          $data['email_err'] = 'User Not Found';
        }
      }

      // validte password
      if(empty($data['password'])){
        $data['password_err'] = 'Please Enter The Password';
      }

      // If no error found login the user
      if(empty($data['email_err']) && empty($data['password_err'])){
        // log the user
        $loggedUser = $this->usersModel->login($data['email'], $data['password']);

        if($loggedUser){
          // user is authenticated
          // can create user sesstions
          // redirect('pages/index');
          die('User Authenticated');
        }
        else{
          $data['password_err'] = 'Password Incorrect';

          // load view with erros
          $this->view('users/v_login', $data);

        }
      }
      else {
        // load view with error
        $this->view('users/v_login', $data);
        
      }

    }
    else {
      // initial form 
      $data = [
        
        'email' => '',
        'password' => '',

        'email_err' => '',
        'password_err' => '',
      ];
      // load the view
    $this->view('users/v_login', $data);
    }
  }

}
?>