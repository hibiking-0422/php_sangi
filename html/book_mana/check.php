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

<form action="" method="post">
      <input type="hidden" name="action" value="submit" />
    <dl>
        <dt>書籍番号</dt>
        <dd>
        <?php echo htmlspecialchars($_SESSION['book']['book_id'],3); ?>
        </dd>

        <dt>書籍名</dt>
        <dd>
        <?php echo htmlspecialchars($_SESSION['book']['book_name'],3); ?>
        </dd>

        <dt>作者</dt>
        <dd>
        <?php echo htmlspecialchars($_SESSION['book']['book_maker'],3); ?>
        </dd>

        <dt>出版社</dt>
        <dd>
        <?php echo htmlspecialchars($_SESSION['book']['publisher'],3); ?>
        </dd>

        <dt>ジャンル</dt>
        <dd>
        <?php echo htmlspecialchars($_SESSION['book']['genre'],3); ?>
        </dd>

        <dt>出版日</dt>
        <dd>
        <?php echo htmlspecialchars($_SESSION['book']['publication'],3); ?>
        </dd>

        <dt>ページ数</dt>
        <dd>
        <?php echo htmlspecialchars($_SESSION['book']['page'],3); ?>
        </dd>

        <dt>対象年齢</dt>
        <dd>
        <?php echo htmlspecialchars($_SESSION['book']['age'],3); ?>
        </dd>

        <dt>説明文</dt>
        <dd>
        <?php echo htmlspecialchars($_SESSION['book']['description'],3); ?>
        </dd>

        <dt>サムネイル</dt>
        <dd>
        <img src = "../assets/thumbnail/<?php echo htmlspecialchars($_SESSION['book']['thumbnail'], 3); ?>"  width="auto" height=
        "200" alt="" />
        </dd>

        <dt>書籍pdf</dt>
        <?php echo htmlspecialchars($_SESSION['book']['book_pdf'],3); ?>
        </dd>
    </dl>
      <div><a href="new.php?action=rewrite">&laquo;&nbsp;やり直す</a> | <input type="submit" value="登録する" /></div>
</form>


