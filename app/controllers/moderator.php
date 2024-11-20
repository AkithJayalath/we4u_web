<?php 
  class moderator extends controller{
    private $moderatorModel;

    public function __construct(){
      $this->moderatorModel = $this->model('M_Moderator');
    }

    public function careseekerrequests(){
      $requests = $this->moderatorModel->get_requests();

      $data = [
        'requests' => $requests
      ];

      $this->view('moderator/v_careseekerrequests', $data);
    }

    public function acceptedcareseekers(){
      $data = [];
      
      $this->view('moderator/v_acceptedcareseekers', $data);
    }

    public function rejectedcareseekers(){
      $data = [];

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
      $data = [];

      $this->view('moderator/v_interviews', $data);
    }

    public function schedule($request_id) {
          // Get request details
          $request = $this->moderatorModel->get_requests_by_id($request_id);
          // Check if interview exists
          $interview = $this->moderatorModel->checkInterviewExists($request_id);
        
          $data = [
              'request' => $request,
              'interview' => $interview,
              'title' => $interview ? 'edit' : 'add'
          ];

          // Load view with combined data
          $this->view('moderator/v_interview', $data);
    }

      public function submitInterview() {
          if($_SERVER['REQUEST_METHOD'] == 'POST') {
              // Collect form data
              $data = [
                  'request_id' => $_POST['request_id'],
                  'request_date' => $_POST['request_date'],
                  'interview_time' => $_POST['interview_time'],
                  'service' => $_POST['service'],
                  'platform' => $_POST['platform'],
                  'meeting_link' => $_POST['meeting_link'],
                  'provider_id' => $_POST['provider_id'],
                  'provider_name' => $_POST['provider_name'],
                  'provider_email' => $_POST['provider_email']
              ];

              // Check if this is an edit operation
              $existingInterview = $this->moderatorModel->checkInterviewExists($data['request_id']);

              if($existingInterview) {
                  // Update existing interview
                  if($this->moderatorModel->updateInterview($data)) {
                      redirect('moderator/requests');
                  }
              } else {
                  // Schedule new interview
                  if($this->moderatorModel->scheduleInterview($data)) {
                      redirect('moderator/requests');
                  }
              }
          }
      }

      public function announcementdetails(){
        $data = [];

        $this->view('moderator/v_announcements', $data);
      }

       // reject the request -->
      public function rejectRequest() {
          if($_SERVER['REQUEST_METHOD'] == 'POST') {
              $data = [
                  'request_id' => $_POST['request_id'],
                  'comment' => $_POST['comment'],
                  'status' => 'Declined'
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

      public function viewrequests(){
          if($_SERVER['REQUEST_METHOD'] == 'POST') {
              $request_id = $_POST['request_id'];
              $result = $this->moderatorModel->get_requests_by_id($request_id);
              
              $data = [
                  'request' => $result
              ];
              
              $this->view('moderator/v_viewrequest', $data);
          }
      }

  }
?>


