<?php 

class careseeker extends controller{
  private $careseekersModel;
    public function __construct(){
        if(!$_SESSION['user_id']){
            redirect('users/login');
        }
        else{
            if($_SESSION['user_role']!= 'Careseeker'){
                redirect('pages/permissonerror');
            }
            $this->careseekersModel = $this->model('M_Careseekers'); 
        }
      
    }

    public function index(){
        $this->showElderProfiles();
    }


    public function createProfile(){
      $careseeker_id = $_SESSION['user_id'] ?? '';
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          // Sanitize POST data
          $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
  
          // Prepare input data and error messages
          $data = [
              'careseeker_id' =>  $careseeker_id,
              'first_name' => trim($_POST['first_name']),
              'middle_name' => trim($_POST['middle_name']),
              'last_name' => trim($_POST['last_name']),
              'relationship_to_careseeker' => trim($_POST['relationship_to_careseeker']),
              'age' => trim($_POST['age']),
              'gender' => trim($_POST['gender']),
              'weight' => trim($_POST['weight']),
              'height' => trim($_POST['height']),
              'blood_pressure' => trim($_POST['blood_pressure']),
              'emergency_contact' => trim($_POST['emergency_contact']),
              'chronic_disease' => trim($_POST['chronic_disease']),
              'current_health_issues' => trim($_POST['current_health_issues']),
              'allergies' => trim($_POST['allergies']),
              'health_barriers' =>trim($_POST['health_barriers']),
              'surgical_history' => trim($_POST['surgical_history']),
              'family_diseases' => trim($_POST['family_diseases']),
              'current_medications' => trim($_POST['current_medications']),
              'special_needs' => trim($_POST['special_needs']),
              'dietary_restrictions' => trim($_POST['dietary_restrictions']),
              'profile_picture' => $_FILES['profile_picture'],
              'profile_picture_name' => time().'_'.$_FILES['profile_picture']['name'], // For storing the profile picture path
  
              // Error fields
              'first_name_err' => '',
              'last_name_err' => '',
              'age_err' => '',
              'emergency_contact_err' => '',
              'relationship_to_careseeker_err' => '',
              'profile_picture_err' => '',
          ];
  
          // Validation
          if (empty($data['first_name'])) {
              $data['first_name_err'] = 'Please enter the first name.';
          }
  
          if (empty($data['last_name'])) {
              $data['last_name_err'] = 'Please enter the last name.';
          }
  
          if (empty($data['age'])) {
              $data['age_err'] = 'Please enter the age.';
          } elseif (!is_numeric($data['age']) || $data['age'] <= 0) {
              $data['age_err'] = 'Age must be a positive number.';
          }
  
         
  
          if (empty($data['emergency_contact'])) {
              $data['emergency_contact_err'] = 'Please enter an emergency contact.';
          } elseif (!preg_match('/^[0-9]{10}$/', $data['emergency_contact'])) {
              $data['emergency_contact_err'] = 'Emergency contact must be a 10-digit number.';
          }
  
          if (empty($data['relationship_to_careseeker'])) {
              $data['relationship_to_careseeker_err'] = 'Please specify the relationship to the careseeker.';
          }
  
          // Profile picture validation (only if a file is uploaded)
          if (!empty($data['profile_picture']['name'])) {
            $profileImagePath = '/images/profile_imgs/' . $data['profile_picture_name'];
        
            // Upload the new profile picture
            if (uploadImage($data['profile_picture']['tmp_name'], $data['profile_picture_name'], '/images/profile_imgs')) {
                $data['profile_pic'] = $profileImagePath; // Assign the correct path
            } else {
                $data['profile_picture_err'] = 'Profile picture uploading unsuccessful';
            }
        }else{
            $data['profile_picture_name'] = null;

        }
        
  
          // Check for errors
          if (empty($data['first_name_err']) && empty($data['last_name_err']) && empty($data['age_err']) 
               && empty($data['emergency_contact_err']) && empty($data['relationship_to_careseeker_err']) &&
              empty($data['profile_picture_err'])) {
                  
              // If no errors, proceed to create the profile
              if ($this->careseekersModel->createElderProfile($data)) {
                  redirect('careseeker/showElderProfiles'); // Redirect to the elder profiles page
              } else {
                  echo "Error creating profile!";
              }
          } else {
              // Load the view with error messages
              $this->view('careseeker/v_create', $data);
          }
      } else {
          // If the request is not POST, load an empty form
          $data = [
              'first_name' => '',
              'middle_name' => '',
              'last_name' => '',
              'relationship_to_careseeker' => '',
              'age' => '',
              'gender' => '',
              'weight' => '',
              'height' => '',
              'blood_pressure' => '',
              'emergency_contact' => '',
              'chronic_disease' => '',
              'current_health_issues' => '',
              'allergies' => '',
              'health_barriers'=>'',
              'surgical_history' => '',
              'family_diseases' => '',
              'current_medications' => '',
              'special_needs' => '',
              'dietary_restrictions' => '',
              'profile_picture' => '',
              'profile_picture_name'=>'',
  
              'first_name_err' => '',
              'last_name_err' => '',
              'age_err' => '',
              'emergency_contact_err' => '',
              'relationship_to_careseeker_err' => '',
              'profile_picture_err' => '',
          ];
          $this->view('careseeker/v_create', $data);
      }
  }
  
  public function showElderProfiles() {
    
    
    $careseeker_id = $_SESSION['user_id'] ?? ''; 
    
    $elders = $this->careseekersModel->getElderProfilesByCareseeker($careseeker_id);

    // Pass the data to the view
    $data = [
        'elders' => $elders
    ];

    $this->view('careseeker/v_createProfile', $data); 
}


