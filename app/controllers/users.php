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
          // user is authenticated
          // can create user sesstions
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
        // If approved or no approval required, create session
         $this->createUserSession($loggedUser);
          
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

  public function createUserSession($user){
    
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
                'profile_picture' => $_FILES['profile_picture'],
                'profile_picture_name' => time().'_'.$_FILES['profile_picture']['name'],
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
                'profile_picture_err' =>'',
                'email_err' => '',
                'dob_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
                'address_err' => '',
                'contact_info_err' => '',
                'gender_err' => ''
            ];

            // Handle profile picture upload/update
            if (!empty($data['profile_picture']['name'])) { // Only process if a new image is uploaded
                $profileImagePath = '/images/profile_imgs/' . $data['profile_picture_name'];
                
                if (!empty($currentProfilePicture)) {
                    // Update existing profile picture by deleting the old one
                    $oldImagePath = PUBROOT . '/images/profile_imgs/' . $currentProfilePicture;
                    if (updateImage($oldImagePath, $data['profile_picture']['tmp_name'], $data['profile_picture_name'], '/images/profile_imgs/')) {
                        // Successfully updated
                    } else {
                        $data['profile_picture_err'] = 'Failed to update profile picture.';
                    }
                } else {
                    // Upload a new profile picture
                    if (uploadImage($data['profile_picture']['tmp_name'], $data['profile_picture_name'], '/images/profile_imgs')) {
                        // Successfully uploaded
                    } else {
                        $data['profile_picture_err'] = 'Profile picture uploading unsuccessful';
                    }
                }
            } else {
                // No new profile picture uploaded, retain the old one
                $data['profile_picture_name'] = $currentProfilePicture;
            }

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
            if (empty($data['username_err']) && empty($data['email_err']) && empty($data['dob_err']) && empty($data['password_err']) && empty($data['confirm_password_err']) && empty($data['gender_err']) && empty($data['address_err']) && empty($data['contact_info_err']) && empty($data['profile_picture_err'])) {
                
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
                // Load view with errors
                $this->view('careseeker/v_edit', $data);
            }
        } else {
            // Initial load, populate form with existing data
            $data = [
                'user_id' => $userId,
                'username' => $profileData[0]->username,
                'email' => $profileData[0]->email,
                'profile_picture' => $profileData[0]->profile_picture,
                'profile_picture_name' => '',
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
  
  // Load view with data
  $data = [
      'caregivers' => $caregivers,
      'regions' => $regions,
      'currentPage' => $page,
      'totalPages' => $totalPages,
      'totalCount' => $totalCount
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
  
  // Get caregivers with filters
  $consultants = $this->consultantsModel->getConsultants($region, $type, $speciality, $sortBy, $page, $perPage);
  
  // Get total filtered caregivers count for pagination
  $totalCount = $this->consultantsModel->getConsultantsCount($region, $type, $speciality);
  $totalPages = ceil($totalCount / $perPage);
  
  // Get all unique regions for the filter dropdown
  $regions = $this->consultantsModel->getAllRegions();
  
  // Load view with data
  $data = [
      'consultants' => $consultants,
      'regions' => $regions,
      'currentPage' => $page,
      'totalPages' => $totalPages,
      'totalCount' => $totalCount
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






}
?>