<?php 
  class moderator extends controller{
    private $moderatorModel;

    public function __construct(){
      if(!$_SESSION['user_id']) {
        redirect('users/login');
      }else{
        if($_SESSION['user_role'] != 'Moderator'){
          redirect('pages/permissonerror');
        }
      }
      
      $this->moderatorModel = $this->model('M_Moderator');
    }


    public function index(){
      $this->careseekerrequests();
    }


    public function careseekerrequests(){
      $requests = $this->moderatorModel->get_requests();

      $data = [
        'requests' => $requests
      ];

      $this->view('moderator/v_careseekerrequests', $data);
    }

    public function pendingrequests() {
      $requests = $this->moderatorModel->get_pending_requests();
      $data = [
          'requests' => $requests
      ];
      $this->view('moderator/v_pendingrequests', $data);
  }
  
    
    public function acceptedcareseekers() {
        $requests = $this->moderatorModel->get_accepted_requests();
        $data = [
            'requests' => $requests
        ];
        $this->view('moderator/v_acceptedcareseekers', $data);
    }

    public function rejectedcareseekers() {
        $requests = $this->moderatorModel->get_rejected_requests();
        $data = [
            'requests' => $requests
        ];
        $this->view('moderator/v_rejectedcareseekers', $data);
    }
    // rejection form getting details about the request
    public function rejectform($request_id) {
      $request = $this->moderatorModel->get_requests_by_id($request_id);
      $data = [
          'request' => $request
      ];
      $this->view('moderator/v_request_rejectionform', $data);
    }

    public function interviewdetails(){
      $interviews = $this->moderatorModel->get_inteviews();
      $data = [
        'interviews' => $interviews
      ];

      $this->view('moderator/v_interviews', $data);
    }


    public function interview($request_id) {
          // Get request details
          $request = $this->moderatorModel->get_requests_by_id($request_id);
          // Check if interview exists
          $interview = $this->moderatorModel->checkInterviewExists($request_id);
        
          $data = [
              'request' => $request,
              'interview' => $interview,
              'title' => $interview ? 'edit' : 'add',
              'time-err-message' => '',
              'link-err-message' => ''
          ];

          $message = '';

          // Load view with combined data
          $this->view('moderator/v_interview', $data);
    }

      // public function submitInterview() {
      //     if($_SERVER['REQUEST_METHOD'] == 'POST') {
      //         // Collect form data
      //         $data = [
      //             'request_id' => $_POST['request_id'],
      //             'request_date' => $_POST['request_date'],
      //             'interview_time' => $_POST['interview_time'],
      //             'service' => $_POST['service'],
      //             'platform' => $_POST['platform'],
      //             'meeting_link' => $_POST['meeting_link'],
      //             'provider_id' => $_POST['provider_id'],
      //             'provider_name' => $_POST['provider_name'],
      //             'provider_email' => $_POST['provider_email']
      //         ];

      //         // Check if this is an edit operation
      //         $existingInterview = $this->moderatorModel->checkInterviewExists($data['request_id']);

      //         if($existingInterview) {
      //             // Update existing interview
      //             if($this->moderatorModel->updateInterview($data)) {
      //                 redirect('moderator/requests');
      //             }
      //         } else {
      //             // Schedule new interview
      //             if($this->moderatorModel->scheduleInterview($data)) {
      //                 redirect('moderator/requests');
      //             }
      //         }
      //     }
      // }
        

    // public function submitInterview() {
    //     if($_SERVER['REQUEST_METHOD'] == 'POST') {
    //         // data for Validation
    //         $validationData = [
    //             'request_date' => $_POST['request_date'],
    //             'interview_time' => $_POST['interview_time'],
    //             'meeting_link' => $_POST['meeting_link']
    //         ];

    //         $errors = [];

    //         // Validate date and time
    //         $currentDateTime = new DateTime('now');
    //         $selectedDateTime = new DateTime($validationData['request_date'] . ' ' . $validationData['interview_time']);

    //         if ($selectedDateTime <= $currentDateTime) {
    //             $errors['time-err-message'] = 'Interview cannot be scheduled in the past';
    //         }

    //         // Validate meeting link
    //         if (!filter_var($validationData['meeting_link'], FILTER_VALIDATE_URL)) {
    //             $errors['link-err-message'] = 'Please enter a valid meeting link';
    //         }

    //         if (!empty($errors)) {
    //             // If there are errors, we carete the viewData to view the data with errors
    //             $request = $this->moderatorModel->get_requests_by_id($_POST['request_id']);
    //             $viewData = [
    //                 'request' => $request,
    //                 'interview' => (object)[
    //                     'request_date' => $_POST['request_date'],
    //                     'interview_time' => $_POST['interview_time'],
    //                     'platform' => $_POST['platform'],
    //                     'meeting_link' => $_POST['meeting_link'],
    //                     'provider_id' => $_POST['provider_id'],
    //                     'provider_name' => $_POST['provider_name'],
    //                     'provider_email' => $_POST['provider_email'],
    //                     'status' => 'Pending'
    //                 ],
    //                 'time-err-message' => $errors['time-err-message'] ?? '',
    //                 'link-err-message' => $errors['link-err-message'] ?? ''
    //             ];
    //             $this->view('moderator/v_interview', $viewData);
    //             return;
    //         }

    //         // If no errors, prepare data
    //         $data = [
    //             'request_id' => $_POST['request_id'],
    //             'request_date' => $_POST['request_date'],
    //             'interview_time' => $_POST['interview_time'],
    //             'platform' => $_POST['platform'],
    //             'service' => $_POST['service'],
    //             'meeting_link' => $_POST['meeting_link'],
    //             'provider_id' => $_POST['provider_id'],
    //             'provider_name' => $_POST['provider_name'],
    //             'provider_email' => $_POST['provider_email']
    //         ];

    //         // Check if interview exists or not and update/create accordingly
    //         $interview = $this->moderatorModel->checkInterviewExists($data['request_id']);
    //         if($interview) {
    //             if($this->moderatorModel->updateInterview($data)) {
    //                 redirect('moderator/careseekerrequests');
    //             }
    //         } else {
    //             if($this->moderatorModel->scheduleInterview($data)) {
    //                 redirect('moderator/careseekerrequests');
    //             }
    //         }
    //     }
    // }

    public function submitInterview() {
      if($_SERVER['REQUEST_METHOD'] == 'POST') {
          // Initial validation for required fields
          if(empty($_POST['request_date']) || empty($_POST['interview_time'])) {
              // Get request details for re-displaying the form
              $request = $this->moderatorModel->get_requests_by_id($_POST['request_id']);
              $viewData = [
                  'request' => $request,
                  'interview' => (object)[
                      'request_date' => $_POST['request_date'] ?? '',
                      'interview_time' => $_POST['interview_time'] ?? '',
                      'platform' => $_POST['platform'] ?? '',
                      'meeting_link' => $_POST['meeting_link'] ?? '',
                      'provider_id' => $_POST['provider_id'] ?? '',
                      'provider_name' => $_POST['provider_name'] ?? '',
                      'provider_email' => $_POST['provider_email'] ?? '',
                      'status' => 'Pending'
                  ],
                  'time-err-message' => 'Please select both date and time for the interview',
                  'link-err-message' => ''
              ];
              $this->view('moderator/v_interview', $viewData);
              return;
          }
  
          // Validation data for date/time and link
          $validationData = [
              'request_date' => $_POST['request_date'],
              'interview_time' => $_POST['interview_time'],
              'meeting_link' => $_POST['meeting_link']
          ];
  
          $errors = [];
  
          // Validate date and time
          $currentDateTime = new DateTime('now');
          $selectedDateTime = new DateTime($validationData['request_date'] . ' ' . $validationData['interview_time']);
  
          if ($selectedDateTime <= $currentDateTime) {
              $errors['time-err-message'] = 'Interview cannot be scheduled in the past';
          }
  
          // Validate meeting link
          if (!filter_var($validationData['meeting_link'], FILTER_VALIDATE_URL)) {
              $errors['link-err-message'] = 'Please enter a valid meeting link';
          }
  
          if (!empty($errors)) {
              $request = $this->moderatorModel->get_requests_by_id($_POST['request_id']);
              $viewData = [
                  'request' => $request,
                  'interview' => (object)[
                      'request_date' => $_POST['request_date'],
                      'interview_time' => $_POST['interview_time'],
                      'platform' => $_POST['platform'],
                      'meeting_link' => $_POST['meeting_link'],
                      'provider_id' => $_POST['provider_id'],
                      'provider_name' => $_POST['provider_name'],
                      'provider_email' => $_POST['provider_email'],
                      'status' => 'Pending'
                  ],
                  'time-err-message' => $errors['time-err-message'] ?? '',
                  'link-err-message' => $errors['link-err-message'] ?? ''
              ];
              $this->view('moderator/v_interview', $viewData);
              return;
          }
  
          // If validation passes, prepare data for database
          $data = [
              'request_id' => $_POST['request_id'],
              'request_date' => $_POST['request_date'],
              'interview_time' => $_POST['interview_time'],
              'platform' => $_POST['platform'],
              'service' => $_POST['service'],
              'meeting_link' => $_POST['meeting_link'],
              'provider_id' => $_POST['provider_id'],
              'provider_name' => $_POST['provider_name'],
              'provider_email' => $_POST['provider_email']
          ];
  
          // Check if interview exists and update/create accordingly
          $interview = $this->moderatorModel->checkInterviewExists($data['request_id']);
          if($interview) {
              if($this->moderatorModel->updateInterview($data)) {
                  redirect('moderator/careseekerrequests');
              }
          } else {
              if($this->moderatorModel->scheduleInterview($data)) {
                  redirect('moderator/careseekerrequests');
              }
          }
      }
  }
  
    
    




       // reject the request -->
      public function rejectRequest() {
          if($_SERVER['REQUEST_METHOD'] == 'POST') {
              $data = [
                  'request_id' => $_POST['request_id'],
                  'user_id' => $_POST['user_id'],
                  'role' => $_POST['role'],
                  'comment' => $_POST['comment'],
                  'status' => 'Declined',
                  'interview_status' => 'Done',
                  'is_approved' => 'rejected'
              ];
              
              if($this->moderatorModel->updateRequestStatus($data)) {
                // Return success response that will trigger modal
                $_SESSION['reject_success'] = true;
                redirect('moderator/careseekerrequests');
              } else {
                $_SESSION['reject_error'] = true;
                redirect('moderator/rejectform/' . $data['request_id']);
              }
          }
      }

      public function approve($request_id) {
        $request = $this->moderatorModel->get_requests_by_id($request_id);
        $data = [
            'request_id' => $request_id,
            'status' => 'Approved',
            'is_approved' => 'approved',
            'comment' => 'Application approved by moderator',
            'role' => $request->role,
            'user_id' => $request->user_id
        ];
        
        if($this->moderatorModel->updateRequestStatus($data)) {
            redirect('moderator/careseekerrequests');
        }
    }
    

      public function deleteInterview($request_id) {
        if($this->moderatorModel->deleteInterview($request_id)) {
            $_SESSION['success_message'] = 'Interview deleted successfully';
            redirect('moderator/careseekerrequests');

        } else {
            $_SESSION['error_message'] = 'Failed to delete interview';
            redirect('moderator/interview/' . $request_id);
        }
      }

      // testing 
      public function update($caregiver_id){
        $this->moderatorModel->updateCaregiverRequestStatus($caregiver_id);
        $data = [
          'caregiver_id' => $caregiver_id
        ];
        $this->view('moderator/v_update_caregiver', $data);
      }

      public function viewrequests(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
          $request_id = $_POST['request_id'];
          $user_id = $_POST['user_id'];
          $request_type = $_POST['request_type'];

          $request = $this->moderatorModel->get_requests_by_id($request_id);
          $documents = $this->moderatorModel->get_documents_by_id($user_id, $request_id);

              // Check if request_type is 'caregiver' or 'consultant'
          if($request_type == 'Caregiver') {
            $caregiver_data = $this->moderatorModel->get_caregiver($user_id);

              $data = [
                    'request' => $request,
                    'caregiver' => $caregiver_data,                    'documents' => $documents
                  ];
              $this->view('moderator/v_caregiver_req', $data);
          }
          else if($request_type == 'Consultant') {
            $consultant_data = $this->moderatorModel->get_consultant($user_id);

            $data = [
              'request' => $request,
              'consultant' => $consultant_data,
              'documents' => $documents
            ];
            $this->view('moderator/v_consultant_req', $data);
          }
        }
      }

      
}
?>




