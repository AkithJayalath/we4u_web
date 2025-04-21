  <?php
  class consultant extends Controller {
    
      private $consultantModel;
      private $caregiversModel;

      public function __construct() {
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

//cancel careseeker request
public function cancelRequest($requestId, $flag = false) {
    date_default_timezone_set('Asia/Colombo'); // or your relevant timezone

    $request = $this->consultantModel->getRequestById($requestId);

    if (!$request) {
        flash('request_error', 'Invalid request or appointment.');
        redirect('consultant/viewRequests');
        return;
    }

    $now = new DateTime();
    $startDateTime = $this->getStartDateTime($request); // using appointment-aware logic
    $canCancel = false;
    $shouldFlag = $flag; // Flag parameter from URL

    // Check if appointment has already started
    if ($now > $startDateTime) {
        flash('request_error', 'Cannot cancel an appointment that has already started.');
        redirect('consultant/viewRequests');
        return;
    }

    // Calculate hours left before appointment starts
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
        flash('request_error', 'Appointments cannot be cancelled less than 12 hours before start time.');
        redirect('consultant/viewRequests');
        return;
    }

    // Check if there's a payment to refund
    $refundAmount = 0;
    if (isset($request->is_paid) && $request->is_paid && isset($request->payment_details)) {
        $refundAmount = $request->payment_details; // Full refund
    }

    // Process the cancellation
    $result = $this->consultantModel->cancelRequestWithRefund($requestId, $refundAmount, $shouldFlag);

    if ($result) {
        $flagMessage = $shouldFlag ? " A cancellation flag has been added to your account." : "";
        flash('request_success', 'Appointment cancelled successfully.' . $flagMessage);
    } else {
        flash('request_error', 'Failed to cancel the appointment. Please try again.');
    }
    
    redirect('consultant/viewRequests');
}

// Helper method to calculate start date/time from request data
private function getStartDateTime($request) {
    $date = new DateTime($request->appointment_date);
    
    // Parse time slot (expected format "HH:MM-HH:MM")
    if (isset($request->time_slot) && !empty($request->time_slot)) {
        $timeSlotParts = explode('-', $request->time_slot);
        if (count($timeSlotParts) >= 1) {
            $startTimeParts = explode(':', trim($timeSlotParts[0]));
            if (count($startTimeParts) >= 2) {
                $hours = (int)$startTimeParts[0];
                $minutes = (int)$startTimeParts[1];
                $date->setTime($hours, $minutes, 0);
                return $date;
            }
        }
    }
    
    // Default time if time slot couldn't be parsed
    $date->setTime(9, 0, 0);
    return $date;
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