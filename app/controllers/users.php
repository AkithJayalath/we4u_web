<?php
  class users extends controller{
    private $usersModel;
    private $caregiversModel;
    private $consultantsModel;
    public function __construct(){
      $this->usersModel = $this->model('M_Users');
      $this->caregiversModel = $this->model('M_Caregivers');
      $this->consultantsModel = $this->model('M_Consultant');
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
          'password' => trim($_POST['password']),
          'confirm_password' => trim($_POST['confirm_password']),

          'username_err' => '',
          'email_err' => '',
          'gender_err' => '',
          'dob_err' =>'',
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
          if($this->usersModel->findUserByEmail($data['email'])){
            $data['email_err'] = 'Email is already taken';
          }
        }
        // validate gender
        if(empty($data['gender'])){
          $data['gender_err'] = 'Please add gender';
        }

        if(empty($data['dob'])){
          $data['dob_err'] = 'Please add a date of birth';
      } elseif (!$this->usersModel->validateDate($data['dob'])) { 
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
        if(empty($data['username_err']) && empty($data['email_err']) && empty($data['password_err']) && empty($data['confirm_password_err']) && empty($data['gender_err']) && empty($data['dob_err'])){
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
          'username' => '',
          'email' => '',
          'gender' =>'',
          'dob' => '',
          'password' => '',
          'confirm_password' => '',

          'username_err' => '',
          'email_err' => '',
          'gender_err' => '',
          'dob_err' => '',
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
          // Check if user is deactivated
          if($loggedUser->is_deactive == 1){
            $data['email_err'] = 'Your account has been deactivated. Please contact support for assistance.';
            $this->view('users/v_login', $data);
            return;
          }
          
          // Check if user is a Caregiver or Consultant and needs approval
          if ($loggedUser->role == 'Caregiver' || $loggedUser->role == 'Consultant') {
            $approvalStatus = $this->usersModel->getApprovalStatus($loggedUser->user_id, $loggedUser->role);

            if ($approvalStatus === 'pending') {
                // Approval pending - show message
                $data['email_err'] = 'Your account is pending approval. Please wait for confirmation.';
                $this->view('users/v_login', $data);
                return;
            } elseif ($approvalStatus === 'rejected') {
                // Account rejected - show message
                $data['email_err'] = 'Your account has been rejected. Please contact support for further assistance.';
                $this->view('users/v_login', $data);
                return;
            }
          }
          
          // If approved and not deactivated, create session
          $this->createUserSession($loggedUser);
        }
        else{
          $data['password_err'] = 'Password Incorrect';
          // load view with errors
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

  public function createUserSession($user){
    flash('success', 'Logged in successfully');
    
    $_SESSION ['user_id']=$user->user_id;
    $_SESSION ['user_profile_picture'] = $user->profile_picture;
    $_SESSION ['user_email']=$user->email;
    $_SESSION ['user_name']=$user->username;
    $_SESSION['user_role'] = $user->role;

    if (isset($_SESSION['user_role'])) {
      switch ($_SESSION['user_role']) {
          case 'Careseeker':
              redirect('careseeker/');
              break;
          case 'Admin':
              redirect('admin/');
              break;
          case 'Consultant':
              redirect('consultant/');
              break;
          case 'Caregiver':
              redirect('caregivers/');
              break;
          case 'Moderator':
              redirect('moderator/');
              break;
          default:
              redirect('home/');
              break;
      }
    }
    

   
    
  }

  public function logout(){
    unset($_SESSION ['user_id']);
    unset($_SESSION ['user_profile_picture']);
    unset($_SESSION ['user_email']);
    unset($_SESSION ['user_name']);
    unset($_SESSION ['user_role']);
    session_destroy();

    redirect('users/login');

  }

  public function isLoggedIn(){
    if(isset($_SESSION['user_id'])){
      return true;
    }else{
      return false;
    }
  }


  // view careseeker profile
  public function viewCareseekerProfile(){
    if($this->isLoggedIn()){
      $userId= $_SESSION['user_id'];
      $profileData=$this->usersModel->getCareseekerProfile($userId);
      $data =[
        'profileData'=>$profileData
      ];
      if($profileData){
        $this->view('careseeker/v_profile',$data);
      }else{
        echo "profile not found.";
      }
    }else{
      redirect('users/login');
    }
  }

  public function viewProfile(){
    if($this->isLoggedIn() && $_SESSION['user_role'] == 'Admin' || $_SESSION['user_role'] == 'Moderator'){
      $userId= $_SESSION['user_id'];
      $profileData=$this->usersModel->getCareseekerProfile($userId);
      $data =[
        'profileData'=>$profileData
      ];
      if($profileData){
        $this->view('careseeker/v_profile',$data);
      }else{
        echo "profile not found.";
      }
    }else{
      redirect('users/login');
    }
  }



  // edit profile
  public function editProfile() {
    if ($this->isLoggedIn()) {
        $userId = $_SESSION['user_id'];

        // Get current profile data for the user to prefill the form if it's a GET request
        $profileData = $this->usersModel->getCareseekerProfile($userId);
        $currentProfilePicture = $profileData[0]->profile_picture; // Current profile picture name from database

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'user_id' => $userId,
                'profile_picture' => $currentProfilePicture, // Set this for view display purposes
                'profile_picture_upload' => $_FILES['profile_picture'],
                'profile_picture_name' => !empty($_FILES['profile_picture']['name']) ? time().'_'.$_FILES['profile_picture']['name'] : $currentProfilePicture,
                'username' => trim($_POST['username']),
                'email' => trim($_POST['email']),
                'date_of_birth' => trim($_POST['date_of_birth']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'address' => trim($_POST['address']),
                'contact_info' => trim($_POST['contact_info']),
                'gender' => trim($_POST['gender']),
                
                // Error fields
                'username_err' => '',
                'profile_picture_err' => '',
                'email_err' => '',
                'dob_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
                'address_err' => '',
                'contact_info_err' => '',
                'gender_err' => ''
            ];

            // Validation
            // Validate username
            if (empty($data['username'])) {
                $data['username_err'] = 'Please enter a username';
            }

            // Validate email
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter an email';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Invalid email format';
            }

            // Validate date of birth
            if (empty($data['date_of_birth'])) {
                $data['dob_err'] = 'Please enter your date of birth';
            } elseif (!$this->usersModel->validateDate($data['date_of_birth'])) {
                $data['dob_err'] = 'Invalid date format. Please use YYYY-MM-DD';
            }

            // Validate password if changing
            if (!empty($data['password'])) {
                if (strlen($data['password']) < 6) {
                    $data['password_err'] = 'Password must be at least 6 characters';
                } elseif (!preg_match('/[A-Z]/', $data['password'])) {
                    $data['password_err'] = 'Password must contain at least one uppercase letter';
                } elseif (!preg_match('/[a-z]/', $data['password'])) {
                    $data['password_err'] = 'Password must contain at least one lowercase letter';
                } elseif (!preg_match('/[0-9]/', $data['password'])) {
                    $data['password_err'] = 'Password must contain at least one number';
                }
                // Confirm password match
                if ($data['password'] !== $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Passwords do not match';
                }
            }

            // Validate gender
            if (empty($data['gender'])) {
                $data['gender_err'] = 'Please specify your gender';
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

            // Check if there are no errors
            if (empty($data['username_err']) && empty($data['email_err']) && empty($data['dob_err']) && 
                empty($data['password_err']) && empty($data['confirm_password_err']) && 
                empty($data['gender_err']) && empty($data['address_err']) && empty($data['contact_info_err'])) {
                
                // Only process image upload if validation passes
                if (!empty($data['profile_picture_upload']['name'])) {
                    if (!empty($currentProfilePicture)) {
                        // Update existing profile picture by deleting the old one
                        $oldImagePath = PUBROOT . '/images/profile_imgs/' . $currentProfilePicture;
                        if (!updateImage($oldImagePath, $data['profile_picture_upload']['tmp_name'], $data['profile_picture_name'], '/images/profile_imgs/')) {
                            $data['profile_picture_err'] = 'Failed to update profile picture.';
                            // Keep the current image for display
                            $data['profile_picture'] = $currentProfilePicture;
                            $this->view('careseeker/v_edit', $data);
                            return;
                        }
                    } else {
                        // Upload a new profile picture
                        if (!uploadImage($data['profile_picture_upload']['tmp_name'], $data['profile_picture_name'], '/images/profile_imgs/')) {
                            $data['profile_picture_err'] = 'Profile picture uploading unsuccessful';
                            // Keep the current image for display
                            $data['profile_picture'] = $currentProfilePicture;
                            $this->view('careseeker/v_edit', $data);
                            return;
                        }
                    }
                    // Update the display profile picture with the new name
                    $data['profile_picture'] = $data['profile_picture_name'];
                } else {
                    // No new profile picture uploaded, retain the old one
                    $data['profile_picture_name'] = $currentProfilePicture;
                }

                // Hash new password if entered
                if (!empty($data['password'])) {
                    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                } else {
                    unset($data['password']); // Remove password field if not changing it
                }

                // Update the user profile
                if ($this->usersModel->updateCareseekerProfile($data)) {
                    $_SESSION['user_profile_picture'] = $data['profile_picture_name'];
                    redirect('users/viewCareseekerProfile');
                } else {
                    die('Something went wrong. Please try again.');
                }
            } else {
                // If validation errors occur, make sure we're showing the current profile picture
                $data['profile_picture'] = $currentProfilePicture;
                
                // Load view with errors
                $this->view('careseeker/v_edit', $data);
            }
        } else {
            // Initial load, populate form with existing data
            $data = [
                'user_id' => $userId,
                'username' => $profileData[0]->username,
                'email' => $profileData[0]->email,
                'profile_picture' => $profileData[0]->profile_picture, // This is what displays in the view
                'profile_picture_name' => $profileData[0]->profile_picture, // This is for database update
                'date_of_birth' => $profileData[0]->date_of_birth,
                'address' => $profileData[0]->address,
                'contact_info' => $profileData[0]->contact_info,
                'gender' => $profileData[0]->gender,
                'username_err' => '',
                'email_err' => '',
                'profile_picture_err' => '',
                'dob_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
                'address_err' => '',
                'contact_info_err' => '',
                'gender_err' => ''
            ];

            $this->view('careseeker/v_edit', $data);
        }
    } else {
        redirect('users/login');
    }
}

public function deleteUser() {
  if ($this->isLoggedIn()) {

    $userId = $_SESSION['user_id'];

      if($this->usersModel->deleteUser($userId)){
        unset($_SESSION ['user_id']);
        unset($_SESSION ['user_profile_picture']);
        unset($_SESSION ['user_email']);
        unset($_SESSION ['user_name']);
        session_destroy();

        redirect('/');

      }else{
        
        die('Something went wrong when deleting user');
      }

  }else{
    redirect('users/v_login');
  }

}



// careseeker profiles for user


// create profile

public function blog(){
  $data=[];
  $this->view('users/v_blog', $data);
}

public function viewblog(){
  $data=[];
  $this->view('users/v_view_blog', $data);
}

public function viewCaregivers() {
  // Get filter and sort parameters
  $region = $_GET['region'] ?? '';
  $type = $_GET['type'] ?? '';
  $speciality = $_GET['speciality'] ?? '';
  $sortBy = $_GET['sort'] ?? '';
  $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
  
  // Items per page
  $perPage = 8;
  
  // Get caregivers with filters
  $caregivers = $this->caregiversModel->getCaregivers($region, $type, $speciality, $sortBy, $page, $perPage);
  
  // Get total filtered caregivers count for pagination
  $totalCount = $this->caregiversModel->getCaregiversCount($region, $type, $speciality);
  $totalPages = ceil($totalCount / $perPage);
  
  // Get all unique regions for the filter dropdown
  $regions = $this->caregiversModel->getAllRegions();
  $specialities = $this->usersModel->getAllSpecialities();
  
  // Load view with data
  $data = [
      'caregivers' => $caregivers,
      'regions' => $regions,
      'specialities' => $specialities,
      'currentPage' => $page,
      'totalPages' => $totalPages,
      'totalCount' => $totalCount,
      'filters' => [
            'region' => $region,
            'type' => $type,
            'speciality' => $speciality,
            'sort' => $sortBy
        ]
  ];
  
  $this->view('users/v_viewCaregivers', $data);
}

public function viewConsultants() {
    // Get filter and sort parameters
    $region = $_GET['region'] ?? '';
    $type = $_GET['type'] ?? '';
    $speciality = $_GET['speciality'] ?? '';
    $sortBy = $_GET['sort'] ?? '';
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

    // Items per page
    $perPage = 8;

    // Get consultants with filters
    $consultants = $this->usersModel->getConsultants($region, $type, $speciality, $sortBy, $page, $perPage);

    // Get total filtered consultants count for pagination
    $totalCount = $this->usersModel->getConsultantsCount($region, $type, $speciality);
    $totalPages = ceil($totalCount / $perPage);

    // Get all unique regions and specialities for the filter dropdowns
    $regions = $this->usersModel->getAllRegions();
    $specialities = $this->usersModel->getAllSpecialities();

    // Load view with data
    $data = [
        'consultants' => $consultants,
        'regions' => $regions,
        'specialities' => $specialities,
        'currentPage' => $page,
        'totalPages' => $totalPages,
        'totalCount' => $totalCount,
        'filters' => [
            'region' => $region,
            'type' => $type,
            'speciality' => $speciality,
            'sort' => $sortBy
        ]
    ];

    $this->view('users/v_viewConsultants', $data);
}



// View individual caregiver profile
public function viewCaregiverProfile($id = null) {
  if (!$id) {
      redirect('careseeker/viewCaregivers');
  }
  
  // Get caregiver details
  $caregiver = $this->caregiversModel->getCaregiverById($id);
  
  if (!$caregiver) {
      redirect('careseeker/viewCaregivers');
  }
  
  $data = [
      'caregiver' => $caregiver
  ];
  
  $this->view('careseeker/viewCaregivers', $data);
}



// Password Reset



public function sendResetCode() {
  // Check for POST
  if($_SERVER['REQUEST_METHOD'] == 'POST') {
      // Process form
      $email = trim($_POST['email']);
      
      // Check if email exists in the database
      if($this->usersModel->findUserByEmail($email)) {
          // Generate a random 5-digit code
          $resetCode = sprintf("%05d", rand(0, 99999));
          
          // Store the code in the database with an expiration time (1 hour from now)
          $expiryTime = date('Y-m-d H:i:s', time() + 3600); // 1 hour from now
          
          if($this->usersModel->storeResetCode($email, $resetCode, $expiryTime)) {
              // Send the code by email
              $emailBody = '<h1>Password Reset Code</h1>
                  <p>You requested a password reset. Use the following code to reset your password:</p>
                  <h2>' . $resetCode . '</h2>
                  <p>This code will expire in 1 hour.</p>
                  <p>If you did not request this reset, please ignore this email.</p>';
              
              $result = $this->sendEmail(
                  $email,
                  'Password Reset Code - We4u',
                  $emailBody
              );
              
              if($result) {
                  // Redirect to verify code page
                  flash('reset_message', 'Reset code sent to your email');
                  redirect('users/verifyResetCode/' . urlencode($email));
              } else {
                  // Email sending failed
                  flash('reset_error', 'Failed to send reset code, please try again', 'alert alert-danger');
                  redirect('users/login');
              }
          } else {
              flash('reset_error', 'Something went wrong, please try again', 'alert alert-danger');
              redirect('users/login');
          }
      } else {
          // Email doesn't exist
          flash('reset_error', 'No account found with that email', 'alert alert-danger');
          redirect('users/login');
      }
  } else {
      // Redirect to login page if accessed directly
      redirect('users/login');
  }
}

public function verifyResetCode($email = '') {
  if($_SERVER['REQUEST_METHOD'] == 'POST') {
      // Process verification form
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
      
      $data = [
          'email' => trim($_POST['email']),
          'code' => trim($_POST['code']),
          'code_err' => ''
      ];
      
      // Validate code
      if(empty($data['code'])) {
          $data['code_err'] = 'Please enter the verification code';
      } elseif(strlen($data['code']) != 5 || !is_numeric($data['code'])) {
          $data['code_err'] = 'Invalid code format';
      }
      
      // Check if code is valid
      if(empty($data['code_err'])) {
          $codeData = $this->usersModel->verifyResetCode($data['email'], $data['code']);
          
          if($codeData) {
              // Check if code is expired
              $currentTime = date('Y-m-d H:i:s');
              if($currentTime > $codeData->expiry_time) {
                  $data['code_err'] = 'This code has expired. Please request a new one.';
              } else {
                  // Code is valid, redirect to reset password page
                  // Store code verification in session to prevent direct access to reset page
                  $_SESSION['reset_verified'] = true;
                  $_SESSION['reset_email'] = $data['email'];
                  
                  redirect('users/resetPassword');
                  return;
              }
          } else {
              $data['code_err'] = 'Invalid verification code';
          }
      }
      
      $this->view('users/v_verifyCode', $data);
  } else {
      // Initial form load
      $data = [
          'email' => urldecode($email),
          'code' => '',
          'code_err' => ''
      ];
      
      $this->view('users/v_verifyCode', $data);
  }
}

public function resetPassword() {
  // Check if user is verified
  if(!isset($_SESSION['reset_verified']) || $_SESSION['reset_verified'] !== true) {
      redirect('users/login');
  }
  
  if($_SERVER['REQUEST_METHOD'] == 'POST') {
      // Process form
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
      
      $data = [
          'email' => $_SESSION['reset_email'],
          'password' => trim($_POST['password']),
          'confirm_password' => trim($_POST['confirm_password']),
          'password_err' => '',
          'confirm_password_err' => ''
      ];
      
      // Validate password
      if(empty($data['password'])) {
          $data['password_err'] = 'Please enter a password';
      } elseif(strlen($data['password']) < 6) {
          $data['password_err'] = 'Password must be at least 6 characters';
      }
      
      // Validate confirm password
      if(empty($data['confirm_password'])) {
          $data['confirm_password_err'] = 'Please confirm password';
      } elseif($data['password'] != $data['confirm_password']) {
          $data['confirm_password_err'] = 'Passwords do not match';
      }
      
      // If no errors
      if(empty($data['password_err']) && empty($data['confirm_password_err'])) {
          // Hash password
          $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
          
          // Update password
          if($this->usersModel->updatePassword($data['email'], $data['password'])) {
              // Remove verification from session
              unset($_SESSION['reset_verified']);
              unset($_SESSION['reset_email']);
              
              // Invalidate used reset codes
              $this->usersModel->invalidateResetCodes($data['email']);
              
              flash('password_success', 'Your password has been updated successfully');
              redirect('users/login');
          } else {
              flash('password_error', 'Something went wrong, please try again', 'alert alert-danger');
              $this->view('users/v_reset_password', $data);
          }
      } else {
          // Load view with errors
          $this->view('users/v_resetPassword', $data);
      }
  } else {
      // Initial form load
      $data = [
          'email' => $_SESSION['reset_email'],
          'password' => '',
          'confirm_password' => '',
          'password_err' => '',
          'confirm_password_err' => ''
      ];
      
      $this->view('users/v_resetPassword', $data);
  }
}

// Send email helper method
private function sendEmail($to, $subject, $body) {
  // This is a wrapper for your existing sendEmail function
  $result = sendEmail($to, $subject, $body);
  
  if ($result['success']) {
      return true;
  } else {
      error_log($result['message']);
      return false;
  }
}






}
?>