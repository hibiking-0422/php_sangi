<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">

<!--外部ファイル読み込み-->
<link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="/assets/css/slick.css"/>
<link rel="stylesheet" type="text/css" href="/assets/css/slick-theme.css"/>
<link rel="stylesheet" type="text/css" href="/assets/css/book_mana/show.css"/>
<script type="text/javascript" src="/assets/js/jquery-3.4.1.min.js"></script>
<script type="text/javascript" src="/assets/js/slick.min.js"></script>
<!---->

<?php
session_start();
require("../../core/pdo_connect.php");
require("../../parts/login_auth.php");

if(isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])){
    $id = $_REQUEST['id'];
    $_SESSION['book_id'] = $id;

    $books = $db->prepare('SELECT * FROM books WHERE id = ?');
    $books->execute(array($id));
    $book  = $books->fetch();

    $admins = $db->prepare('SELECT * FROM admin WHERE id = ?');
    $admins->execute(array($book['admin_id']));
    $admin = $admins->fetch();

    $post_sql = 'SELECT * FROM comments WHERE b_id=' . $id;
    $posts = $db->query($post_sql);

    $edit_log_sql = 'SELECT * FROM edit_log WHERE b_id=' . $id;
    $edit_logs = $db->query($edit_log_sql);

    $goods_sql = 'SELECT SUM(good) as good FROM comments WHERE b_id=' . $id;
    $goods = $db->query($goods_sql);
    $good = $goods->fetch();
}

if(!empty($_POST)){

    if($_POST['comment'] != ''){

        if(empty($_POST['good'])){
            $_POST['good'] = 0;
        }

        $comments = $db->prepare('INSERT INTO comments SET comment=?, b_id=?, good=?, name=?, created=NOW()');
        $comments->execute(array(
            $_POST['comment'],
            $book['id'],
            $_POST['good'],
            $_POST['name']
        ));
    }

    $url = "show.php?id=" . $id ;
    header("Location:" . $url );
}
?>
</head>
<body>

<div class="thumbnail">
    <img src = "../assets/thumbnail/<?php echo htmlspecialchars($book['thumbnail'], 3); ?>"  width="400px" height="550px" alt="" />
</div>
<div class="book-element">
    <table>
        <tr>
            <th>書籍番号</th><td><?php echo htmlspecialchars($book['book_id'],3); ?></td>

            <th>書籍名</th><td><?php echo htmlspecialchars($book['book_name'],3); ?></td>
        </tr>
        <tr>
            <th>作者</th><td><?php echo htmlspecialchars($book['book_maker'],3); ?></td>

            <th>出版社</th><td><?php echo htmlspecialchars($book['publisher'],3); ?></td>
        </tr>
        <tr>
            <th>出版日</th><td><?php echo htmlspecialchars($book['publication'],3); ?></td>

            <th>ジャンル</th><td><?php echo htmlspecialchars($book['genre'],3); ?></td>
        </tr>
        <tr>
            <th>ページ数</th><td><?php echo htmlspecialchars($book['page'],3); ?></td>

            <th>対象年齢</th><td><?php echo htmlspecialchars($book['age'],3); ?>歳</td>
        </tr>
        <tr>
        <th>閲覧数</th>
        <td>
        <?php 
            
            if(empty($book['views'])){
                echo 0;
            }else{
                echo htmlspecialchars($book['views'],3); 
            }
        ?>
        </td>

        <th>高評価</th>
            <td>
        <?php 
            if(empty($good['good'])){
                echo 0;
            }else{
                echo htmlspecialchars($good['good'],3); 
            }
        ?>
        </td>
        </tr>
        <tr>
            <th class="border-none">登録者</th><td class="border-none"><?php echo htmlspecialchars($admin['teacher_id'],3); ?></td>
        </tr>
        </table>
</div>  
    <div class="discription"><div class="discription-title">紹介</div>
    <?php echo nl2br(htmlspecialchars($book['description'],3)); ?>
</div>


<a href="show_pdf.php?id=<?php print($book['id']); ?>"><div class="read-button">読む</div></a>
<a href="edit.php?id=<?php print($book['id']); ?>"><div class="edit-button">編集</div></a>

<hr>

<div class="edit-log">
<div class="edit-title">編集者履歴</div>
<?php
foreach ($edit_logs as $edit_log):
$t_id_sql = 'SELECT teacher_id FROM admin WHERE id=' .  $edit_log['t_id'];
$t_ids = $db->query($t_id_sql);
$t_id = $t_ids->fetch();
?>
<div class="edit-texts">
    <span class="edit-text"><?php echo htmlspecialchars($t_id['teacher_id'], 3); ?></span>

    <span class="edit-text"><?php echo htmlspecialchars($edit_log['created'], 3); ?></span>
</div>
<?php
endforeach;
?>
</div>
</div>

<div class="comment">
<div class="comment-title">コメント</div>
<?php
$i = 1;
foreach ($posts as $post):
?>
<div class="comment-header">
    <span class="comment-username">
           <?php echo $i ?>.  <?php echo htmlspecialchars($post['name'], 3); ?>　
    </span>
    <span class="comment-date">
        <?php echo htmlspecialchars($post['created'], 3); ?>
    </sapn>
</div>
<div class="comment-area">
    <span class="comment-areatext"><?php echo nl2br(htmlspecialchars($post['comment'], 3)); ?></span>
</div>
<div class="comment-footer">
    <span class="comment-delete">
            <a href="/comment/delete.php?id=<?php print($post['id']); ?>">削除</a>
        </span>
    <span class="comment-good">
        <?php if($post['good'] == 1): ?>
            <i class="fas fa-heart pink"></i>
        <?php else: ?>
            <i class="far fa-heart pink"></i>
        <?php endif; ?>
    </span>
</div>
<?php
$i++;
endforeach;
?>
</div>
</body>
</html>