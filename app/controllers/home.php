<?php

class home extends controller{
    
    // The index method will be the default action for the HomeController
    public function __construct(){
        
    }

    public function index(){
        $this->view('home/v_home1');
    }

    public function index1(){
        $this->view('home/v_home1');
    }
}

?> 
