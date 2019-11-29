<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">

<!--外部ファイル読み込み-->
<link rel="stylesheet" type="text/css" href="/assets/css/slick.css"/>
<link rel="stylesheet" type="text/css" href="/assets/css/slick-theme.css"/>
<link rel="stylesheet" type="text/css" href="/assets/css/book_mana/index.css"/>
<script type="text/javascript" src="/assets/js/jquery-3.4.1.min.js"></script>
<script type="text/javascript" src="/assets/js/slick.min.js"></script>
<!---->

<?php
session_start();
require("../../core/pdo_connect.php");
require("../../parts/login_auth.php");

$count = 0;
$index_page = 12;

if(isset($_REQUEST['page']) && is_numeric($_REQUEST['page'])){
        $page = $_REQUEST['page'];
}else{
        $page = 1;
        unset($_SESSION['sql']);
}
$start = $index_page * ($page - 1);

if(empty($_SESSION['sql'])){

if(empty($_GET['keyword'])){
        $sql = 'SELECT * FROM books';
        if(!empty($_GET['genre']) or !empty($_GET['prev_publication'])){
                $sql .= " WHERE ";
        }
}else{
        $keyword = $_GET['keyword'];
        $sql = "SELECT * FROM books WHERE book_name LIKE '%$keyword%' OR book_maker LIKE '%$keyword%' OR publisher LIKE '%$keyword%'";
        $count++;
}

if(!empty($_GET['genre'])){

        if($count > 0){
                if(empty($_GET['vagueness'])){
                        $sql .= " AND ";
                }else{
                        $sql .= " OR ";
                }
        }
        $genre = $_GET['genre'];
        $sql .= " genre = '";
        $sql  .= "$genre'";
        $count++;
}

if(!empty($_GET['prev_publication'])){

        if($count > 0){
                if(empty($_GET['vagueness'])){
                        $sql .= " AND ";
                }else{
                        $sql .= " OR ";
                }
        }
        $prev_publication = $_GET['prev_publication'];
        $sql .= " publication >= '";
        $sql  .= "$prev_publication'";
        $count++;
}

if(!empty($_GET['next_publication'])){

        if($count > 0){
                        $sql .= " AND ";
        }
        $next_publication = $_GET['next_publication'];
        $sql .= " publication <= '";
        $sql  .= "$next_publication'";
        $count++;
}

switch($_GET['sort']) {
        case 'new':
                $sql .= ' ORDER BY publication DESC';
                break;
        case 'old':
                $sql .= ' ORDER BY publication ASC';
                break;
        case 'few_views':
                $sql .= ' ORDER BY views ASC';
                break;
        case 'many_views':
                $sql .= ' ORDER BY views DESC';
                break;
        case 'few_pages':
                $sql .= ' ORDER BY page ASC';
                break;
        case 'many_pages':
                $sql .= ' ORDER BY page DESC';
                break;
        case 'low_age':
                $sql .= ' ORDER BY  age ASC';
                break;
        case 'high_age':
                $sql .= ' ORDER BY age DESC';
                break;
        default:
                break;
}

if(empty($_GET['sort'])){
        $sql .= " ORDER BY id LIMIT ?,$index_page";
}else{
        $sql .= " LIMIT ?,$index_page";
}

}else{
        $sql = $_SESSION['sql'];
}

$book_counts_sql = "SELECT COUNT(*) FROM books"; 
$book_counts = $db->query($book_counts_sql);
$book_count = $book_counts->fetchColumn();

$books = $db->prepare($sql);
$books->bindParam(1, $start, PDO::PARAM_INT);
$books->execute();

$_SESSION['sql'] = $sql;

?>

</head>

<body>

<div class="search-box clearfix">
<div class="search-area">
<a href="/book_mana/new.php"><div class="new-button">新規作成</div></a>

<form action="" method="get">

<div class="search-h">キーワード</div>
<input type="text" name="keyword">

<p>
<div class="search-h">ジャンル</div>
<p><input type="radio" name="genre" value="のりもの">のりもの</p>
<p><input type="radio" name="genre" value="どうぶつ">どうぶつ</p>
<p><input type="radio" name="genre" value="こんちゅう">こんちゅう</p>
<p><input type="radio" name="genre" value="しょくぶつ">しょくぶつ</p>
<p><input type="radio" name="genre" value="おばけ・ホラー">おばけ・ホラー</p>
<p><input type="radio" name="genre" value="むかしばなし">むかしばなし</p>
<p><input type="radio" name="genre" value="わらい">わらい</p>
<p><input type="radio" name="genre" value="ゆるふわ">ゆるふわ</p>
<p><input type="radio" name="genre" value="SF・ファンタジー">SF・ファンタジー</p>
<p><input type="radio" name="genre" value="小説">小説</p>
<p><input type="radio" name="genre" value="その他">その他</p>
</p>

<p>
<div class="search-h">出版日</div>
<p>
        <p><input type="date" name="prev_publication" />  から</p>
        <p><input type="date" name="next_publication" />  まで</p>
</p>
</p>

<p>
<div class="search-h">並び替え</div>
<p>
<select name="sort">
        <option value="">選択してください</option>
        <option value="new">新しい順</option>
        <option value="old">古い順</option>
        <option value="few_views">閲覧数の少ない順</option>
        <option value="many_views">閲覧数の多い順</option>
        <option value="few_pages">ページ数の少ない順</option>
        <option value="many_pages">ページ数の多い順</option>
        <option value="low_age">対象年齢の低い順</option>
        <option value="high_age">対象年齢の高い順</option>
</select>
</p>
</p>

<p><input type="checkbox" name="vagueness" value="vagueness">あいまい検索</p>
<input type="submit" value="検索">
</form>
</div>
</div>

<div class="views">

<?php
$i = 0;
$start = '<p>';
$end = '</p>';
$hr = '<hr>';
foreach ($books as $book):
if($i % 3 == 0){
        echo $start;
}


$id = $book['id'];
$goods_sql = 'SELECT SUM(good) as good FROM comments WHERE b_id=' . $id;
$goods = $db->query($goods_sql);
$good = $goods->fetch();

?>
<div class="book-box">
        <div class="book-scale">
                       <div class="book-number"><?php echo (($i+1) + ($page-1)*$index_page);?></div>
                <div class="book-shadow">
                        <a href="show.php?id=<?php print($book['id']); ?>">
                                <img src = "../assets/thumbnail/<?php echo htmlspecialchars($book['thumbnail'], 3); ?>"  width="250" height="400" alt="" />
                        </a>
                </div>
                <div class="book-text">
                        <div class="under-line"><?php echo htmlspecialchars($book['book_name'], 3); ?></div>
                        <div class="under-line">高評価:
                        <?php 
                                if(empty($good['good'])){
                                        echo 0;
                                }else{
                                        echo htmlspecialchars($good['good'],3); 
                                }
                        ?>  
                        </div>
                </div>
        </div>
</div>　　　　　   　　　　　
<?php
if($i % 3 == 2){
        echo $end;
        echo $hr;
}
$i = $i + 1;
endforeach;
?>

<hr>
<?php if($page >= 2): ?>
<a href="index.php?page=<?php print($page-1); ?>"><?php print($page-1); ?>ページ</a>
<?php endif; ?>
|
<a href="index.php?page=<?php print($page+1); ?>"><?php print($page+1); ?>ページ</a>
</div>

</body>
</html>