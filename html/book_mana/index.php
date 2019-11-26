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
if(empty($_GET['keyword'])){
        $sql = 'SELECT * FROM books';

        if(!empty($_GET['genre']) or !empty($_GET['prev_publication'])){
                $sql .= " WHERE ";
        }
}else{
        $keyword = $_GET['keyword'];
        $sql = "SELECT * FROM books WHERE book_name LIKE '%" . $keyword . "%' OR book_maker LIKE '%" . $keyword . "%' OR publisher LIKE '%" . $keyword . "%'";
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

$books = $db->query($sql);
?>

</head>

<body>

<div class="search-box clearfix">
<p><a href="/book_mana/new.php">新規作成</a></p>

<form action="" method="get">

キーワード<br>
<input type="text" name="keyword">

<p>
ジャンル<br>
<p><input type="radio" name="genre" value="のりもの">のりもの</p>
<p><input type="radio" name="genre" value="どうぶつ">どうぶつ</p>
<p><input type="radio" name="genre" value="こんちゅう">こんちゅう</p>
<p><input type="radio" name="genre" value="しょくぶつ">しょくぶつ</p>
<p><input type="radio" name="genre" value="おばけ・ホラー">おばけ・ホラー</p>
<p><input type="radio" name="genre" value="むかしばなし">むかしばなし</p>
<p><input type="radio" name="genre" value="小説">小説</p>
<p><input type="radio" name="genre" value="その他">その他</p>
</p>

<p>
出版日 <br>
<input type="date" name="prev_publication" />~<input type="date" name="next_publication" />
<p>
<p>
並び替え<br>
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

<p><input type="checkbox" name="vagueness" value="vagueness">あいまい検索</p>
<input type="submit" value="検索">
</form>
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
                <p><?php echo $i+1;?></p>
                <div class="book-shadow">
                        <a href="show.php?id=<?php print($book['id']); ?>">
                                <img src = "../assets/thumbnail/<?php echo htmlspecialchars($book['thumbnail'], 3); ?>"  width="250" height="400" alt="" />
                        </a>
                </div>
                <div class="book-text">
                        <?php echo htmlspecialchars($book['book_name'], 3); ?>
                        <p>高評価:
                        <?php 
                                if(empty($good['good'])){
                                        echo 0;
                                }else{
                                        echo htmlspecialchars($good['good'],3); 
                                }
                        ?>  
                        </p>
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
<?php echo $sql; ?>
</div>
</body>
</html>
