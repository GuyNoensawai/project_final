<?php
    $db_host = "localhost"; #localhost server
    $db_user = "root"; #database username
    $db_password = ""; #database password
    $db_name = "php_multiplelogin"; #database name

    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }
    
    try {
        $db = new PDO("mysql:host={$db_host}; dbname={$db_name}", $db_user, $db_password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        $e->getMessage();
    }
?>