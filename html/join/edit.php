<?php
session_start();
require("../../core/pdo_connect.php");
require("../../parts/login_auth.php");

if(isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])){
    $id = $_REQUEST['id'];

    $admins = $db->prepare('SELECT * FROM admin WHERE id = ?');
    $admins->execute(array($id));
    $admin  = $admins->fetch();
}

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
    if($_POST['password'] != $_POST['re_password']){
        $error['re_password'] = 'different';
    }

    //職員番号の重複チェック
    if(empty($error)){
        $admin_check = $db->prepare('SELECT COUNT(*) AS cnt FROM admin WHERE teacher_id=?');
        $admin_check->execute(array($_POST['teacher_id']));
        $record = $admin_check->fetch();
        if($record['cnt'] > 0){
            $error['teacher_id'] = 'duplicate';
        }
    }

    //入力に空白が無ければ実行
    if(empty($error)){
        $_SESSION['update'] = $_POST;
        header('Location: update.php');
        exit(); 
    }
}

?>

<form action="" method="post">
    <dl>
        <input type="hidden" name="id" value="<?php print($id); ?>">
        <dt>職員番号</dt>
        <dd><input type="text" name="teacher_id" maxlength="255" value="<?php echo htmlspecialchars($admin['teacher_id'], 3); ?>" />
        <?php if($error['teacher_id'] == 'blank'): ?>
        <p>* 職員番号を入力してください </p>
        <?php endif; ?>
        <?php if($error['teacher_id'] == 'duplicate'): ?>
        <p>*この職員番号は既に登録されています</p>
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
        <dt>パスワード(確認)</dt>
        <dd><input type="password" name="re_password" maxlength="255" value="<?php echo htmlspecialchars($_POST['re_password'],3) ?>" />
        <?php if($error['re_password'] == 'different'): ?>
        <p>*パスワードと確認用パスワードが異なります</P>
        <?php endif; ?>
        </dd>

    </dl>
    <div><input type="submit" value="送信"></div>
</form>