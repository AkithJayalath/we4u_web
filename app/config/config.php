<?php
    // Database Configuration
    define('DB_HOST','localhost');
    define('DB_USER','root');
    define('DB_PASSWORD','');
    define('DB_NAME','we4u_db');
    // APPROOT
    define('APPROOT',dirname(dirname(__FILE__)));  // define declares static

    // URLROOT
    define('URLROOT','http://localhost/we4u');

    // WEBSITE NAME
    define('SITENAME','WE4U');

    // PUBROOT
    define('PUBROOT',dirname(dirname(dirname(__FILE__))).'\public');

    define('DEBUG_MODE', true);


    // Encryption Key
    define('ENCRYPTION_KEY', 'YTBhM2M5ZjczYTY0MzlkZDI0NGFiODU1ZWYwOGYzN2MyODY3ODNkMGVmMmQ5ZWYxODI5YmVhNzJhNzFkZjM3YQ==');

?>