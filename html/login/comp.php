<?php
session_start();

  require("../../core/pdo_connect.php");
  print($_SESSION['test']);

  $statement = $dbh->prepare('INSERT INTO admin SET teacher_id=?, password=?, created=NOW()');
  $count = $statement->execute(array(
    10,
    10
  ));
  print('------成功!');
?>

<?php 
  require("../../core/pdo_connect.php");

  $statement = $dbh->prepare('INSERT INTO admin SET teacher_id=?, password=?, created=NOW()');
  $count = $statement->execute(array(
    $_POST['teacher_id'],
    $_POST['password']
  ));
  print('------成功!');

?>
<br>
<?php
  $admins = $dbh->query('SELECT * FROM admin');
  while ($admin = $admins->fetch() ) {
    print($admin['teacher_id'] . " ");
    print($admin['paswsword'] );
  }
?>