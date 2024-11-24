<?php
class cgpayments extends controller{
    
    public function __construct(){
        
       
    }

    public function paymentMethod(){
       $data = [];
       $this->view('caregiver/v_paymentMethod',$data);
    }

    public function paymentHistory(){
        $data = [];
        $this->view('caregiver/v_paymentHistory',$data);
     }

   
}



?>