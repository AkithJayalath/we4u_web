<?php
  class caregivers extends controller{

    private $caregiversModel;
    private $sheduleModel;
    public function __construct() {
      // Get current URL path
      $currentUrl = $_SERVER['REQUEST_URI'] ?? '';
      
      // Define allowed routes for non-logged-in users
      $allowedRoutes = [
          '/we4u/caregivers/register',
          '/we4u/users/login'
          // Add other public routes as needed
      ];
      
      // Only check authentication if not accessing a public route
      if (!in_array($currentUrl, $allowedRoutes)) {
          if (!isset($_SESSION['user_id'])) {
              redirect('users/login');
          } elseif ($_SESSION['user_role'] != 'Caregiver') {
              redirect('pages/permissionerror');
          }
      }
      
      // Always load the model (for both public and protected routes)
      $this->caregiversModel = $this->model('M_Caregivers');
      $this->sheduleModel = $this->model('M_Shedules');

    }
    
  

    public function index(){
      $this->viewRequests();
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
  

    public function isLoggedIn(){
      if(isset($_SESSION['user_id'])){ 
        return true;
      }else{
        return false;
      }
    }

    public function rateandreview(){
      $email = $_SESSION['user_email'];
      $reviews= $this->caregiversModel->getReviews($email);

      $data = [
        'reviews' => $reviews
      ];

      $this->view('caregiver/v_rate&review', $data);
  
      
    }

    

    public function submitReview(){
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Sanitize POST data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $data=[
          'reviewer_id' => $_SESSION['user_id'],
          'reviewed_user_id' => $_POST['reviewed_user_id'],
          
          'review_text' => trim($_POST['review_text']),
          'review_text_err' => ''
        ];

        if(empty($data['review_text'])){
          $data['review_text_err'] = 'Please leave a review';
        }

        if(empty($data['review_text_error'])){
          if($this->caregiversModel->submitReview($data)){
            echo json_encode(['success' => 'Review submitted successfully']);
            return;
          }
        }
        echo json_encode(['error' => 'Failed to submit review']);
    }
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
    if (!$this->isLoggedIn()) {
        redirect('users/login');
    }
    
    $caregiverId = $_SESSION['user_id'];
    $payments = $this->caregiversModel->getPaymentHistory($caregiverId);

    $data=[
        'payments' => $payments
        
    ];
       $this->view('caregiver/v_paymentHistory',$data);
    }

    public function request(){
      $data = [];
      $this->view('caregiver/v_request',$data);
   }

   public function viewpayments(){
    

    
    
   
    

    $this->view('caregiver/v_viewPayments', $data);
}
  

   public function viewreqinfo($requestId){
       
    $careRequest = $this->caregiversModel->getFullCareRequestInfo($requestId);

    if (!$careRequest) {
        flash('request_not_found', 'Request not found');
        redirect('careseeker/viewRequests');
    }

    $this->view('caregiver/v_viewRequestInfo', $careRequest);
    }

   public function norequest(){
       
       $this->view('caregiver/v_norequest');
    }

    public function viewCareseeker($elder_id){
      $elderProfile=$this->caregiversModel->getElderProfileById($elder_id);
      if (!$elderProfile) {
          flash('profile_not_found', 'Profile not found');
          redirect('caregivers/viewCaregivers');
      }
       $data = [
          'elderProfile' => $elderProfile,
      ];
      $this->view('caregiver/v_careseekerProfile', $data);
   }

   public function viewmyProfile(){
      $email = $_SESSION['user_email'];
      $profile = $this->caregiversModel->showCaregiverProfile($email);
      $rating = $this->caregiversModel->getAvgRating($email);
      $reviews = $this->caregiversModel->getReviews($email);

      //calculate age
      $dob = new DateTime($profile->date_of_birth);
      $today = new DateTime();
      $age = $today->diff($dob)->y;

      $data = [
          'profile' => $profile,
          'age' => $age,
          'rating' => $rating,
          'reviews' => $reviews

      ];

    $this->view('caregiver/v_caregiverProfile',$data);
  }

  public function editmyProfile() {
    if (!$this->isLoggedIn()) {
        redirect('users/login');
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Sanitize POST data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        //Handle profile picture
        $profile = $this->caregiversModel->showCaregiverProfile($_SESSION['user_email']);
        $profilePicture = $_FILES['profile_picture'] ?? null;
        $currentProfilePicture = $profile->profile_picture ?? null;
        
        // Handle multi-select fields
        $selectedSkills = isset($_POST['skills']) ? implode(',', $_POST['skills']) : '';
        $selectedRegions = isset($_POST['available_regions']) ? implode(',', $_POST['available_regions']) : '';
        $selectedSpecializations = isset($_POST['specializations']) ? implode(',', $_POST['specializations']) : '';

        $data = [
            'user_id' => $_SESSION['user_id'],
            'email' => $_SESSION['user_email'],
            'profile' => $profile,
            'username' => trim($_POST['username']),
            'address' => trim($_POST['address']),
            'contact_info' => trim($_POST['contact_info']),
            'caregiver_type' => trim($_POST['caregiver_type']),
            'specialty' => $selectedSpecializations, // Now properly handled as array
            'skills' => $selectedSkills,
            'qualification' => trim($_POST['qualification']),
            'available_region' => $selectedRegions, // Now properly handled as array
            'payment_per_session' => trim($_POST['payment_per_session'] ?? ''),
            'payment_per_day' => trim($_POST['payment_per_visit'] ?? ''),
            'bio' => trim($_POST['bio']),
            'profile_picture' => $currentProfilePicture, // Default to current picture
            'profile_picture_name' => time().'_'.($_FILES['profile_picture']['name'] ?? ''),
            'username_err' => '',
            'address_err' => '',
            'contact_info_err' => '',
            'profile_picture_err' => ''
        ];

        // Validate inputs (same as before)
        if(empty($data['username'])){
            $data['username_err'] = 'Please enter username';
        }
        if(empty($data['address'])){
            $data['address_err'] = 'Please enter address';
        }
        if(empty($data['contact_info'])){
            $data['contact_info_err'] = 'Please enter contact info';
        } elseif(!preg_match('/^[0-9]{10}$/', $data['contact_info'])) {
            $data['contact_info_err'] = 'Contact number should be a 10-digit number';
        }

        // Handle profile picture upload
        if ($profilePicture && $profilePicture['error'] === 0) {
            $validImageTypes = ["image/jpeg", "image/png", "image/gif"];
            
            if (in_array($profilePicture['type'], $validImageTypes)) {
                $newFileName = uniqid() . '_' . $profilePicture['name'];
                if (uploadImage($profilePicture['tmp_name'], $newFileName, '/images/profile_imgs/')) {
                    $data['profile_picture'] = $newFileName;
                } else {
                    $data['profile_picture_err'] = 'Failed to upload profile picture';
                }
            } else {
                $data['profile_picture_err'] = 'Please upload a valid image file (JPEG, PNG, GIF)';
            }
        }

        if(empty($data['username_err']) && empty($data['address_err']) && 
           empty($data['contact_info_err']) && empty($data['profile_picture_err'])){
            
            if($this->caregiversModel->updateCaregiverProfile($data)){
                redirect('caregivers/viewmyProfile');
            } else {
                // Handle update failure
                die('Something went wrong');
            }
        } else {
            $this->view('caregiver/v_editCaregiverProfile', $data);
        }
    } else {
        // GET request - load existing data
        $email = $_SESSION['user_email'];
        $profile = $this->caregiversModel->showCaregiverProfile($email);
        
        $data = [
            'profile' => $profile,
            'email' => $_SESSION['user_email'],
            'username' => $profile->username ?? '',
            'address' => $profile->address ?? '',
            'contact_info' => $profile->contact_info ?? '',
            'caregiver_type' => $profile->caregiver_type ?? '',
            'specialty' => $profile->specialty ?? '',
            'skills' => $profile->skills ?? '',
            'qualification' => $profile->qualification ?? '',
            'available_region' => $profile->available_region ?? '',
            'payment_per_session' => $profile->price_per_session ?? '',
            'payment_per_day' => $profile->price_per_day ?? '',
            'bio' => $profile->bio ?? '',
            'profile_picture' => $profile->profile_picture ?? '',
            'username_err' => '',
            'address_err' => '',
            'contact_info_err' => ''
        ];

        $this->view('caregiver/v_editCaregiverProfile', $data);
    }
}
public function viewCaregivers() {
  // Get all caregivers from the database
  $caregivers = $this->caregiversModel->getAllCaregivers();
  
  // Get unique regions for the filter dropdown
  $regions = $this->caregiversModel->getUniqueRegions();
  
  $data = [
      'caregivers' => $caregivers,
      'regions' => $regions,
      'title' => 'View Caregivers'
  ];
  
  $this->view('careseeker/viewCaregivers', $data);
}

public function viewCaregiverProfile($id = null) {
  if ($id === null) {
      redirect('careseeker/viewCaregivers');
  }
  
  $caregiver = $this->caregiversModel->getCaregiverById($id);
  
  if (!$caregiver) {
      redirect('careseeker/viewCaregivers');
  }
  
  $data = [
      'caregiver' => $caregiver,
      'title' => 'Caregiver Profile'
  ];
  
  $this->view('careseeker/viewCaregiverProfile', $data);
}


public function viewRequests()
{
    
    // Check if user is logged in as caregiver
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'Caregiver') {
        redirect('users/login');
    }
   

    // Get caregiver ID from session
    $caregiverId = $_SESSION['user_id'];
    
    // Get all requests for this caregiver
    $careRequests = $this->caregiversModel->getAllCareRequestsByCaregiver($caregiverId);
    
    $data = [
        'requests' => $careRequests
    ];
    
    
    $this->view('caregiver/v_request', $data);
}


