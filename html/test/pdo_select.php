<?php 
    require 'pdo_connect.php';

    $sql = 'SELECT * FROM user WHERE id < :id';
    $prepare = $dbh->prepare($sql);
    $prepare->bindValue(':id', 3, PDO::PARAM_INT);
    $prepare->execute();
    $result = $prepare->fetchAll(PDO::FETCH_ASSOC);
    var_dump($result);

?>