<?php
  class caregivers extends controller{

    private $caregiversModel;
    public function __construct(){ 
      $this->caregiversModel = $this->model('M_Caregivers');
    }

    public function index(){
      $this->request();
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
            'national_id' => trim($_POST['national_id']),
            'gender' => trim($_POST['gender']),
            'dob' => trim($_POST['dob']),
            'address' => trim($_POST['address']),
            'contact_info' => trim($_POST['contact_info']),
            'type_of_caregiver' => trim($_POST['type_of_caregiver']),
            'password' => trim($_POST['password']),
            'confirm_password' => trim($_POST['confirm_password']),
            'documents' => $_FILES['documents'], 
  
            'username_err' => '',
            'email_err' => '',
            'national_id_err' => '',
            'gender_err' => '',
            'dob_err' =>'',
            'address_err' => '',
            'contact_info_err' => '',
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
            if($this->caregiversModel->findUserByEmail($data['email'])){
              $data['email_err'] = 'Email is already taken';
            }
          }





          //validate national_id
          if (empty($data['national_id'])) {
            $data['national_id_err'] = 'Please enter your NIC number';
        } else {
            // NIC validation
            $nic = $data['national_id'];
        
            // Check for pre-2016 NIC format (9 digits + V or X)
            $patternPre2016 = '/^\d{9}[VXvx]$/';
        
            // Check for post-2016 NIC format (12 digits)
            $patternPost2016 = '/^\d{12}$/';
        
            if (!preg_match($patternPre2016, $nic) && !preg_match($patternPost2016, $nic)) {
                $data['national_id_err'] = 'Invalid NIC number format';
            }
        }

          // validate gender
          if(empty($data['gender'])){
            $data['gender_err'] = 'Please add gender';
          }

          // validate dob
          if(empty($data['dob'])){
            $data['dob_err'] = 'Please add a date of birth';
        } elseif (!$this->caregiversModel->validateDate($data['dob'])) { 
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
         if(empty($data['type_of_caregiver'])){
          $data['type_of_caregiver_err'] = 'Please add type';
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
     empty($data['national_id_err']) && empty($data['contact_info_err']) && empty($data['address_err']) && 
     empty($data['type_of_caregiver_err']) && empty($data['documents_err'])) {

     // Hash the password
     $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

     // Register the user
     if ($this->caregiversModel->register($data, $uploadedFiles)) {
         redirect('users/login'); // Redirect to login on success
     } else {
         die('Something went wrong');
     }
 } else {
     // Load view with errors
     $this->view('users/v_caregiver_register', $data);
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
     'type_of_caregiver' => '',
     'password' => '',
     'confirm_password' => '',
     'username_err' => '',
     'email_err' => '',
     'national_id_err' => '',
     'gender_err' => '',
     'dob_err' => '',
     'address_err' => '',
     'contact_info_err' => '',
     'type_of_caregiver_err' => '',
     'password_err' => '',
     'confirm_password_err' => '',
     'documents_err' => ''
 ];

 // Load registration form view
 $this->view('users/v_caregiver_register', $data);
}
    }

    public function rateandreview(){
      $this->view('caregiver/v_rate&review');
  
      
    }

    public function caregivingHistory(){
      $this->view('caregiver/v_cghistory');
  
      
    }

    public function addPaymentMethod() {
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          // Sanitize and validate inputs
          $data = [
              'email' => $_SESSION['user_email'],
              'mobile_number' => trim($_POST['mobile_number']),
              'account_holder_name' => trim($_POST['account_holder_name']),
              'bank_name' => trim($_POST['bank_name']),
              'branch_name' => trim($_POST['branch_name']),
              'account_number' => trim($_POST['account_number']),
              'payment_type_st' => trim($_POST['payment_type_st']),
              'payment_type_lt' => trim($_POST['payment_type_lt']),
              'advance_amount' => trim($_POST['advance_amount']),
              'mobile_number_err' => '',
              'account_holder_name_err' => '',
              'bank_name_err' => '',
              'branch_name_err' => '',
              'account_number_err' => ''
          ];
  
          // Validate mobile number
          if(empty($data['mobile_number'])) {
              $data['mobile_number_err'] = 'Please enter mobile number';
          } elseif(!preg_match('/^[0-9]{10}$/', $data['mobile_number'])) {
              $data['mobile_number_err'] = 'Invalid mobile number format';
          }
  
          // Validate account holder name
          if(empty($data['account_holder_name'])) {
              $data['account_holder_name_err'] = 'Please enter account holder name';
          }
  
          // Validate bank name
          if(empty($data['bank_name'])) {
              $data['bank_name_err'] = 'Please enter bank name';
          }
  
          // Validate branch name
          if(empty($data['branch_name'])) {
              $data['branch_name_err'] = 'Please enter branch name';
          }
  
          // Validate account number
          if(empty($data['account_number'])) {
              $data['account_number_err'] = 'Please enter account number';
          } elseif(!preg_match('/^[0-9]{9,18}$/', $data['account_number'])) {
              $data['account_number_err'] = 'Invalid account number format';
          }
  
          // Check for any errors
          if(empty($data['mobile_number_err']) && 
            empty($data['account_holder_name_err']) && 
            empty($data['bank_name_err']) && 
            empty($data['branch_name_err']) && 
            empty($data['account_number_err'])) {
              
              if ($this->caregiversModel->addPaymentMethod($data)) {
                  redirect('caregivers/PaymentMethod');
              }
          } else {
              // Load view with errors
              $this->view('caregiver/v_addPaymentMethod', $data);
          }
      } else {
          // Display the add payment method form
          $this->view('caregiver/v_addPaymentMethod');
      }
}