public function acceptRequest($request_id) {
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      // Get caregiver_id from session
      $caregiverId = $_SESSION['user_id'];

      // Verify request belongs to this caregiver
      $request = $this->caregiversModel->getRequestById($request_id);
      if (!$request || $request->caregiver_id != $caregiverId) {
          flash('request_message', 'Unauthorized access!', 'alert alert-danger');
          redirect('caregivers/viewRequests');
          return;
      }

      // Update status
      if ($this->caregiversModel->updateRequestStatus($request_id, 'accepted')) {
          flash('request_message', 'Request has been accepted.');
      } else {
          flash('request_message', 'Something went wrong. Try again.', 'alert alert-danger');
      }
      redirect('caregivers/viewRequests');
  }
}

public function rejectRequest($request_id) {
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $caregiverId = $_SESSION['user_id'];
      $request = $this->caregiversModel->getRequestById($request_id);
      if (!$request || $request->caregiver_id != $caregiverId) {
          flash('request_message', 'Unauthorized access!', 'alert alert-danger');
          redirect('caregivers/viewRequests');
          return;
      }

      if ($this->caregiversModel->updateRequestStatus($request_id, 'rejected')) {
          flash('request_message', 'Request has been rejected.');
      } else {
          flash('request_message', 'Something went wrong. Try again.', 'alert alert-danger');
      }
      redirect('caregivers/viewRequests');
  }
}



