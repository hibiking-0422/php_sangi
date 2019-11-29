<?php
session_start();
require("../../core/pdo_connect.php");

if(isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])){
    $id = $_REQUEST['id'];

    $books = $db->prepare('SELECT * FROM books WHERE id = ?');
    $books->execute(array($id));
    $book  = $books->fetch();

    $views = $book['views'] + 1;
    $statement = $db->prepare('UPDATE books SET  views = ? WHERE id = ?');
    $statement->execute(array(
        $views,
        $id
    ));



}


$file_name = '../assets/book_pdf/' . $book['book_pdf']; // ファイル名


//PDFを出力するためのヘッダー
header("Content-Type: application/pdf");

//ファイルを読み込んで出力
readfile($file_name);

?>