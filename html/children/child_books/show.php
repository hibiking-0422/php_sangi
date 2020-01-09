<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">

<!--外部ファイル読み込み-->
<link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="/assets/css/slick.css"/>
<link rel="stylesheet" type="text/css" href="/assets/css/slick-theme.css"/>
<link rel="stylesheet" type="text/css" href="/assets/css/children/show.css"/>
<script type="text/javascript" src="/assets/js/jquery-3.4.1.min.js"></script>
<script type="text/javascript" src="/assets/js/slick.min.js"></script>
<!---->

<?php
session_start();
require("../../../core/pdo_connect.php");

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
    <img src = "../../assets/thumbnail/<?php echo htmlspecialchars($book['thumbnail'], 3); ?>"  width="500px" height="700px" alt="" />
</div>
<div class="book-element">
    <table>
        <tr>
            <th>ばんごう</th><td><?php echo htmlspecialchars($book['book_id'],3); ?></td>

            <th>ほんのなまえ</th><td><?php echo htmlspecialchars($book['book_name'],3); ?></td>
        </tr>
        <tr>
            <th>かいたひと</th><td><?php echo htmlspecialchars($book['book_maker'],3); ?></td>

            <th>かいしゃ</th><td><?php echo htmlspecialchars($book['publisher'],3); ?></td>
        </tr>
        <tr>
            <th>うられたひ</th><td><?php echo htmlspecialchars($book['publication'],3); ?></td>

            <th>ジャンル</th><td><?php echo htmlspecialchars($book['genre'],3); ?></td>
        </tr>
        <tr>
            <th>ながさ</th><td><?php echo htmlspecialchars($book['page'],3); ?></td>

            <th>だれむけ</th><td><?php echo htmlspecialchars($book['age'],3); ?>さいむけ</td>
        </tr>
        <tr>
        <th>よまれたかいすう</th>
        <td>
        <?php 
            
            if(empty($book['views'])){
                echo 0;
            }else{
                echo htmlspecialchars($book['views'],3); 
            }
        ?>
        </td>

        <th>こうひょうか</th>
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
        </table>
</div>  
    <div class="discription"><div class="discription-title">しょうかい</div>
    <?php echo nl2br(htmlspecialchars($book['description'],3)); ?>
</div>

<a href="../index.php"><div class="back-button">もどる</div></a>
<a href="show_pdf.php?id=<?php print($book['id']); ?>"><div class="read-button">よむ</div></a>

<hr>

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

<div class= "comment-form">
<form action="" method="post">
<dl>
    <dd>
        <input type="checkbox" name="good" value= 1 >　こうひょうか
    </dd>
    <br>
    <dd>
        <p><input class="big-font" type="text" name="name" placeholder="名前"></p>
    <dd>
        <textarea class="big-font" name="comment" cols="30" rows="5" placeholder="コメント"></textarea>
    </dd>
</dl>
<div>
    <input class="comment-submit" type="submit" value="送信" />
</div>
</form>
</div>

</body>
</html>