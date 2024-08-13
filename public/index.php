<?php

require_once '../app/controllers/home.controller.php';

// Create an instance of the HomeController
$controller = new HomeController();

// Call the index method to load the homepage
$controller->index();
?>
