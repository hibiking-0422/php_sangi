<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">

<!--外部ファイル読み込み-->
<link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="/assets/css/slick.css"/>
<link rel="stylesheet" type="text/css" href="/assets/css/slick-theme.css"/>
<link rel="stylesheet" type="text/css" href="/assets/css/book_mana/edit.css"/>
<!---->


<?php
session_start();
require("../../core/pdo_connect.php");
require("../../parts/login_auth.php");

if(isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])){
    $id = $_REQUEST['id'];

    $books = $db->prepare('SELECT * FROM books WHERE id = ?');
    $books->execute(array($id));
    $book  = $books->fetch();
}

if(!empty($_POST)){

    //空白チェック
    if($_POST['book_id'] == ''){
        $error['book_id'] = 'blank';
    }
    if($_POST['book_name'] == ''){
        $error['book_name'] = 'blank';
    }
    if($_POST['book_maker'] == ''){
        $error['book_maker'] = 'blank';
    }
    if($_POST['publisher'] == ''){
        $error['publisher'] = 'blank';
    }
    if($_POST['genre'] == ''){
        $error['genre'] = 'blank';
    }
    if($_POST['publication'] == ''){
        $error['publication'] = 'blank';
    }
    if($_POST['page'] == ''){
        $error['page'] = 'blank';
    }
    if($_POST['age'] == ''){
        $error['age'] = 'blank';
    }
    if($_POST['description'] == ''){
        $error['description'] = 'blank';
    }

    //サムネイル画像のチェック
    if(empty($_POST['no_thumbnail'])){
        $thumbnail_name = $_FILES['thumbnail']['name'];
        if(!empty($thumbnail_name)){
            $thumbnail_ext = substr($thumbnail_name, -3);
            if($thumbnail_ext != 'jpg' && $thumbnail_ext != 'png'){
                $error['thumbnail'] = 'type';
            }
        }else{
            $error['thumbnail'] = 'blank';
        }
    }

    //書籍pdfのチェック
    if(empty($_POST['no_book_pdf'])){
        $book_pdf_name = $_FILES['book_pdf']['name'];
        if(!empty($book_pdf_name)){
            $book_pdf_ext = substr($book_pdf_name, -3);
            if($book_pdf_ext != 'pdf'){
                $error['book_pdf'] = 'type';
            }
        }else{
            $error['book_pdf'] = 'blank';
        }
    }

     //書籍番号の重複チェック
    if(empty($error)){
        $books = $db->prepare('SELECT COUNT(*) AS cnt FROM books WHERE book_id=?');
        $books->execute(array($_POST['book_id']));
        $record = $books->fetch();
        if($record['cnt'] > 0){
            $error['book_id'] = 'duplicate';
        }
        if($book['book_id'] == $_POST['book_id']){
            unset($error['book_id']);
        }
    }

    //入力に空白が無ければ実行
    if(empty($error)){

        $_SESSION['update'] = $_POST;

        if(empty($_POST['no_thumbnail'])){
            //サムネイルをアップロード
            $thumbnail = date('YmdHis') . $_FILES['thumbnail']['name'];
            move_uploaded_file($_FILES['thumbnail']['tmp_name'], '../assets/thumbnail/' . $thumbnail);
            $_SESSION['update']['thumbnail'] = $thumbnail;
        }else{
            $_SESSION['update']['thumbnail'] = $book['thumbnail'];
        }

        if(empty($_POST['no_book_pdf'])){
            //書籍pdfをアップロード
            $book_pdf = date('YmdHis') . $_FILES['book_pdf']['name'];
            move_uploaded_file($_FILES['book_pdf']['tmp_name'], '../assets/book_pdf/' . $book_pdf);
            $_SESSION['update']['book_pdf'] = $book_pdf;
        }else{
            $_SESSION['update']['book_pdf'] = $book['book_pdf'];
        }

        
        
        header('Location: update.php');
        exit();
    }
}


if($_REQUEST['action'] == 'rewrite'){
    $_POST = $_SESSION['book'];
}
?>


