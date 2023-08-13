<?php   
    define('DB_HOST', 'localhost');
    define('DB_USER', 'anzar');
    define('DB_PASS', 'anzar123');
    define('DB_NAME', 'contacts_db');
    define('MAIL_TO', 'test@techsolvitservice.com');
    define('MAIL_FROM', 'abdullahanzar789@gmail.com');

    //Creating database connection
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    //Check if connection is successful
    if($conn->connect_error) {
        die("Connection failed $$conn->connect_error");
    };