// public function updatePaymentMethod() {
    //     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //         $data = [
    //             'email' => $_SESSION['user_email'],
    //             'mobile_number' => trim($_POST['mobile_number']),
    //             'account_holder_name' => trim($_POST['account_holder_name']),
    //             'bank_name' => trim($_POST['bank_name']),
    //             'branch_name' => trim($_POST['branch_name']),
    //             'account_number' => trim($_POST['account_number']),
    //             'payment_type_st' => trim($_POST['payment_type_st']),
    //             'payment_type_lt' => trim($_POST['payment_type_lt']),
    //             'advance_amount' => trim($_POST['advance_amount'])
    //         ];

    //         if ($this->caregiversModel->updatePaymentMethod($data)) {
    //             redirect('caregivers/paymentMethod');
    //         }
    //     }
    // }

    public function paymentMethod() {
      $email = $_SESSION['user_email'];
      $paymentMethod = $this->caregiversModel->getPaymentMethod($email);
      
      $data = [
          'paymentMethod' => $paymentMethod,
          'email' => $email
      ];
      
      $this->view('caregiver/v_paymentMethod', $data);
  }

    public function updatePaymentMethod() {
      $existingPaymentMethod = $this->caregiversModel->getPaymentMethod($_SESSION['user_email']);
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          // Sanitize and validate inputs
          $data = [
              'email' => $_SESSION['user_email'],
              'mobile_number' => trim($_POST['mobile_number']),
              'account_holder_name' => trim($_POST['account_holder_name']),
              'bank_name' => trim($_POST['bank_name']),
              'branch_name' => trim($_POST['branch_name']),
              'account_number' => trim($_POST['account_number']),
              'payment_type_st' => trim($_POST['payment_type_st']),
              'payment_type_lt' => trim($_POST['payment_type_lt']),
              'advance_amount' => trim($_POST['advance_amount']),
              'mobile_number_err' => '',
              'account_holder_name_err' => '',
              'bank_name_err' => '',
              'branch_name_err' => '',
              'account_number_err' => '',
              'paymentMethod' => $existingPaymentMethod
          ];
  
          // Validate mobile number
          if(empty($data['mobile_number'])) {
              $data['mobile_number_err'] = 'Please enter mobile number';
          } elseif(!preg_match('/^[0-9]{10}$/', $data['mobile_number'])) {
              $data['mobile_number_err'] = 'Invalid mobile number format';
          }
  
          // Validate account holder name
          if(empty($data['account_holder_name'])) {
              $data['account_holder_name_err'] = 'Please enter account holder name';
          }
  
          // Validate bank name
          if(empty($data['bank_name'])) {
              $data['bank_name_err'] = 'Please enter bank name';
          }
  
          // Validate branch name
          if(empty($data['branch_name'])) {
              $data['branch_name_err'] = 'Please enter branch name';
          }
  
          // Validate account number
          if(empty($data['account_number'])) {
              $data['account_number_err'] = 'Please enter account number';
          } elseif(!preg_match('/^[0-9]{9,18}$/', $data['account_number'])) {
              $data['account_number_err'] = 'Invalid account number format';
          }
  
          // Check for any errors
          if(empty($data['mobile_number_err']) && 
             empty($data['account_holder_name_err']) && 
             empty($data['bank_name_err']) && 
             empty($data['branch_name_err']) && 
             empty($data['account_number_err'])) {
              
              if ($this->caregiversModel->updatePaymentMethod($data)) {
                  redirect('caregivers/paymentMethod');
              }
          } else {
              // Load view with errors
              $this->view('caregiver/v_paymentMethod', $data);
          }
      }
    }
  

    public function deletePaymentMethod() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_SESSION['user_email'];
            if ($this->caregiversModel->deletePaymentMethod($email)) {
                redirect('caregivers/paymentMethod');
            }
        }
    }
    
   public function paymentHistory(){
       $data = [];
       $this->view('caregiver/v_paymentHistory',$data);
    }

    public function request(){
      $data = [];
      $this->view('caregiver/v_request',$data);
   }

   public function viewpayments(){
    $this->view('consultant/v_viewPayments');
  }

   public function viewreqinfo(){
       
       $this->view('caregiver/v_reqinfo');
    }

   public function norequest(){
       
       $this->view('caregiver/v_norequest');
    }

    public function viewCareseeker(){
       
      $this->view('caregiver/v_careseekerProfile');
   }

   public function viewmyProfile(){
       
    $this->view('caregiver/v_caregiverProfile');
 }

}
  

  
?>