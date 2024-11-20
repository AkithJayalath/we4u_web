<?php
class cgrequest extends controller{
    
    public function __construct(){
        
       
    }

    public function request(){
       $data = [];
       $this->view('caregiver/v_request',$data);
    }

    public function viewreqinfo(){
        
        $this->view('caregiver/v_reqinfo');
     }

    public function norequest(){
        
        $this->view('caregiver/v_norequest');
     }
 

   
}



?>