//cancel request
public function cancelRequest($requestId, $flag = false) {
  date_default_timezone_set('Asia/Colombo'); // or your relevant timezone

  $request = $this->caregiversModel->getRequestById($requestId);

  if (!$request) {
      flash('request_error', 'Invalid request or service.');
      redirect('caregivers/viewRequests');
      return;
  }

  $now = new DateTime();
  $startDateTime = $this->getStartDateTime($request); // using slot-aware logic
  $canCancel = false;
  $shouldFlag = $flag; // Flag parameter from URL

  // Check if service has already started
  if ($now > $startDateTime) {
      flash('request_error', 'Cannot cancel a service that has already started.');
      redirect('caregivers/viewRequests');
      return;
  }

  // Calculate hours left before service starts
  $hoursLeft = ($startDateTime->getTimestamp() - $now->getTimestamp()) / 3600;

  if ($hoursLeft >= 24) {
      // More than 24 hours before - can cancel without penalty
      $canCancel = true;
      $shouldFlag = false; // Override flag parameter - no flag needed
  } elseif ($hoursLeft >= 12) {
      // Between 12-24 hours - can cancel but will be flagged
      $canCancel = true;
      $shouldFlag = true; // Ensure flag is set
  } else {
      // Less than 12 hours before - cannot cancel
      flash('request_error', 'Requests cannot be cancelled less than 12 hours before start time.');
      redirect('caregivers/viewRequests');
      return;
  }

  // Check if there's a payment to refund
  $refundAmount = 0;
  if (isset($request->is_paid) && $request->is_paid && isset($request->payment_details)) {
      $refundAmount = $request->payment_details; // Full refund
  }

  // Process the cancellation
  $result = $this->caregiversModel->cancelRequestWithRefund($requestId, $refundAmount, $shouldFlag);

  if ($result) {
      $flagMessage = $shouldFlag ? " A cancellation flag has been added to your account." : "";
      flash('request_success', 'Request cancelled successfully.' . $flagMessage);
  } else {
      flash('request_error', 'Failed to cancel the request. Please try again.');
  }
  
  redirect('caregivers/viewRequests');
}

// Helper function to determine start time based on request details
private function getStartDateTime($request) {
  $date = new DateTime($request->start_date);
  
  if ($request->duration_type === 'long-term') {
      $date->setTime(8, 0);
  } else {
      // Decode time slots
      $slots = json_decode($request->time_slots);
      
      // Set time based on slot
      if (is_array($slots)) {
          if (in_array('morning', $slots)) {
              $date->setTime(8, 0);
          } elseif (in_array('evening', $slots) || in_array('afternoon', $slots)) {
              $date->setTime(13, 0);
          } elseif (in_array('overnight', $slots)) {
              $date->setTime(20, 0);
          } elseif (in_array('full_day', $slots) || in_array('full-day', $slots)) {
              $date->setTime(8, 0);
          } else {
              // Default if no specific time slot matched
              $date->setTime(8, 0);
          }
      } else {
          // Default time if slots couldn't be decoded
          $date->setTime(8, 0);
      }
  }

  return $date;
}

    public function viewMyCalendar(){
      // Check if user is logged in
      if(!$this->isLoggedIn()){
          redirect('users/login');
      }
      
      // Get caregiver ID from session
      $id = $_SESSION['user_id'];
      
      // Get all schedules for the caregiver
      $shortShedules = $this->sheduleModel->getAllShortShedulesForCaregiver($id);
      $longShedules = $this->sheduleModel->getAllLongShedulesForCaregiver($id);
      
      // Prepare data for the view
      $data = [
          'shortShedules' => $shortShedules,
          'longShedules' => $longShedules
      ];
      
      // Load the calendar view
      $this->view('calendar/v_cgcalendar', $data);
  }
  
  }
?>