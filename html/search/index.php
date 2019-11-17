<?php
session_start();
require("../../core/pdo_connect.php");
require("../../parts/login_auth.php");


$sql = "SELECT * FROM books WHERE book_name LIKE '%" . $_POST['book_name'] . "%'";
$books = $db->query($sql);

?>

<?php
foreach ($books as $book):
?>
<p>
        <dd>
        <img src = "../assets/thumbnail/<?php echo htmlspecialchars($book['thumbnail'], 3); ?>"  width="auto" height=
        "200" alt="" />
        </dd>
        <dd>
        ・<a href="/book_mana/show.php?id=<?php print($book['id']); ?>"><?php echo htmlspecialchars($book['book_name'], 3); ?></a>
        </dd>
</p>
<?php
endforeach;
?>
