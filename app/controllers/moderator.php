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
                  // Send email to provider
                  $this->sendInterviewEmail($data['provider_email'], $data['service'], $data['request_date'], $data['interview_time'], $data['platform'], $data['meeting_link']);
                  redirect('moderator/careseekerrequests');
              }
          } else {
              if($this->moderatorModel->scheduleInterview($data)) {
                // Send email to provider
                $this->sendInterviewEmail($data['provider_email'], $data['service'], $data['request_date'], $data['interview_time'], $data['platform'], $data['meeting_link']);
                  redirect('moderator/careseekerrequests');
              }
          }
      }
  }

  public function sendInterviewEmail($email, $service, $date, $time, $platform, $meeting_link) {
    // Format date for better readability
    $formatted_date = date('l, F j, Y', strtotime($date));
    $formatted_time = date('g:i A', strtotime($time));
    
    $result = sendEmail(
        $email,
        'Your We4u Interview Appointment',
        '<h1>Interview Scheduled for ' . htmlspecialchars($service) . ' Position</h1>
        <p>Dear Applicant,</p>
        <p>We are pleased to inform you that your interview for the ' . htmlspecialchars($service) . ' position at We4u has been scheduled.</p>
        <div style="background-color: #f5f5f5; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <p><strong>Interview Details:</strong></p>
            <p><strong>Date:</strong> ' . htmlspecialchars($formatted_date) . '</p>
            <p><strong>Time:</strong> ' . htmlspecialchars($formatted_time) . '</p>
            <p><strong>Platform:</strong> ' . htmlspecialchars($platform) . '</p>
            <p><strong>Meeting Link:</strong> <a href="' . htmlspecialchars($meeting_link) . '">' . htmlspecialchars($meeting_link) . '</a></p>
        </div>
        <p>Please ensure you join the meeting a few minutes before the scheduled time. Have your identification documents ready for verification purposes.</p>
        <p>We look forward to meeting you!</p>
        <p>Best regards,<br>The We4u Team</p>'
    );
    
    if ($result['success']) {
        flash('success', 'Interview notification email sent successfully');
        return true;
    } else {
        flash('error', 'Failed to send interview notification email');
        return false;
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
                  'is_approved' => 'rejected',
                  'user_email' => $_POST['email']
              ];
              
              if($this->moderatorModel->updateRequestStatus($data)) {
                // Return success response that will trigger modal
                $_SESSION['reject_success'] = true;
                // Send rejection email to the user
                $email = $_POST['email'];
                $reason = $_POST['comment'];
                $this->sendRejectionEmail($email, $reason);
                redirect('moderator/careseekerrequests');
              } else {
                $_SESSION['reject_error'] = true;
                redirect('moderator/rejectform/' . $data['request_id']);
              }
          }
      }

      public function sendRejectionEmail($email, $reason) {
        $result = sendEmail(
            $email,
            'Update on Your Consultant Application',
            '<h1>We4u Application Status</h1><p>We regret to inform you that your request to become a Consultant has been rejected.</p><p><strong>Reason for rejection:</strong> ' . htmlspecialchars($reason) . '</p><p>If you believe this decision was made in error or if you would like to reapply with additional information, please contact our support team.</p><p>Thank you for your interest in We4u.</p>'
        );       

        if ($result['success']) {
            flash('success', 'Email sent successfully');
            return true;
        } else {
            return false;
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
            'user_id' => $request->user_id,
            'user_email' => $request->email,
        ];
        
        if($this->moderatorModel->updateRequestStatus($data)) {
          // send Email to the user
          $this->sendAcceptionEmail($data['user_email']);
            redirect('moderator/careseekerrequests');
        }
    }

    public function sendAcceptionEmail($email) {
      $result = sendEmail(
        $email,
        'Your Consultant Application is Approved',
        '<p>Dear Applicant,</p>
        <p>Congratulations! Your application to become a Consultant at We4u has been approved.</p>
        <p>You can now log in to your account and start using our platform.</p>
        <p>Thank you for joining We4u.</p>
        <p>Regards,<br>The We4u Team</p>'
      );       

      if ($result['success']) {
          flash('success', 'Email sent successfully');
          return true;
      } else {
          return false;
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
                    'caregiver' => $caregiver_data,                    
                    'documents' => $documents
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


      public function caregiverAndConsultantRequests() {
        // Get caregiver requests from model
        $careRequests = $this->moderatorModel->getAllCareRequests();
        
        // Add service_type manually to each caregiving request
        foreach ($careRequests as &$req) {
            $req->service_category = 'Caregiving';
        }
        
        // Get consultant requests from model
        $consultRequests = $this->moderatorModel->getAllConsultRequests();
        foreach ($consultRequests as &$req) {
            $req->service_category = 'Consultation';
        }
    
        // Merge both types of requests
        $mergedRequests = array_merge($careRequests, $consultRequests);
        
        // Sort by created_at/request_date (newest first)
        usort($mergedRequests, function($a, $b) {
            $dateA = isset($a->created_at) ? $a->created_at : $a->request_date;
            $dateB = isset($b->created_at) ? $b->created_at : $b->request_date;
            return strtotime($dateB) - strtotime($dateA); 
        });
        
        $data = [
            'requests' => $mergedRequests
        ];
        
        $this->view('moderator/v_careAndConslutantRequests', $data);
    }

  //  Display the payments page for caregivers
  public function caregiverPayments() {
    // Get all caregiver payments
    $caregiverPayments = $this->moderatorModel->getCaregiverPayments();
    
    // Calculate We4U earnings (8% of payment amount) for each payment
    foreach ($caregiverPayments as &$payment) {
        // Calculate We4U earnings (8% of the total amount)
        $we4u_commission_rate = 0.08; // 8%
        $we4u_earn = $payment->amount * $we4u_commission_rate;
        
        // Round to 2 decimal places for currency
        $we4u_earn = round($we4u_earn, 2);
        
        // Calculate the actual payment amount to caregiver (92% of total)
        $caregiver_payment = $payment->amount - $we4u_earn;
        
        // Update the payment object with these calculated values
        $payment->we4u_earn = $we4u_earn;
        $payment->caregiver_payment = $caregiver_payment;
    }
    
    $data = [
        'caregiverPayments' => $caregiverPayments
    ];
    
    $this->view('moderator/v_payments', $data);
  }

  public function markCaregiversAsPaid() {
    // Check if form is submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get form data
        $care_request_id = $_POST['care_request_id'];
        $caregiver_id = $_POST['caregiver_id'];
        
        // Validate the data
        if (empty($care_request_id) || empty($caregiver_id)) {
            flash('payment_error', 'Invalid payment information', 'alert alert-danger');
            redirect('moderator/payments');
            return;
        }
        
        // Call the model function to mark payment as paid
        if ($this->moderatorModel->markPaymentAsPaid($care_request_id, $caregiver_id)) {
            flash('payment_success', 'Payment marked as paid successfully', 'alert alert-success');
        } else {
            flash('payment_error', 'Failed to mark payment as paid', 'alert alert-danger');
        }
        
        // Redirect back to payments page
        redirect('moderator/caregiverPayments');
    } else {
        // If not POST request, redirect to payments page
        redirect('moderator/caregiverPayments');
    }
  }

public function consultantPayments() {
  // Get all consultant payments
  $consultantPayments = $this->moderatorModel->getConsultantPayments();
  
  // Calculate We4U earnings (8% of payment amount) for each payment
  foreach ($consultantPayments as &$payment) {
      // Calculate We4U earnings (8% of the total amount)
      $we4u_commission_rate = 0.08; // 8%
      $we4u_earn = $payment->amount * $we4u_commission_rate;
      
      // Round to 2 decimal places for currency
      $we4u_earn = round($we4u_earn, 2);
      
      // Calculate the actual payment amount to consultant (92% of total)
      $consultant_payment = $payment->amount - $we4u_earn;
      
      // Update the payment object with these calculated values
      $payment->we4u_earn = $we4u_earn;
      $payment->consultant_payment = $consultant_payment;
  }
  
  $data = [
      'consultantPayments' => $consultantPayments
  ];
  
  $this->view('moderator/v_consultant_payments', $data);
}

public function markConsultantAsPaid() {
  // Check if form is submitted
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // Get form data
      $payment_id = $_POST['payment_id'];
      $consultant_id = $_POST['consultant_id'];
      
      // Validate the data
      if (empty($payment_id) || empty($consultant_id)) {
          flash('consultant_payment_error', 'Invalid payment information', 'alert alert-danger');
          redirect('moderator/consultantPayments');
          return;
      }
      
      // Call the model function to mark payment as paid
      if ($this->moderatorModel->markConsultantPaymentAsPaid($payment_id, $consultant_id)) {
          flash('consultant_payment_success', 'Payment marked as paid successfully', 'alert alert-success');
      } else {
          flash('consultant_payment_error', 'Failed to mark payment as paid', 'alert alert-danger');
      }
      
      // Redirect back to payments page
      redirect('moderator/consultantPayments');
  } else {
      // If not POST request, redirect to payments page
      redirect('moderator/consultantPayments');
  }
}




    

      
}
?>




