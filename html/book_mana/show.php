<?php
session_start();
require("../../core/pdo_connect.php");
require("../../parts/login_auth.php");

if(isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])){
    $id = $_REQUEST['id'];

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

    <dl>
        <dt>書籍番号</dt>
        <dd>
        <?php echo htmlspecialchars($book['book_id'],3); ?>
        </dd>

        <dt>書籍名</dt>
        <dd>
        <?php echo htmlspecialchars($book['book_name'],3); ?>
        </dd>

        <dt>作者</dt>
        <dd>
        <?php echo htmlspecialchars($book['book_maker'],3); ?>
        </dd>

        <dt>出版社</dt>
        <dd>
        <?php echo htmlspecialchars($book['publisher'],3); ?>
        </dd>

        <dt>出版日</dt>
        <dd>
        <?php echo htmlspecialchars($book['publication'],3); ?>
        </dd>

        <dt>ジャンル</dt>
        <dd>
        <?php echo htmlspecialchars($book['genre'],3); ?>
        </dd>

        <dt>ページ数</dt>
        <dd>
        <?php echo htmlspecialchars($book['page'],3); ?>
        </dd>

        <dt>対象年齢</dt>
        <dd>
        <?php echo htmlspecialchars($book['age'],3); ?>
        </dd>

        <dt>説明文</dt>
        <dd>
        <?php echo htmlspecialchars($book['description'],3); ?>
        </dd>

        <dt>閲覧数</dt>
        <dd>
        <?php 
            
            if(empty($book['views'])){
                echo 0;
            }else{
                echo htmlspecialchars($book['views'],3); 
            }
        ?>
        </dd>

        <dt>サムネイル</dt>
        <dd>
        <img src = "../assets/thumbnail/<?php echo htmlspecialchars($book['thumbnail'], 3); ?>"  width="auto" height=
        "200" alt="" />
        </dd>

        <dt>書籍pdf</dt>
        <dd>
        <?php echo htmlspecialchars($book['book_pdf'],3); ?>
        </dd>

        <dt>高評価</dt>
        <dd>
        <?php 
            if(empty($good['good'])){
                echo 0;
            }else{
                echo htmlspecialchars($good['good'],3); 
            }
        ?>
        </dd>

        <dt>登録者</dt>
        <dd>
        <?php echo htmlspecialchars($admin['teacher_id'],3); ?>
        </dd>
    </dl>
    
<p>編集者履歴</P>
<?php
foreach ($edit_logs as $edit_log):
$t_id_sql = 'SELECT teacher_id FROM admin WHERE id=' .  $edit_log['t_id'];
$t_ids = $db->query($t_id_sql);
$t_id = $t_ids->fetch();
?>
<p><?php echo htmlspecialchars($t_id['teacher_id'], 3); ?>　<?php echo htmlspecialchars($edit_log['created'], 3); ?></p>
<?php
endforeach;
?>

<p><a href="edit.php?id=<?php print($book['id']); ?>">編集</a></p>
<p><a href="delete.php?id=<?php print($book['id']); ?>">削除</a></p>
<p><a href="show_pdf.php?id=<?php print($book['id']); ?>">読む</a></p>

<form action="" method="post">
<dl>
    <dd>
        <input type="checkbox" name="good" value= 1 >高評価
    </dd>
    <dd>
        <p><input type="text" name="name" placeholder="名前"></p>
    <dd>
        <textarea name="comment" cols="50" rows="5" placeholder="コメント"></textarea>
    </dd>
</dl>
<div>
    <input type="submit" value="コメントを送信" />
</div>
</form>

<?php
foreach ($posts as $post):
?>
<p><?php echo htmlspecialchars($post['name'], 3); ?>　<?php echo htmlspecialchars($post['comment'], 3); ?>　<?php echo htmlspecialchars($post['created'], 3); ?>　<?php echo htmlspecialchars($post['good'], 3); ?></p>
<?php
endforeach;
?>