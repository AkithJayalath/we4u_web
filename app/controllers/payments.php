<?php
require_once __DIR__ . '/../../vendor/autoload.php'; 




class payments extends controller{
    
    private $paymentsModel;
    
    public function __construct(){ 
        $this->paymentsModel = $this->model('M_payment');
        $this->careseekersModel = $this->model('M_careseekers');
        $this->caregiversModel = $this->model('M_caregivers');
       
    }
    public function index(){ 
        
    }

    public function checkout(){
        $type = $_GET['type'] ?? null;
        $requestId = $_GET['request_id'] ?? null;

        
        
        if (!$requestId || !$type) {
            die("Invalid request.");
        }

        if ($type === 'caregiving') {
            $requestData = $this->careseekersModel->getFullCareRequestInfo($requestId);

            $data = [
                'request_id' => $requestId,
                'type' => $type,
                'payer' => $requestData->elder_name,
                'provider' => $requestData->caregiver_name ?? $requestData->consultant_name,
                'provider_id' => $requestData->caregiver_id ?? $requestData->consultant_id,
                'service_type' => isset($requestData->duration_type) ? $requestData->duration_type : 'N/A', // Default to 'N/A' if not set
                'time_slots' => isset($requestData->time_slots) ? $requestData->time_slots : 'N/A', // Default to 'N/A' if not set
                'amount' => $requestData->payment_details,
                'description' => "Elderly Care - Request #$requestId"
            ];

        } elseif ($type === 'consulting') {
            $requestData = $this->careseekersModel->getFullConsultRequestInfo($requestId);
            $data = [
                'request_id' => $requestId,
                'type' => $type,
                'payer' => $requestData->elder_name,
                'provider' => $requestData->caregiver_name ?? $requestData->consultant_name,
                'provider_id' => $requestData->caregiver_id ?? $requestData->consultant_id,
                'service_type' => 'Consulting', // Default service type for consulting
                'time_slots' => isset($requestData->time_slot) ? $requestData->time_slot : 'N/A', // Ensure time_slot is set for consulting
                'amount' => $requestData->payment_details,
                'description' => "Elderly Care - Request #$requestId"
            ];

        } else {
            die("Unknown request type.");
        }

        if (!$requestData) {
            die("Request not found.");
        }

        // Store payment data in session
        $_SESSION['payment_type'] = $type;
        $_SESSION['payment_request_id'] = $requestId;
        $_SESSION['payment_amount'] = $requestData->payment_details;
        $_SESSION['payment_provider_id'] = $type === 'caregiving' ? $requestData->caregiver_id : $requestData->consultant_id;
        
        
    
        $this->view('payments/v_checkout',$data);
    }

    public function stripe(){
        
        
        $stripe_secret_key = "sk_test_51RDgWGRsiFHF5MGjQ3sCDUNDk6HsvtikzZfKafQkyOSQX9KkmNCGKAKTy0UBq5ZecP2BVkY33AeQPH7S3MQl4Ncb00sbaDkGev";
        \Stripe\Stripe::setApiKey($stripe_secret_key);



        $amount = $_POST['amount'];
        $description = $_POST['description'];
        $user_id = $_SESSION['user_id'];

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'lkr',
                    'product_data' => [
                        'name' => $description,
                    ],
                    'unit_amount' => $amount * 100, // Amount in paise
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'customer_email' => $_SESSION['user_email'],
            'success_url' => URLROOT . '/payments/success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => URLROOT . '/payments/cancel',
        ]);



        header("Location: " . $session->url);


        //$this->view('payments/v_proceed',$data);


    }

