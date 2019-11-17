<?php
session_start();
require("../../core/pdo_connect.php");
require("../../parts/login_auth.php");

if (!isset($_SESSION['join'])){
  header('Location: new.php');
  exit();
}

if(!empty($_POST)){
  $statement = $db->prepare('INSERT INTO admin SET teacher_id=?, password=?, created=NOW()');
  $statement->execute(array(
    $_SESSION['join']['teacher_id'],
    sha1($_SESSION['join']['password'])
  ));
  unset($_SESSION['join']);

  header('Location: /join/index.php');
  exit();
}
?>

<form action="" method="post">
  <input type="hidden" name="action" value="submit" />
  <dl>
    <dt>職員番号</dt>
    <dd>
    <?php echo htmlspecialchars($_SESSION['join']['teacher_id'],3); ?>
    </dd>
    <dt>パスワード</dt>
    <dd>
    [表示されません]
    </dd>
  </dl>
    <div><a href="new.php?action=rewrite">&laquo;&nbsp;やり直す</a> | <input type="submit" value="登録する" /></div>
</form>