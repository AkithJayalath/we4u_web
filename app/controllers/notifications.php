<?php
class notifications extends controller{
    
    public function __construct(){
        
       
    }

    
    public function notifications(){
        $data = [];
        $this->view('v_notifications',$data);
     } 

   
}
?>




