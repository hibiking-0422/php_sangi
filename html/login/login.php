<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">

<script
src="https://code.jquery.com/jquery-2.2.4.min.js"
integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
crossorigin="anonymous"></script>
<script type="text/javascript" src="script.js"></script>
<link rel="stylesheet" href="style.css">
<script>
$(function() {
var h = $(window).height(); //ブラウザウィンドウの高さを取得
$('#main-contents').css('display','none'); //初期状態ではメインコンテンツを非表示
$('#loader-bg ,#loader').height(h).css('display','block'); //ウィンドウの高さに合わせでローディング画面を表示
});
$(window).load(function () {
$('#loader-bg').delay(900).fadeOut(800); //$('#loader-bg').fadeOut(800);でも可
$('#loader').delay(600).fadeOut(300); //$('#loader').fadeOut(300);でも可
$('#main-contents').css('display', 'block'); // ページ読み込みが終わったらメインコンテンツを表示する
});
</script>


</head>
<?php
session_start();
require("../../core/pdo_connect.php");

if(!empty($_POST)){
    if($_POST['teacher_id'] != '' && $_POST['password'] != ''){
            $login = $db->prepare(' SELECT * FROM admin WHERE teacher_id = ? AND password = ? ');
            $login->execute(array(
                $_POST['teacher_id'],
                sha1($_POST['password'])
              ));
              $admin = $login->fetch();

            if($admin){
                $_SESSION['id'] = $admin['id'];
                $_SESSION['time'] = time();

                header('Location: ../admin/admin_home.php');
                exit();
            }else {
                $error['login'] = 'failed';
            }
    }else{
        $error['login'] = 'blank';
    }
}
?>


<body>
<div id="loader-bg">
<div id="loading">
<img src="loader.gif">
</div>
</div>
<div id="main-contents">
<form action="" method="post">
    <dl>
        <dt>職員番号</dt>
        <dd>
        <input type="text" name="teacher_id" maxlength="255" value="<?php echo htmlspecialchars($_POST['teacher_id'], 3); ?>" />
        <?php if($error['login'] == 'blank'): ?>
        <p>*メールアドレスとパスワードを入力してください</p>
        <?php endif; ?>
        <?php if($error['login'] == 'failed'): ?>
        <p>*ログインに失敗しました</P>
        <?php endif; ?>
        </dd> 
        <dt>パスワード</dt>
        <dd>
        <input type="password" name="password" maxlength="255" value="<?php echo htmlspecialchars($_POST['password'], 3); ?>" />
        </dd>
        <div><input type="submit" value="ログイン" /></div>
</form>
</div>
</body>