<?php
session_start();
require("../../core/pdo_connect.php");
require("../../parts/login_auth.php");

$books = $db->prepare('SELECT * FROM books WHERE id = ?');
$books->execute(array($_SESSION['update']['id']));
$book = $books->fetch();

if(($_SESSION['update']['thumbnail']) != $book['thumbnail']){
    unlink('../assets/thumbnail/' . $book['thumbnail']);
}

if(($_SESSION['update']['book_pdf']) != $book['book_pdf']){
    unlink('../assets/book_pdf/' . $book['book_pdf']);
}

$statement = $db->prepare('UPDATE books SET  
    book_id=?, 
    book_name=?, 
    book_maker=?, 
    publisher=?,
    publication=?,
    genre=?,
    page=?, 
    age=?, 
    description=?, 
    thumbnail=?, 
    book_pdf=?, 
    updated=NOW()
    WHERE id = ? 
');

 $statement->execute(array(
    $_SESSION['update']['book_id'],
    $_SESSION['update']['book_name'],
    $_SESSION['update']['book_maker'],
    $_SESSION['update']['publisher'],
    $_SESSION['update']['publication'],
    $_SESSION['update']['genre'],
    $_SESSION['update']['page'],
    $_SESSION['update']['age'],
    $_SESSION['update']['description'],
    $_SESSION['update']['thumbnail'],
    $_SESSION['update']['book_pdf'],
    $_SESSION['update']['id']
  ));

  $edit_log = $db->prepare("INSERT INTO edit_log SET 
    t_id=?, 
    b_id=?, 
    created=NOW()
    ");

  $edit_log->execute(array(
    $_SESSION['id'],
    $book['id']
  ));

  unset($_SESSION['update']);
  
  $url = "show.php?id=" . $book['id'] ;
  header("Location:" . $url );
  exit();
?>