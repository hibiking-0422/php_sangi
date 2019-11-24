<?php
session_start();
require("../../core/pdo_connect.php");
require("../../parts/login_auth.php");

if(isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])){
    $id = $_REQUEST['id'];
}

if(!empty($_POST)){

    //空白チェック
    if($_POST['request'] == ''){
        $error['request'] = 'blank';
    }
    
    //入力に空白が無ければ実行
    if(empty($error)){
        $_SESSION['requests'] = $_POST;
        header('Location: create.php');
        exit(); 
    }
}

?>

<form action="" method="post" >
    <dl>
        <dt>リクエスト</dt>
        <dd><textarea name="request" cols="50" rows="5"></textarea>
        <?php if($error['request'] == 'blank'): ?>
        <p>*文字を入力してください</P>
        <?php endif; ?>
        </dd>
    </dl>
    <div><input type="submit" value="送信"></div>
</form>