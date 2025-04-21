<?php

class about extends controller{
    
    public function aboutus(){
        $this->view('v_about');
    }

    public function index(){
        $this->aboutus();
    }

    
}

?>
