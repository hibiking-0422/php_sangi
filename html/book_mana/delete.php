<?php
session_start();
require("../../core/pdo_connect.php");
require("../../parts/login_auth.php");


if(isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])){
    $id = $_REQUEST['id'];

    $books = $db->prepare('SELECT * FROM books WHERE id = ?');
    $books->execute(array($id));
    $book = $books->fetch();
    
    unlink('../assets/thumbnail/' . $book['thumbnail']);
    unlink('../assets/book_pdf/' . $book['book_pdf']);


    $book_del = $db->prepare('DELETE FROM books WHERE id = ?');
    $book_del->execute(array($id));

    $comments = $db->prepare('DELETE FROM comments WHERE b_id = ?');
    $comments->execute(array($id));

    $edit_logs = $db->prepare('DELETE FROM edit_log WHERE b_id = ?');
    $edit_logs->execute(array($id));

    $delete_logs = $db->prepare('INSERT INTO delete_log SET 
    admin_id = ?, 
    book_id = ?, 
    book_name = ?, 
    created = NOW()'
    );

    $delete_logs->execute(array(
        $_SESSION['id'],
        $book['book_id'],
        $book['book_name']
    ));
}

header('Location: index.php');
exit();
?>