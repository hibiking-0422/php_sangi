<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">

<!--外部ファイル読み込み-->
<link rel="stylesheet" type="text/css" href="/assets/css/slick.css"/>
<link rel="stylesheet" type="text/css" href="/assets/css/slick-theme.css"/>
<link rel="stylesheet" type="text/css" href="/assets/css/children/index.css"/>
<script type="text/javascript" src="/assets/js/jquery-3.4.1.min.js"></script>
<script type="text/javascript" src="/assets/js/slick.min.js"></script>
<!---->

<?php
session_start();
require("../../core/pdo_connect.php");

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
$max_page = ceil($book_count / $index_page);

$books = $db->prepare($sql);
$books->bindParam(1, $start, PDO::PARAM_INT);
$books->execute();

$_SESSION['sql'] = $sql;

?>

</head>

<body>

<?php if(!empty($_SESSION['comp'])): ?>
        <div class="comp-box"><h1><?php echo htmlspecialchars($_SESSION['comp'], 3); ?></h1></div>
        <?php unset($_SESSION['comp']); ?>
<?php endif; ?>

<a href="./child_requests/new.php"><div class="request-button">ほんのリクエスト</div></a>
<div class="search-box">
<div class="search-area">

<form action="" method="get">
<p>
<div class="key">
<p>
<div class="search-h">キーワード</div>
</p>
<input type="text" name="keyword">
</div>
</p>

<div class="genre">
<p>
<div class="search-h">ジャンル</div>
</p>
<select name="genre">
        <option value="">--えらんでね--</option>
        <option value="のりもの">のりもの</option>
        <option value="どうぶつ">どうぶつ</option>
        <option value="こんちゅう">こんちゅう</option>
        <option value="しょくぶつ">しょくぶつ</option>
        <option value="おばけ・ホラー">おばけ・ホラー</option>
        <option value="むかしばなし">むかしばなし</option>
        <option value="わらい">わらい</option>
        <option value="ゆるふわ">ゆるふわ</option>
        <option value="SF・ファンタジー">SF・ファンタジー</option>
        <option value="小説">しょうせつ</option>
        <option value="その他">そのた</option>
</select>
</div>

<div class="day">
<p>
<div class="search-h">しゅっぱんび</div>
<p>
        <p><input type="date" name="prev_publication" />  から
        <input type="date" name="next_publication" />  まで</p>
</p>
</p>
</div>
<div class="sort">
<p>
<div class="search-h">ならびかえ</div>
<p>
<select name="sort">
        <option value="">--えらんでね--</option>
        <option value="new">あたらしいじゅん</option>
        <option value="old">ふるいじゅん</option>
        <option value="few_views">よまれてないじゅん</option>
        <option value="many_views">よまれてるじゅん</option>
        <option value="few_pages">みじかいじゅん</option>
        <option value="many_pages">ながいじゅん</option>
        <option value="low_age">ていねんれいむけ</option>
        <option value="high_age">こうがくねんむけ</option>
</select>
</p>
</p>
</div>

<div class="search">
<p><input type="checkbox" name="vagueness" value="vagueness">あいまいけんさく</p>

<p>
<input class="search-button"type="submit" value="検索">
</p>

</div>
</form>
</div>
</div>

<hr>

<div class="views">

<?php
$i = 0;
$start = '<p>';
$end = '</p>';
$hr = '<hr>';
foreach ($books as $book):
if($i % 2 == 0){
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
                        <a href="./child_books/show.php?id=<?php print($book['id']); ?>">
                                <img src = "../assets/thumbnail/<?php echo htmlspecialchars($book['thumbnail'], 3); ?>"  width="250" height="400" alt="" />
                        </a>
                </div>
                <div class="book-text">
                        <div class="under-line"><?php echo htmlspecialchars($book['book_name'], 3); ?></div>
                        <div class="under-line">こうひょうか:
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
if($i % 2 == 1){
        echo $end;
}
$i = $i + 1;
endforeach;
?>

<hr>

<div class="paging">
<?php if($page >= 2): ?>
        <a href="index.php?page=<?php print($page-1); ?>"><div class="paging-left"><?php print($page-1); ?>ページ</div></a>
        <?php if($page < $max_page): ?>
                <a href="index.php?page=<?php print($page+1); ?>"><div class="paging-right"><?php print($page+1); ?>ページ</div></a>
        <?php endif; ?>
<?php else: ?>
        <a href="index.php?page=<?php print($page+1); ?>"><div class="paging-center"><?php print($page+1); ?>ページ</div></a>
<?php endif; ?>
</div>
</div>

</body>
</html>