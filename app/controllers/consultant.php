  <?php
  class consultant extends Controller {
    
      private $consultantModel;
      private $caregiversModel;

      public function __construct() {
            if(!isLoggedIn()) {
                $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
                redirect('users/login');
            }
          $this->consultantModel = $this->model('M_Consultant');
          $this->caregiversModel = $this->model('M_Caregivers'); 
      }
      

      public function index(){
        $this->viewRequests();
      }

    // public function consultantview(){
    //     $this->view('consultant/c_view');
    // }
  
    // public function consultantedit(){
    //     $this->view('consultant/c_edit');
    // }

//     public function consultantland(){
//         $this->view('consultant/c_land');
// }
    

  //   public function consultantchat(){
  //     $this->view('consultant/v_chat');
  // }

  // public function consultanthistory(){
  //   $this->view('consultant/apphistory');
  // }
    
//   public function request(){
//     $data = [];
//     $this->view('caregiver/v_request',$data);
//  }

//  public function viewrequestinfo(){
       
//   $this->view('caregiver/v_reqinfo');
// }

public function register() {
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $data = [
            'username' => trim($_POST['username']),
            'email' => trim($_POST['email']),
            'nic_no' => trim($_POST['nic_no']),
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
            'nic_no_err' => '',
            'gender_err' => '',
            'dob_err' => '',
            'contact_info_err' => '',
            'slmc_no_err' => '',
            'specialization_err' => '',
            'password_err' => '',
            'confirm_password_err' => '',
            'documents_err' => ''
        ];

        // Validation
        $this->validateRegistrationData($data);

        // Handle document upload
        $uploadDir = '/documents/approvalDocuments/';
        $uploadResult = uploadMultipleFiles($data['documents'], $uploadDir);
        $uploadedFiles = $uploadResult['uploadedFiles'];

        if (empty($uploadedFiles)) {
            $data['documents_err'] = 'Please add the documents';
        } elseif (!empty($uploadResult['errors'])) {
            $data['documents_err'] = implode(', ', $uploadResult['errors']);
        }

        // Check for validation errors
        if ($this->hasNoValidationErrors($data)) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

            if ($this->consultantsModel->register($data, $uploadedFiles)) {
                redirect('users/login');
            } else {
                die('Registration failed');
            }
        }

        $this->view('users/v_consultant_register', $data);
    } else {
        // Initial form load
        $this->view('users/v_consultant_register', $this->getEmptyRegistrationData());
    }
}

private function validateRegistrationData(&$data) {
    if(empty($data['username'])) {
        $data['username_err'] = 'Please enter username';
    }

    if(empty($data['email'])) {
        $data['email_err'] = 'Please enter email';
    } elseif($this->consultantsModel->findUserByEmail($data['email'])) {
        $data['email_err'] = 'Email is already taken';
    }

    $this->validateNIC($data);
    $this->validateBasicInfo($data);
    $this->validatePassword($data);
}

private function validateNIC(&$data) {
    if (empty($data['nic_no'])) {
        $data['nic_no_err'] = 'Please enter your NIC number';
        return;
    }

    $patternPre2016 = '/^\d{9}[VXvx]$/';
    $patternPost2016 = '/^\d{12}$/';

    if (!preg_match($patternPre2016, $data['nic_no']) && !preg_match($patternPost2016, $data['nic_no'])) {
        $data['nic_no_err'] = 'Invalid NIC number format';
    }
}

private function validateBasicInfo(&$data) {
    if(empty($data['gender'])) {
        $data['gender_err'] = 'Please add gender';
    }

    $this->validateDOB($data);

    if (empty($data['contact_info'])) {
        $data['contact_info_err'] = 'Please enter your contact number';
    } elseif (!preg_match('/^[0-9]{10}$/', $data['contact_info'])) {
        $data['contact_info_err'] = 'Contact number should be a 10-digit number';
    }

    if(empty($data['slmc_no'])) {
        $data['slmc_no_err'] = 'Please enter SLMC registration number';
    }

    if(empty($data['specialization'])) {
        $data['specialization_err'] = 'Please enter specialization';
    }
}

