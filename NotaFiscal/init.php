<?php
    // defines
    $DB_HOSTNAME = "localhost";
    $DB_USER = "root";
    $DB_PASSWORD = "";
    $DB_DATABASE = "iteris";

    // open connection
    $DB_CONN = mysqli_connect($DB_HOSTNAME, $DB_USER, $DB_PASSWORD, $DB_DATABASE);

    if (!$DB_CONN){
        die("Falha de conexão: " . mysqli_connect_error());
    }
        else{
            
        }

?>