<?php

    // Load configurations
    require_once 'config/config.php';


    // Load Helper
    require_once 'helpers/URL_Helper.php';
    require_once 'helpers/Session_Helper.php';
    require_once 'helpers/imageUpload_Helper.php';
    require_once 'helpers/fileUpload_Helper.php';
    require_once 'helpers/cssLoader_Helper.php';
    require_once 'helpers/calculate_BMI.php';
    require_once 'helpers/flash_Helper.php';
    require_once 'helpers/EmailHelper.php';

    // This is the initial file that will be executed.
    // Other files to be executed will be redirected from here.
    // This is the entry point to the framework 



    // Load libraries
    require_once 'libraries/core.php';
    require_once 'libraries/controller.php';
    require_once 'libraries/database.php';

    // library files that are needed for email configurations 
    require_once 'libraries/PHPMailer/src/Exception.php';
    require_once 'libraries/PHPMailer/src/PHPMailer.php';
    require_once 'libraries/PHPMailer/src/SMTP.php';


?>