public function success()
{
    //get session id
    $sessionId = $_GET['session_id'] ?? null;

    if (!$sessionId) {
        die("Session ID not found!");
    }
    
   
    $stripe = new \Stripe\StripeClient('sk_test_51RDgWGRsiFHF5MGjQ3sCDUNDk6HsvtikzZfKafQkyOSQX9KkmNCGKAKTy0UBq5ZecP2BVkY33AeQPH7S3MQl4Ncb00sbaDkGev');
    //\Stripe\Stripe::setApiKey("sk_test_51RDgWGRsiFHF5MGjQ3sCDUNDk6HsvtikzZfKafQkyOSQX9KkmNCGKAKTy0UBq5ZecP2BVkY33AeQPH7S3MQl4Ncb00sbaDkGev");

    $checkoutSession = $stripe->checkout->sessions->retrieve($sessionId);

    
    // Get payment intent
    $paymentIntentId = $checkoutSession->payment_intent;

    // Retrieve payment intent with expanded charges
    $paymentIntent = $stripe->paymentIntents->retrieve($paymentIntentId);
   

    // Get charge ID
    if (!$paymentIntent || empty($paymentIntent->latest_charge)) {
        die("Payment intent or charge not found!");
    }
    
    $chargeId = $paymentIntent->latest_charge;
    
    
    
    
    


    

    $type = $_SESSION['payment_type'] ?? null;
    $requestId = $_SESSION['payment_request_id'] ?? null;
    $amount = $_SESSION['payment_amount'] ?? null;
    $payerId = $_SESSION['user_id'] ?? null;

    $paymentData = [
        'payer_id' => $payerId,
        'amount' => $amount,
        'payment_date' => date('Y-m-d H:i:s'),
        'status' => 'success',
        'stripe_charge_id' => $chargeId
    ];

    $recieptData = [];
    

    if ($type === 'caregiving') {
        $requestData = $this->careseekersModel->getFullCareRequestInfo($requestId);
        $providerId = $requestData->caregiver_id; // Get the caregiver ID

        

        $paymentData['care_request_id'] = $requestId;
        $paymentData['caregiver_id'] = $providerId;

        

        $recieptData = [
                'request_id' => $requestId,
                'type' => $type,
                'payer' => $requestData->elder_name,
                'provider' => $requestData->caregiver_name ?? $requestData->consultant_name,
                'service' => 'Caregiving',
                'service_type' => isset($requestData->duration_type) ? $requestData->duration_type : 'N/A', // Default to 'N/A' if not set
                'time_slots' => isset($requestData->time_slots) ? $requestData->time_slots : 'N/A', // Default to 'N/A' if not set
                'amount' => $requestData->payment_details,
                'description' => "Elderly Care - Request #$requestId"
        ];

        

        
        // Store payment in care_payments table
        $result = $this->paymentsModel->storeCaregiverPayment($paymentData);
        
        if ($result) {
            // Mark caregiving request as paid
            $this->careseekersModel->markCareRequestAsPaid($requestId);
        }
    } elseif ($type === 'consulting') {
        $requestData = $this->careseekersModel->getFullConsultRequestInfo($requestId);
        $providerId = $requestData->consultant_id; // Get the consultant ID

        $paymentData['consultant_request_id'] = $requestId;
        $paymentData['consultant_id'] = $providerId;

        $recieptData = [
                'request_id' => $requestId,
                'type' => $type,
                'payer' => $requestData->elder_name,
                'provider' => $requestData->caregiver_name ?? $requestData->consultant_name,
                'service' => 'Consulting', // Default service type for consulting
                'time_slots' => isset($requestData->time_slot) ? $requestData->time_slot : 'N/A', // Ensure time_slot is set for consulting
                'amount' => $requestData->payment_details,
                'description' => "Elderly Care - Request #$requestId"
    ];
    
        
        // Store payment in consultant_payments table
        $result = $this->paymentsModel->storeConsultantPayment($paymentData);
        
        if ($result) {
            // Mark consulting request as paid
            $this->careseekersModel->markConsultRequestAsPaid($requestId);
        }
    }
    
        // Clear session payment data
        unset($_SESSION['payment_type'], $_SESSION['payment_request_id'], $_SESSION['payment_amount']);
    
        

  

        

        $data = [
            'message' => '🎉 Your payment was successful!',
            'receipt' => $recieptData
        ];
    $this->view('payments/v_proceed', $data);
}



public function cancel()
{
    $data['message'] = "⚠️ Your payment was canceled. Please try again.";
    $this->view('payments/v_proceed', $data);
}

    
   

   
}
?>
