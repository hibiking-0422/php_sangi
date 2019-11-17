<?php

    $dsn = 'mysql:dbname=sangi_lib;host=localhost';
    $user = 'hibiking';
    $password = 'Toromaru@5513';
    
    try{
        $db = new PDO($dsn,$user,$password);
    }catch (PDOException $e){
        echo "接続失敗: " . $e->getMessage() . "\n";
        exit();
    }
?>