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