<?php

    $dsn = 'mysql:dbname=sangi_lib;host=localhost';
    $user = 'hibiking';
    $password = 'Toromaru@5513';
    
    try{
        $dbh = new PDO($dsn,$user,$password);
        echo "接続成功\n";
    }catch (PDOException $e){
        echo "接続失敗: " . $e->getMessage() . "\n";
        exit();
    }
?>