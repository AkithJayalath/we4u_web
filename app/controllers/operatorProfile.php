<?php 
class operatorProfile extends controller{
  private $operatorrModel;

  public function __construct(){
    $this->operatorrModel = $this->model('M_operatorProfile');
  }

  public function index(){
    if($_SESSION['user_id'] ){
      if($_SESSION['user_role'] == 'Admin' || $_SESSION['user_role'] == 'Moderator'){
        $userId= $_SESSION['user_id'];
        $profileData=$this->operatorrModel->getOperatorProfile($userId);
        $data =[
          'profileData'=>$profileData
        ];
        if($profileData){
          $this->view('users/v_view_operator_profile',$data);
        }else{
          echo "profile not found.";
        }

      }else{
        redirect('pages/error');
      }

    }else{
      redirect('users/login');
    }
  }

  public function editProfile() {
    if ($_SESSION['user_id']) {
      if($_SESSION['user_role'] == 'Admin' || $_SESSION['user_role'] == 'Moderator'){
        $userId = $_SESSION['user_id'];

        // Get current profile data for the user to prefill the form if it's a GET request
        $profileData = $this->operatorrModel->getOperatorProfile($userId);
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
            } elseif (!$this->operatorrModel->validateDate($data['date_of_birth'])) {
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
                if ($this->operatorrModel->updateCareseekerProfile($data)) {
                    $_SESSION['user_profile_picture'] = $data['profile_picture_name'];
                    redirect('operatorProfile/index');
                } else {
                    die('Something went wrong. Please try again.');
                }
            } else {
                // Load view with errors
                $this->view('users/v_edit_operator_profile', $data);
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

            $this->view('users/v_edit_operator_profile', $data);
        }
      }else{
        redirect('pages/error');
      }
    } else {
        redirect('users/login');
    }
}

}


?>