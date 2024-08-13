<?php

class HomeController {
    
    // The index method will be the default action for the HomeController
    public function index() {
        // Load the homepage view
        require_once '../app/views/home/home.view.php';
    }
}

?>