private function validateDOB(&$data) {
    if(empty($data['dob'])) {
        $data['dob_err'] = 'Please add a date of birth';
        return;
    }

    if (!$this->consultantsModel->validateDate($data['dob'])) {
        $data['dob_err'] = 'Invalid date format. Please use YYYY-MM-DD';
        return;
    }

    $dob = new DateTime($data['dob']);
    $today = new DateTime();
    $age = $today->diff($dob)->y;

    if($age < 18) {
        $data['dob_err'] = 'You must be at least 18 years old to register';
    }
}

private function validatePassword(&$data) {
    if(empty($data['password'])) {
        $data['password_err'] = 'Please enter password';
    } elseif(strlen($data['password']) < 6) {
        $data['password_err'] = 'Password must be at least 6 characters';
    } elseif(!preg_match('/[A-Z]/', $data['password'])) {
        $data['password_err'] = 'Password must contain at least one Uppercase Letter';
    } elseif(!preg_match('/[a-z]/', $data['password'])) {
        $data['password_err'] = 'Password must contain at least one Lowercase Letter';
    } elseif(!preg_match('/[0-9]/', $data['password'])) {
        $data['password_err'] = 'Password must contain at least one Number';
    }

    if(empty($data['confirm_password'])) {
        $data['confirm_password_err'] = 'Please confirm password';
    } elseif($data['password'] != $data['confirm_password']) {
        $data['confirm_password_err'] = 'Passwords do not match';
    }
}

private function hasNoValidationErrors($data) {
    return empty($data['username_err']) &&
           empty($data['email_err']) &&
           empty($data['nic_no_err']) &&
           empty($data['password_err']) &&
           empty($data['confirm_password_err']) &&
           empty($data['gender_err']) &&
           empty($data['dob_err']) &&
           empty($data['contact_info_err']) &&
           empty($data['slmc_no_err']) &&
           empty($data['specialization_err']) &&
           empty($data['documents_err']);
}

private function getEmptyRegistrationData() {
    return [
        'username' => '',
        'email' => '',
        'nic_no' => '',
        'gender' => '',
        'dob' => '',
        'contact_info' => '',
        'slmc_no' => '',
        'specialization' => '',
        'password' => '',
        'confirm_password' => '',
        'username_err' => '',
        'email_err' => '',
        'nic_no_err' => '',
        'gender_err' => '',
        'dob_err' => '',
        'contact_info_err' => '',
        'slmc_no_err' => '',
        'specialization_err' => '',
        'password_err' => '',
        'confirm_password_err' => '',
        'documents_err' => ''
    ];
}

public function viewmyProfile(){
    $email = $_SESSION['user_email'];
    $profile = $this->consultantModel->showConsultantProfile($email);
    $rating = $this->consultantModel->getAvgRating($email);
    $reviews = $this->consultantModel->getReviews($email);

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

  $this->view('consultant/v_consultantprofile',$data);
}

