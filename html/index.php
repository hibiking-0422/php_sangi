<a href="./login/login.php">ログイン</a><br>
<a href="./test/session_test.php">セッションテスト</a><br>
<?php session_start();?>
<?php $_SESSION['test'] = 'あいうえお';
    echo('エコー');
?>