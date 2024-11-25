<?php 

class careseeker extends controller{
  private $careseekersModel;
    public function __construct(){
      $this->careseekersModel = $this->model('M_Careseekers');
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
        }
        
  
          // Check for errors
          if (empty($data['first_name_err']) && empty($data['last_name_err']) && empty($data['age_err']) 
               && empty($data['emergency_contact_err']) && empty($data['relationship_to_careseeker_err']) &&
              empty($data['profile_picture_err'])) {
                  
              // If no errors, proceed to create the profile
              if ($this->careseekersModel->createElderProfile($data)) {
                  redirect('pages/index'); // Redirect to the elder profiles page
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
              'surgical_history' => '',
              'family_diseases' => '',
              'current_medications' => '',
              'special_needs' => '',
              'dietary_restrictions' => '',
              'profile_pic' => '',
  
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



  
  public function viewElderProfile(){
    $data=[];
    $this->view('careseeker/v_viewElderProfile', $data);
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