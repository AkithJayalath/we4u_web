<?php
require_once __DIR__ . '/../../vendor/autoload.php'; 
;

class payments extends controller{
    

    
    public function __construct(){ 
        $this->paymentsModel = $this->model('M_payment');
       
    }
    public function index(){
        
    }

    public function checkout(){
        

        $data = [];
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
            'success_url' => URLROOT . '/payments/success',
            'cancel_url' => URLROOT . '/payments/cancel',
        ]);

        header("Location: " . $session->url);


        //$this->view('payments/v_proceed',$data);


    }

public function success()
{
    $data = [
        
    ];

    $data['message'] = "🎉 Your payment was successful. Thank you!";
    $this->view('payments/v_proceed', $data);
}

public function cancel()
{
    $data['message'] = "⚠️ Your payment was canceled. Please try again.";
    $this->view('payments/v_proceed', $data);
}

    
   

   
}
?>
