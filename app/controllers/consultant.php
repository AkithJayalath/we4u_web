  <?php
  class consultant extends Controller {
    
      private $consultantModel;
      private $caregiversModel;

      public function __construct() {
          $this->consultantModel = $this->model('M_Consultant');
          $this->caregiversModel = $this->model('M_Caregivers');
      }

      // Add the required index method
      public function index() {
          // This is your default method
          // You can redirect to c_reg or show a different view
          $this->viewpatients();
          
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

  public function viewrequests(){
    $this->view('consultant/v_viewRequests');
  }

  public function viewrequestinfo(){
    $this->view('consultant/v_viewRequestInfo');
  }

  public function viewpayments(){
    $this->view('consultant/v_viewPayments');
  }

  public function rateandreview(){
    $this->view('consultant/v_rate&review');
  }
  public function patientlist(){
    $this->view('consultant/v_patientList');
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