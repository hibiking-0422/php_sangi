<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<link href="/assets/css/header.css" rel="stylesheet" type="text/css">
<link href="/assets/css/login/login.css" rel="stylesheet" type="text/css">
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

                header('Location: /book_mana/index.php');
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
<header>
        <h1 class="headline">
            <a>Sangi Library</a>
        </h1>
        <ul class="nav-list">
            <span class="nav-right">
              <li class="nav-list-item">
              <a href="./children/index.php">ほんをよむ</a>
              </li>
              </span>
          </ul>
</header>
<div class="main-contents">
<form action="" method="post">

        <div class="title">職員番号</div>
        <div>
        <input type="text" name="teacher_id" maxlength="255" class="input-desgin" value="<?php echo htmlspecialchars($_POST['teacher_id'], 3); ?>" />
        <?php if($error['login'] == 'blank'): ?>
        <p>*職員番号とパスワードを入力してください</p>
        <?php endif; ?>
        <?php if($error['login'] == 'failed'): ?>
        <p>*ログインに失敗しました</P>
        <?php endif; ?>
        </div> 
        <div class="title">パスワード</div>
        <div>
        <input type="password" name="password" maxlength="255" class="input-desgin" value="<?php echo htmlspecialchars($_POST['password'], 3); ?>" />
        </div>
        <div><input type="submit" class="submit-button" value="ログイン" /></div>
</form>
</div>
</body>
</html>