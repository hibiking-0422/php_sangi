<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">

<link rel="stylesheet" type="text/css" href="/assets/css/book_mana/index.css"/>
<link rel="stylesheet" type="text/css" href="/assets/css/slick.css"/>
<link rel="stylesheet" type="text/css" href="/assets/css/slick-theme.css"/>
<script type="text/javascript" src="/assets/js/jquery-3.4.1.min.js"></script>
<script type="text/javascript" src="/assets/js/slick.min.js"></script>
<style>
.slider{
    margin: auto;
    width: 300px;
}
.slider img{
    height: auto;
    width: 100%;
}

.slick-prev:before,
.slick-next:before {
    color: black;
    position: relative;
    z-index: 1;
}

</style>
<script>
$(function(){
$('.slider').slick({
    autoplay:true,
    autoplaySpeed:5000,
    dots:true,
});
});

</script>

<?php
session_start();
require("../../core/pdo_connect.php");
require("../../parts/login_auth.php");

$sql = 'SELECT * FROM books';

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
<p><a href="/book_mana/new.php">新規作成</a></p>

<div class="search-box">
<form action="/search/index.php" method="post">
<input type="text" name="book_name">
<input type="submit" value="検索">
</form>


<form action="" method="get">
<p><input type="radio" name="sort" value="new">新しい順 / <input type="radio" name="sort" value="old">古い順</p>
<p><input type="radio" name="sort" value="few_views">閲覧数の少ない順 / <input type="radio" name="sort" value="many_views">閲覧数の多い順</p>
<p><input type="radio" name="sort" value="few_pages">ページ数の少ない順 / <input type="radio" name="sort" value="many_pages">ページ数の多い順</p>
<p><input type="radio" name="sort" value="low_age">対象年齢の低い順 / <input type="radio" name="sort" value="high_age">対象年齢の高い順</p>
<input type="submit" value="並び替え">
</form>
</div>


<div class="slider">
<?php
foreach ($books as $book):
?>
<a href="show.php?id=<?php print($book['id']); ?>"><img src = "../assets/thumbnail/<?php echo htmlspecialchars($book['thumbnail'], 3); ?>"  width="150" height="300" alt="" /></a>
<?php
endforeach;
?>
</div>

</body>
</html>