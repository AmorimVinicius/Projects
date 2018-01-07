<?php
    $conn = 'mysql:host=localhost;dbname=iteris';

    try{
        $db = new PDO($conn, 'root', '');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }catch (PDOException $e){
        if($e->getCode() == 1049){
            echo "ATENÇÃO! Verifique o banco de dados.";
            echo "O mesmo parece não existir.";
        }else{
            echo $e->getMessage();
        }
    }

?>