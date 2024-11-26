
<?php
  class consultant extends controller{

    private $consultantModel;
    public function __construct(){
      $this->consultantModel = $this->model('M_Consultant');
    }

    public function consultantreg(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
          // Now the form is submitting
          // Value the data
          $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
  
          // INPUT DATA
          $data = [
            'username' => trim($_POST['username']),
            'email' => trim($_POST['email']),
            'national_id' => trim($_POST['national_id']),
            'gender' => trim($_POST['gender']),
            'dob' => trim($_POST['dob']),
            'address' => trim($_POST['address']),
            'contact_info' => trim($_POST['contact_info']),
            'type_of_consultant' => trim($_POST['type_of_consultant']),
            'password' => trim($_POST['password']),
            'confirm_password' => trim($_POST['confirm_password']),
            
            
            // g
  
            'username_err' => '',
            'email_err' => '',
            'national_id_err' => '',
            'gender_err' => '',
            'dob_err' =>'',
            'address_err' => '',
            'contact_info_err' => '',
            'type_of_consultant_err' => '',
            'password_err' => '',
            'confirm_password_err' => ''

          ];
  
          // validation part
          // validate username
          if(empty($data['username'])){
            $data['username_err'] = 'Please enter username';
          }
  
          // validate email
          if(empty($data['email'])){
            $data['email_err'] = 'Please enter email';
          }else{
            // check email
            if($this->consultantModel->findUserByEmail($data['email'])){
              $data['email_err'] = 'Email is already taken';
            }
          }
          // validate gender
          if(empty($data['gender'])){
            $data['gender_err'] = 'Please add gender';
          }

          // validate dob
          if(empty($data['dob'])){
            $data['dob_err'] = 'Please add a date of birth';
        } elseif (!$this->consultantModel->validateDate($data['dob'])) { 
            $data['dob_err'] = 'Invalid date format. Please use YYYY-MM-DD';
        }

        // Validate address
        if (empty($data['address'])) {
          $data['address_err'] = 'Please enter your address';
      }

       // Validate contact info
       if (empty($data['contact_info'])) {
        $data['contact_info_err'] = 'Please enter your contact number';
    } elseif (!preg_match('/^[0-9]{10}$/', $data['contact_info'])) {
        $data['contact_info_err'] = 'Contact number should be a 10-digit number';
    }


         // validate type of caregiver
         if(empty($data['type_of_consultant'])){
          $data['type_of_consultant_err'] = 'Please select type';
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

         
 // Handle documents upload using `uploadMultipleFiles` helper
//  $uploadDir = '/documents/approvalDocuments/';
//  $uploadResult = uploadMultipleFiles($data['documents'], $uploadDir);

//  // Store uploaded file names and check for any errors
//  $uploadedFiles = $uploadResult['uploadedFiles'];
//  if (empty($uploadedFiles)) {
//      $data['documents_err'] = 'Please add the documents';
//  } elseif (!empty($uploadResult['errors'])) {
//      $data['documents_err'] = implode(', ', $uploadResult['errors']);
//  }

 // Proceed if no errors
 if (empty($data['username_err']) && empty($data['email_err']) && empty($data['password_err']) && 
     empty($data['confirm_password_err']) && empty($data['gender_err']) && empty($data['dob_err']) && 
     empty($data['national_id_err']) && empty($data['contact_info_err']) && empty($data['address_err']) && 
     empty($data['type_of_consultant_err'])) {

     // Hash the password
     $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

     // Register the user
     if ($this->consultantModel->register($data)) {
         redirect('users/login'); // Redirect to login on success
     } else {
         die('Something went wrong');
     }
 } else {
     // Load view with errors
     $this->view('consultant/c_reg', $data);
 }
} else {
 // Initialize data for GET request (form load)
 $data = [
     'username' => '',
     'email' => '',
     'national_id' => '',
     'gender' => '',
     'dob' => '',
     'address' => '',
     'contact_info' => '',
     'type_of_consultant' => '',
     'password' => '',
     'confirm_password' => '',
     'username_err' => '',
     'email_err' => '',
     'national_id_err' => '',
     'gender_err' => '',
     'dob_err' => '',
     'address_err' => '',
     'contact_info_err' => '',
     'password_err' => '',
     'confirm_password_err' => '',

 ];

 // Load registration form view
 $this->view('consultant/c_reg', $data);
}
    }

  public function consultantview(){
      $this->view('consultant/c_view');
  }
  
  public function consultantedit(){
      $this->view('consultant/c_edit');
  }
  public function consultantland(){
      $this->view('consultant/c_land');
  }

  public function consultantchat(){
    $this->view('consultant/chat');
}


    
  }
    ?>