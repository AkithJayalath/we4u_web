  <?php
  class consultant extends Controller {
    
      private $consultantModel;

      public function __construct() {
          $this->consultantModel = $this->model('M_Consultant');
      }

      // Add the required index method
      public function index() {
          // This is your default method
          // You can redirect to c_reg or show a different view
          $this->view('consultant/c_reg');
      }

      public function c_reg() {
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
              'specifications' => trim($_POST['specifications']),
              'qualifications' => trim($_POST['qualifications']),
            
              'username_err' => '',
              'email_err' => '',
              'national_id_err' => '',
              'gender_err' => '',
              'dob_err' =>'',
              'address_err' => '',
              'contact_info_err' => '',
              'type_of_consultant_err' => '',
              'password_err' => '',
              'confirm_password_err' => '',
              'specifications_err' => '',
              'qualifications_err' => ''
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
     'password' => '',
     'confirm_password' => '',
     'gender' => '',
     'dob' => '',
     'national_id' => '',
     'address' => '',
     'contact_info' => '',
     'type_of_consultant' => '',
     'specifications' => '',
     'qualifications' => '',
     // Add all error fields
     'username_err' => '',
     'email_err' => '',
     'password_err' => '',
     'confirm_password_err' => '',
     'gender_err' => '',
     'dob_err' => '',
     'national_id_err' => '',
     'address_err' => '',
     'contact_info_err' => '',
     'type_of_consultant_err' => '',
     'specifications_err' => '',
     'qualifications_err' => '',
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

  public function consultanthistory(){
    $this->view('consultant/apphistory');
  }
    
  public function consultantprofile(){
    $this->view('consultant/v_consultantprofile');
  }

  public function viewconsultant(){
    $this->view('consultant/v_viewConsultants');
  }

  public function consultantsession(){
    $this->view('consultant/v_consultantSession');
  }

  public function editelderprofile(){
    $this->view('consultant/v_editElderProfile');
  }

  public function viewrequests(){
    $this->view('consultant/v_viewRequests');
  }

  public function viewrequestinfo(){
    $this->view('consultant/v_viewRequestInfo');
  }

  public function viewpayments(){
    $this->view('consultant/v_viewPayments');
  }

  public function rateandreview(){
    $this->view('consultant/v_rate&review');
  }
  public function consultantlist(){
    $this->view('consultant/v_consultantList');
  }

}