public function editmyProfile() {
    if (!isset($_SESSION['user_id'])) {
        redirect('users/login');
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Sanitize POST data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        //Handle profile picture
        $profile = $this->consultantModel->showConsultantProfile($_SESSION['user_email']);
        $profilePicture = $_FILES['profile_picture'] ?? null;
        $currentProfilePicture = $profile->profile_picture ?? null;
        
        // Handle multi-select fields
        $selectedRegions = isset($_POST['available_regions']) ? implode(',', $_POST['available_regions']) : '';
        $selectedSpecializations = isset($_POST['specializations']) ? implode(',', $_POST['specializations']) : '';

        $data = [
            'user_id' => $_SESSION['user_id'],
            'email' => $_SESSION['user_email'],
            'profile' => $profile,
            'username' => trim($_POST['username']),
            'address' => trim($_POST['address']),
            'contact_info' => trim($_POST['contact_info']),
            'specialty' => $selectedSpecializations, // Now properly handled as array
            'qualification' => trim($_POST['qualification']),
            'available_region' => $selectedRegions, // Now properly handled as array
            'payment_per_hour' => trim($_POST['payment_per_hour'] ?? ''),
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
            
            if($this->consultantModel->updateConsultantProfile($data)){
                redirect('consultant/viewmyProfile');
            } else {
                // Handle update failure
                die('Something went wrong');
            }
        } else {
            $this->view('consultant/v_editConsultantProfile', $data);
        }
    } else {
        // GET request - load existing data
        $email = $_SESSION['user_email'];
        $profile = $this->consultantModel->showConsultantProfile($email);
        
        $data = [
            'profile' => $profile,
            'email' => $_SESSION['user_email'],
            'username' => $profile->username ?? '',
            'address' => $profile->address ?? '',
            'contact_info' => $profile->contact_info ?? '',
            'specialty' => $profile->specializations ?? '',
            'qualification' => $profile->qualifications ?? '',
            'available_region' => $profile->available_regions ?? '',
            'payment_per_hour' => $profile->payment_details ?? '',
            'bio' => $profile->bio ?? '',
            'profile_picture' => $profile->profile_picture ?? '',
            'username_err' => '',
            'address_err' => '',
            'contact_info_err' => ''
        ];

        $this->view('consultant/v_editConsultantProfile', $data);
    }
}

public function viewConsultantProfile($id = null) {
    if ($id === null) {
        redirect('users/viewConsultants');
    }
    
    $consultant = $this->consultantModel->getConsultantById($id);
    
    if (!$consultant) {
        redirect('users/viewConsultants');
    }
    
    $data = [
        'consultant' => $consultant,
        'title' => 'Consultant Profile'
    ];
    
    $this->view('consultant/v_consultantprofile', $data);
  }



  public function viewRequests()
{
    
    // Check if user is logged in as caregiver
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'Consultant') {
        redirect('users/login');
    }
   

    // Get caregiver ID from session
    $consultantID = $_SESSION['user_id'];
    
    // Get all requests for this caregiver
    $consultRequests = $this->consultantModel->getAllConsultRequestsByConsultant($consultantID);
    
    $data = [
        'requests' => $consultRequests
    ];
    
    
    $this->view('consultant/v_viewRequests', $data);
}

public function viewreqinfo($requestId){
       
    $consultRequest = $this->consultantModel->getFullConsultRequestInfo($requestId);

    if (!$consultRequest) {
        flash('request_not_found', 'Request not found');
        redirect('consultant/viewRequests');
    }

    $this->view('consultant/v_viewRequestInfo', $consultRequest);
    }


public function acceptRequest($request_id) {
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      // Get caregiver_id from session
      $consultantId = $_SESSION['user_id'];

      // Verify request belongs to this caregiver
      $request = $this->consultantModel->getRequestById($request_id);
      if (!$request || $request->consultant_id != $consultantId) {
          flash('request_message', 'Unauthorized access!', 'alert alert-danger');
          redirect('consultant/viewRequests');
          return;
      }

      // Update status
      if ($this->consultantModel->updateRequestStatus($request_id, 'accepted')) {
          flash('request_message', 'Request has been accepted.');
      } else {
          flash('request_message', 'Something went wrong. Try again.', 'alert alert-danger');
      }
      redirect('consultant/viewRequests');
  }
}

public function rejectRequest($request_id) {
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $consultantId = $_SESSION['user_id'];
      $request = $this->consultantModel->getRequestById($request_id);
      if (!$request || $request->consultant_id != $consultantId) {
          flash('request_message', 'Unauthorized access!', 'alert alert-danger');
          redirect('consultant/viewRequests');
          return;
      }

      if ($this->consultantModel->updateRequestStatus($request_id, 'rejected')) {
          flash('request_message', 'Request has been rejected.');
      } else {
          flash('request_message', 'Something went wrong. Try again.', 'alert alert-danger');
      }
      redirect('consultant/viewRequests');
  }
}


