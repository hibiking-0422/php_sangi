<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">

<!--外部ファイル読み込み-->
<link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="/assets/css/slick.css"/>
<link rel="stylesheet" type="text/css" href="/assets/css/slick-theme.css"/>
<link rel="stylesheet" type="text/css" href="/assets/css/book_mana/check.css"/>
<script type="text/javascript" src="/assets/js/jquery-3.4.1.min.js"></script>
<script type="text/javascript" src="/assets/js/slick.min.js"></script>
<!---->

<?php
session_start();
require("../../core/pdo_connect.php");
require("../../parts/login_auth.php");

if(!isset($_SESSION['book'])){
    header('Location: index.php');
    exit;
}
if(!empty($_POST)){


    $statement = $db->prepare('INSERT INTO books SET  
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
      admin_id=?, 
      created=NOW()
      ');

  $statement->execute(array(
    $_SESSION['book']['book_id'],
    $_SESSION['book']['book_name'],
    $_SESSION['book']['book_maker'],
    $_SESSION['book']['publisher'],
    $_SESSION['book']['publication'],
    $_SESSION['book']['genre'],
    $_SESSION['book']['page'],
    $_SESSION['book']['age'],
    $_SESSION['book']['description'],
    $_SESSION['book']['thumbnail'],
    $_SESSION['book']['book_pdf'],
    $_SESSION['id']
  ));


  unset($_SESSION['book']);

  header('Location: index.php');
  exit();
}
?>
</head>
<body>

<form action="" method="post">
      <input type="hidden" name="action" value="submit" />

        <div class="thumbnail">
          <img src = "../assets/thumbnail/<?php echo htmlspecialchars($_SESSION['book']['thumbnail'], 3); ?>"  width="400px" height="550px" alt="" />
        </div>

        <div class="book-element">
    <table>
        <tr>
            <th>書籍番号</th><td><?php echo htmlspecialchars($_SESSION['book']['book_id'],3); ?></td>

            <th>書籍名</th><td><?php echo htmlspecialchars($_SESSION['book']['book_name'],3); ?></td>
        </tr>
        <tr>
            <th>作者</th><td><?php echo htmlspecialchars($_SESSION['book']['book_maker'],3); ?></td>

            <th>出版社</th><td><?php echo htmlspecialchars($_SESSION['book']['publisher'],3); ?></td>
        </tr>
        <tr>
            <th>出版日</th><td><?php echo htmlspecialchars($_SESSION['book']['publication'],3); ?></td>

            <th>ジャンル</th><td><?php echo htmlspecialchars($_SESSION['book']['genre'],3); ?></td>
        </tr>
        <tr>
            <th class="border-none">ページ数</th><td class="border-none"><?php echo htmlspecialchars($_SESSION['book']['page'],3); ?></td>

            <th class="border-none">対象年齢</th><td class="border-none"><?php echo htmlspecialchars($_SESSION['book']['age'],3); ?>歳</td>
        </tr>

    </table>
</div>  
<div class="discription"><div class="discription-title">紹介</div>
    <?php echo nl2br(htmlspecialchars($_SESSION['book']['description'],3)); ?>
</div>
      <a href="new.php?action=rewrite"><div class="rewrite-button">修正</div></a><input type="submit"  class="submit-button" value="登録" />
</form>
</body>
</html>