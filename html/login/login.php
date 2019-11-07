<?php
session_start();

if(!empty($_POST)){

    //空白チェック
    if($_POST['teacher_id'] == ''){
        $error['teacher_id'] = 'blank';
    }
    if(strlen($_POST['password']) < 6){
        $error['password'] = 'length';
    }
    if($_POST['password'] == ''){
        $error['password'] = 'blank';
    }

    //入力に空白が無ければ実行
    if(empty($error)){
        $_SESSION['login'] = $_POST;
        header('Location: login_check.php');
        exit(); 
    }
}
?>

<form action="" method="post" >
    <dl>
        <dt>職員番号</dt>
        <dd><input type="text" name="teacher_id" maxlength="255" value="<?php echo htmlspecialchars($_POST['teacher_id'], 3); ?>" />
        <?php if($error['teacher_id'] == 'blank'): ?>
        <p>* 職員番号を入力してください </p>
        <?php endif; ?>
        </dd>
        <dt>パスワード</dt>
        <dd><input type="password" name="password" maxlength="255" value="<?php echo htmlspecialchars($_POST['password'],3) ?>" />
        <?php if($error['password'] == 'blank'): ?>
        <p>*パスワードを入力してください</p>
        <?php endif; ?>
        <?php if($error['password'] == 'length'): ?>
        <p>*パスワードは6文字以上で入力してください</p>
        <?php endif; ?>
        </dd>
    </dl>
    <div><input type="submit" value="送信"></div>
<form>