public function viewCareseeker($elder_id){
    $elderProfile=$this->consultantModel->getElderProfileById($elder_id);
    if (!$elderProfile) {
        flash('profile_not_found', 'Profile not found');
        redirect('caregivers/viewCaregivers');
    }
     $data = [
        'elderProfile' => $elderProfile,
    ];
    $this->view('consultant/v_careseekerProfile', $data);
 }


  public function consultantprofile(){
    $this->view('consultant/v_consultantprofile');
  }

  public function viewpatients(){
    $this->view('consultant/v_viewPatients');
  }

  public function consultantsession(){
    $this->view('consultant/v_consultantSession');
  }

  public function viewelderprofile(){
    $this->view('consultant/v_editElderProfile');
  }

  

  public function viewrequestinfo(){
    $this->view('consultant/v_viewRequestInfo');
  }

  public function viewpayments(){
    $this->view('consultant/v_viewPayments');
  }


  public function viewrateandreview(){
    $rateandreview = $this->consultantModel->getRateAndReviews();
    $data = [
      'title' => 'View Rate and Review',
        'rateandreview' => $rateandreview
    ];
    $this->view('consultant/v_rate&review', $data);
  }
  public function patientlist(){
    $this->view('consultant/v_patientList');
  }

  public function addreview()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Sanitize and validate inputs
        $data = [
            'rating' => trim($_POST['rating']),
            'review' => trim($_POST['review']),
            'rating_err' => '',
            'review_err' => ''
        ];
    
        // Validate rating
        if(empty($data['rating'])) {
            $data['rating_err'] = 'Please enter a rating';
        }
    
        // Validate review
        if(empty($data['review'])) {
            $data['review_err'] = 'Please enter a review';
        }
    
        // If validation passes
        if(empty($data['rating_err']) && empty($data['review_err'])) {
            if($this->consultantModel->addReview($data)) {
                redirect('consultant/viewrateandreview');
            } else {
                die('Something went wrong');
            }
        } else {
            $this->view('consultant/v_addreview', $data);
        }
    } 
    else {
        $data = [
            'rating' => '',
            'review' => '',
            'rating_err' => '',
            'review_err' => ''
        ];
        $this->view('consultant/v_addreview', $data);
    }
  }
  public function editreview($review_id) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = [
            'review_id' => $review_id,
            'rating' => trim($_POST['rating']),
            'review_text' => trim($_POST['review']),
            'rating_err' => '',
            'review_err' => ''
        ];

        // Validate rating
        if (empty($data['rating'])) {
            $data['rating_err'] = 'Please select a rating';
        }

        // Validate review
        if (empty($data['review_text'])) {
            $data['review_err'] = 'Please enter your review';
        }

        // Make sure no errors
        if (empty($data['rating_err']) && empty($data['review_err'])) {
            if ($this->consultantModel->editreview($data)) {
                redirect('consultant/viewrateandreview');
            }
        } else {
            // Load view with errors
            $this->view('consultant/v_editreview', $data);
        }
    } else {
        // Get existing review from database
        $review = $this->consultantModel->getReviewById($review_id);
        
        // Check if review exists
        if (!$review) {
            redirect('consultant/viewrateandreview');
        }

        $data = [
            'review_id' => $review_id,
            'rating' => $review->rating,
            'review_text' => $review->review_text,
            'rating_err' => '',
            'review_err' => ''
        ];

        $this->view('consultant/v_editreview', $data);
    }
}

public function deletereview($review_id) {
    if ($this->consultantModel->deleteReview($review_id)) {
        redirect('consultant/viewrateandreview');
    } else {
        die('Something went wrong');
    }
}

  // public function viewpaymentinfo(){
  //   $this->view('consultant/v_viewPaymentInfo');
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

}