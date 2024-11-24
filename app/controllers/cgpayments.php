<?php
class cgpayments extends controller{
    
    public function __construct(){
        
       
    }

    public function paymentMethod(){
       $data = [];
       $this->view('caregiver/v_paymentMethod',$data);
    }

   
}



?>