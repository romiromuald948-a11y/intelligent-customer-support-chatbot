<?php 
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $servername = "localhost"; 
    $username = "root"; 
    $password = ""; 
    $dbname = "chatapp_db"; 

    $conn = new mysqli ($servername, $username, $password, $dbname);

   if ($conn->connect_error) { 
        die("La connexion a échoué: " . $conn->connect_error); 
    }
?>