<?php if(!empty($error)): ?>
<div class="error-area">
    <div class="error-title">Warning!</div>
        <?php if($error['book_id'] == 'blank'): ?>
        <p>*書籍番号を入力してください</p>
        <?php endif; ?>
        <?php if($error['book_id'] == 'duplicate'): ?>
        <P>*この書籍番号は既に登録されています</P>
        <?php endif; ?>

        <?php if($error['book_name'] == 'blank'): ?>
        <p>*書籍名を入力してください</P>
        <?php endif; ?>

        <?php if($error['book_maker'] == 'blank'): ?>
        <p>*作者を入力してください</P>
        <?php endif; ?>

        <?php if($error['publisher'] == 'blank'): ?>
        <p>*出版社を入力してください</P>
        <?php endif; ?>

        <?php if($error['publication'] == 'blank'): ?>
        <p>*出版日を入力してください</P>
        <?php endif; ?>

        <?php if($error['genre'] == 'blank'): ?>
        <p>*ジャンルを入力してください</P>
        <?php endif; ?>

        <?php if($error['page'] == 'blank'): ?>
        <p>*ページ数を入力してください</P>
        <?php endif; ?>

        <?php if($error['age'] == 'blank'): ?>
        <p>*対象年齢を入力してください</P>
        <?php endif; ?>

        <?php if($error['description'] == 'blank'): ?>
        <p>*説明文を入力してください</P>
        <?php endif; ?>

        <?php if($error['thumbnail'] == 'blank'): ?>
        <p>*サムネイルを指定してください</P>
        <?php endif; ?>
        <?php if($error['thumbnail'] == 'type'): ?>
        <p>*gif または　jpg の画像を指定してください</P>
        <?php endif; ?>

        <?php if($error['book_pdf'] == 'blank'): ?>
        <p>*書籍pdfを指定してください</P>
        <?php endif; ?>
        <?php if($error['book_pdf'] == 'type'): ?>
        <p>*pdfファイルを指定してください</P>
        <?php endif; ?>
</div>
<?php endif; ?>

<form action="" method="post" enctype="multipart/form-data">
<input type="hidden" name="id" value="<?php print($id); ?>">
<div class="thumbnail">
    <img src = "../assets/thumbnail/<?php echo htmlspecialchars($book['thumbnail'], 3); ?>"  width="400px" height="550px" alt="" />

            <table class="shadow-none">
                <tr>
                    <td class="border-none"><input type="checkbox" name="no_thumbnail" value= 1 >変更しない</td>
                </tr>
                <tr>
                    <th>サムネイル</th><td><input type="file"  name="thumbnail" class="margin-file" /></td>
                </tr>
                <tr>
                    <td class="border-none"><input type="checkbox" name="no_book_pdf" value= 1 >変更しない</td>
                </tr>
                <tr>
                    <th>書籍pdf</th><td><input type="file" name="book_pdf" class="margin-file"/></td>
                <tr>
            </table>    
</div>

    <div class="book-element">
        <table class="table-element">
            <th>書籍番号</th>
            <td><input type="text" name="book_id" value="<?php echo htmlspecialchars($book['book_id'], 3); ?>" /></td>
        
            <th>書籍名</th>
            <td><input type="text" name="book_name" value="<?php echo htmlspecialchars($book['book_name'], 3); ?>" /></td>
        </tr>
        <tr>
            <th>作者</th>
            <td><input type="text" name="book_maker" value="<?php echo htmlspecialchars($book['book_maker'], 3); ?>" /></td>

            <th>出版社</th>
            <td><input type="text" name="publisher" value="<?php echo htmlspecialchars($book['publisher'], 3); ?>" /></td>        
        </tr>
        <tr>

        <th>出版日</th>
        <td><input type="date" name="publication" value="<?php echo htmlspecialchars($book['publication'], 3); ?>" /></td>

        <th>ジャンル</th>
        <td>
        <select name="genre">
        <option value="">選択してください</option>
        <option value="のりもの">のりもの</option>
        <option value="どうぶつ">どうぶつ</option>
        <option value="こんちゅう">こんちゅう</option>
        <option value="しょくぶつ">しょくぶつ</option>
        <option value="おばけ・ホラー">おばけ・ホラー</option>
        <option value="むかしばなし">むかしばなし</option>
        <option value="わらい">わらい</option>
        <option value="ゆるふわ">ゆるふわ</option>
        <option value="SF・ファンタジー">SF・ファンタジー</option>
        <option value="小説">小説</option>
        <option value="その他">その他</option>
        </select>
        </td>   
        </tr>
        <tr>
            <th class="border-none">ページ数</th>
            <td class="border-none"><input type="number" name="page" value="<?php echo htmlspecialchars($book['page'], 3); ?>" /></td>

            <th class="border-none">対象年齢</th>
            <td class="border-none"><input type="number" name="age" value="<?php echo htmlspecialchars($book['age'], 3); ?>" /></td>
        </tr>
    </table>
</div>
        <div class="discription"><div class="discription-title">紹介</div>
            <div id="app">
                <pre><textarea  name="description" cols="50" rows="5"><?php echo nl2br(htmlspecialchars($book['description'], 3)); ?></textarea></pre>
            </div>
        </div>

    <a href="delete.php?id=<?php print($book['id']); ?>"><div class="delete-button">削除</div></a>
    <input type="submit"  class="update-button" value="修正" />
    
</form>