<?php
  class consultants extends controller{

    private $consultantsModel;
    public function __construct(){
      $this->consultantsModel = $this->model('M_Consultantss');
    }

    public function register(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
          // Now the form is submitting
          // Value the data
          $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
  
          // INPUT DATA
          $data = [
            'username' => trim($_POST['username']),
            'email' => trim($_POST['email']),
            
            'gender' => trim($_POST['gender']),
            'dob' => trim($_POST['dob']),
            'contact_info' => trim($_POST['contact_info']),
            'slmc_no' => trim($_POST['slmc_no']),
            'specialization' => trim($_POST['specialization']),
            'password' => trim($_POST['password']),
            'confirm_password' => trim($_POST['confirm_password']),
            'documents' => $_FILES['documents'], 
  
            'username_err' => '',
            'email_err' => '',
            
            'gender_err' => '',
            'dob_err' =>'',
            'contact_info_err' => '',
            'slmc_no_err'=>'',
            'specialization_err'=>'',
            'type_of_caregiver_err' => '',
            'password_err' => '',
            'confirm_password_err' => '',
            'documents_err' => ''
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
            if($this->consultantsModel->findUserByEmail($data['email'])){
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
        } elseif (!$this->consultantsModel->validateDate($data['dob'])) { 
            $data['dob_err'] = 'Invalid date format. Please use YYYY-MM-DD';
        }else {
          // Calculate age from DOB
          $dob = new DateTime($data['dob']);
          $today = new DateTime();
          $age = $today->diff($dob)->y;
          
          if($age < 18) {
              $data['dob_err'] = 'You must be at least 18 years old to register';
          }
      }

        // Validate address
        

       // Validate contact info
       if (empty($data['contact_info'])) {
        $data['contact_info_err'] = 'Please enter your contact number';
    } elseif (!preg_match('/^[0-9]{10}$/', $data['contact_info'])) {
        $data['contact_info_err'] = 'Contact number should be a 10-digit number';
    }

    if(empty($data['slmc_no'])){
        $data['slmc_no_err'] = 'Please slmc registration no';
      }

    if(empty($data['specialization'])){
        $data['specialization_err'] = 'Please enter specialization';
      }

         // validate type of caregiver
         
  
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
 $uploadDir = '/documents/approvalDocuments/';
 $uploadResult = uploadMultipleFiles($data['documents'], $uploadDir);

 // Store uploaded file names and check for any errors
 $uploadedFiles = $uploadResult['uploadedFiles'];
 if (empty($uploadedFiles)) {
     $data['documents_err'] = 'Please add the documents';
 } elseif (!empty($uploadResult['errors'])) {
     $data['documents_err'] = implode(', ', $uploadResult['errors']);
 }

 // Proceed if no errors
 if (empty($data['username_err']) && empty($data['email_err']) && empty($data['password_err']) && 
     empty($data['confirm_password_err']) && empty($data['gender_err']) && empty($data['dob_err']) && 
     empty($data['contact_info_err']) &&  empty($data['slmc_no_err'])  && 
     empty($data['specialization_err']) && empty($data['documents_err'])) {

     // Hash the password
     $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

     // Register the user
     if ($this->consultantsModel->register($data, $uploadedFiles)) {
         redirect('users/login'); // Redirect to login on success
     } else {
         die('Something went wrong');
     }
 } else {
     // Load view with errors
     $this->view('users/v_consultant_register', $data);
 }
} else {
 // Initialize data for GET request (form load)
 $data = [
     'username' => '',
     'email' => '',
     
     'gender' => '',
     'dob' => '',
     
     'contact_info' => '',
     'slmc_no' => '',
     'specialization' => '',
     'password' => '',
     'confirm_password' => '',
     'username_err' => '',
     'email_err' => '',
     'gender_err' => '',
     'dob_err' => '',
     'address_err' => '',
     'contact_info_err' => '',
     'slmc_no_err' => '',
     'specialization_err' => '',
     'password_err' => '',
     'confirm_password_err' => '',
     'documents_err' => ''
 ];

 // Load registration form view
 $this->view('users/v_consultant_register', $data);
}
    }

  }

?>