public function deleteElderProfile($elderId) {
  // Check if the elder profile exists
  if ($this->careseekersModel->deleteElderProfile($elderId)) {
      echo 'Elder profile deleted successfully.';
  } else {
      echo'Failed to delete elder profile.';
  }

  // Redirect to the profile page
  redirect('careseeker/showElderProfiles');
}



public function isLoggedIn(){
  if(isset($_SESSION['user_id'])){
    return true;
  }else{
    return false;
  }
}


public function viewElderProfile() {
  if ($_SERVER['REQUEST_METHOD'] == 'POST' && $this->isLoggedIn()) {
      $elderId = $_POST['elder_id'];
      $elderData = $this->careseekersModel->getElderProfilesData($elderId);
      
      $data = ['elderData' => $elderData];

      if ($elderData) {
          $this->view('careseeker/v_viewElderProfile', $data);
      } else {
          echo "Profile not found.";
      }
  } else {
      redirect('careseeker/showElderProfiles');
  }
}

public function editElderProfile()
{
    // Ensure the user is logged in
    if (!$this->isLoggedIn()) {
        redirect('users/login');
    }

    // Get the careseeker_id from the session
    $careseeker_id = $_SESSION['user_id'] ?? '';

    // Check if elder_id is passed in the GET request
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['elder_id'])) {
        // Get elder_id from the GET request
        $elderID = $_GET['elder_id'];

        // Fetch the elder profile by elder_id and careseeker_id
        $elderProfile = $this->careseekersModel->getElderProfileById($elderID, $careseeker_id);
        
        // If no profile found, redirect to the elder profiles page
        if (!$elderProfile) {
            redirect('careseeker/showElderProfiles');
        }
       
        // Prefill the form with elder profile data
        $data = [
            'elder_id' => $elderID,
            'careseeker_id' => $careseeker_id,
            'first_name' => $elderProfile->first_name,
            'middle_name' => $elderProfile->middle_name,
            'last_name' => $elderProfile->last_name,
            'relationship_to_careseeker' => $elderProfile->relationship_to_careseeker,
            'age' => $elderProfile->age,
            'gender' => $elderProfile->gender,
            'weight' => $elderProfile->weight,
            'height' => $elderProfile->height,
            'blood_pressure' => $elderProfile->blood_pressure,
            'emergency_contact' => $elderProfile->emergency_contact,
            'chronic_disease' => $elderProfile->chronic_disease,
            'current_health_issues' => $elderProfile->current_health_issues,
            'allergies' => $elderProfile->allergies,
            'health_barriers' => $elderProfile->health_barriers,
            'surgical_history' => $elderProfile->surgical_history,
            'family_diseases' => $elderProfile->family_diseases,
            'current_medications' => $elderProfile->current_medications,
            'special_needs' => $elderProfile->special_needs,
            'dietary_restrictions' => $elderProfile->dietary_restrictions,
            'profile_picture' => $elderProfile->profile_picture,
            'profile_picture_name' => '', // Initial value for profile picture
            // Error fields
            'first_name_err' => '',
            'last_name_err' => '',
            'age_err' => '',
            'emergency_contact_err' => '',
            'relationship_to_careseeker_err' => '',
            'profile_picture_err' => '',
        ];
       

        // Load the view with pre-filled data
        $this->view('careseeker/v_editElderProfile', $data);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
       
        // Handle POST request to update elder profile
        $elderID = $_POST['elder_id']; // Get elder_id from the form submission
        $careseeker_id = $_SESSION['user_id'] ?? ''; // Get careseeker_id from the session
        
        $elderProfile = $this->careseekersModel->getElderProfileById($elderID, $careseeker_id);
        $currentProfilePicture=$elderProfile->profile_picture;
        // Sanitize POST data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        // Prepare input data and error messages
        $data = [
            'elder_id' => $elderID,
            'careseeker_id' => $careseeker_id,
            'first_name' => trim($_POST['first_name']),
            'middle_name' => trim($_POST['middle_name']),
            'last_name' => trim($_POST['last_name']),
            'relationship_to_careseeker' => trim($_POST['relationship_to_careseeker']),
            'age' => trim($_POST['age']),
            'gender' => trim($_POST['gender']),
            'weight' => trim($_POST['weight']),
            'height' => trim($_POST['height']),
            'blood_pressure' => trim($_POST['blood_pressure']),
            'emergency_contact' => trim($_POST['emergency_contact']),
            'chronic_disease' => trim($_POST['chronic_disease']),
            'current_health_issues' => trim($_POST['current_health_issues']),
            'allergies' => trim($_POST['allergies']),
            'health_barriers' => trim($_POST['health_barriers']),
            'surgical_history' => trim($_POST['surgical_history']),
            'family_diseases' => trim($_POST['family_diseases']),
            'current_medications' => trim($_POST['current_medications']),
            'special_needs' => trim($_POST['special_needs']),
            'dietary_restrictions' => trim($_POST['dietary_restrictions']),
            'profile_picture' => $_FILES['profile_picture'],
           'profile_picture_name' => time().'_'.$_FILES['profile_picture']['name'], // Default to current picture if not updated
            // Error fields
            'first_name_err' => '',
            'last_name_err' => '',
            'age_err' => '',
            'emergency_contact_err' => '',
            'relationship_to_careseeker_err' => '',
            'profile_picture_err' => '',
        ];



        // Validation
        if (empty($data['first_name'])) {
            $data['first_name_err'] = 'Please enter the first name.';
        }

        if (empty($data['last_name'])) {
            $data['last_name_err'] = 'Please enter the last name.';
        }

        if (empty($data['age'])) {
            $data['age_err'] = 'Please enter the age.';
        } elseif (!is_numeric($data['age']) || $data['age'] <= 0) {
            $data['age_err'] = 'Age must be a positive number.';
        }

        if (empty($data['emergency_contact'])) {
            $data['emergency_contact_err'] = 'Please enter an emergency contact.';
        } elseif (!preg_match('/^[0-9]{10}$/', $data['emergency_contact'])) {
            $data['emergency_contact_err'] = 'Emergency contact must be a 10-digit number.';
        }

        if (empty($data['relationship_to_careseeker'])) {
            $data['relationship_to_careseeker_err'] = 'Please specify the relationship to the careseeker.';
        }

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
        // Check for errors
        if (empty($data['first_name_err']) && empty($data['last_name_err']) && empty($data['age_err'])
            && empty($data['emergency_contact_err']) && empty($data['relationship_to_careseeker_err'])
            && empty($data['profile_picture_err'])
        ) {
            // No errors, update the profile
            if ($this->careseekersModel->updateElderProfile($data,$elderID)) {
                redirect('careseeker/showElderProfiles'); // Redirect to the elder profiles page
            } else {
                die('Something went wrong. Please try again.');
            }
        } else {
            // Load the view with error messages
            $this->view('careseeker/v_editElderProfile', $data);
        }
    }
}


  
    public function viewConsultantProfile(){
        $data=[];
        $this->view('careseeker/v_consultantProfile', $data);
      }

      public function viewCaregiverProfile(){
        $data=[];
        $this->view('careseeker/v_caregiverProfile', $data);
      }


      public function requestCaregiver(){
        $data=[];
        $this->view('careseeker/v_requestCaregiver', $data);
      }

      public function requestConsultant(){
        $data=[];
        $this->view('careseeker/v_requestConsultant', $data);
      } 

      public function viewRequestInfo(){
        $data=[];
        $this->view('careseeker/v_viewRequestInfo', $data);
      }

      public function viewRequests(){
        $data=[];
        $this->view('careseeker/v_viewRequests', $data);
      }

      public function viewPayments(){
        $data=[];
        $this->view('careseeker/v_viewPayments', $data);
      }

      public function viewConsultants(){
        $data=[];
        $this->view('careseeker/v_viewConsultants', $data);
      }

      public function viewConsultantSession(){
        $data=[];
        $this->view('careseeker/v_viewConsultantSession', $data);
      }



}


?>