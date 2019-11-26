<?php
session_start();
require("../../core/pdo_connect.php");
require("../../parts/login_auth.php");

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
    if($_POST['publication'] == ''){
        $error['publication'] = 'blank';
    }
    if($_POST['genre'] == ''){
        $error['genre'] = 'blank';
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
    $thumbnail_name = $_FILES['thumbnail']['name'];
    if(!empty($thumbnail_name)){
        $thumbnail_ext = substr($thumbnail_name, -3);
        if($thumbnail_ext != 'jpg' && $thumbnail_ext != 'png'){
            $error['thumbnail'] = 'type';
        }
    }else{
        $error['thumbnail'] = 'blank';
    }

    //書籍pdfのチェック
    $book_pdf_name = $_FILES['book_pdf']['name'];
    if(!empty($book_pdf_name)){
        $book_pdf_ext = substr($book_pdf_name, -3);
        if($book_pdf_ext != 'pdf'){
            $error['book_pdf'] = 'type';
        }
    }else{
        $error['book_pdf'] = 'blank';
    }



     //書籍番号の重複チェック
    if(empty($error)){
        $books = $db->prepare('SELECT COUNT(*) AS cnt FROM books WHERE book_id=?');
        $books->execute(array($_POST['book_id']));
        $record = $books->fetch();
        if($record['cnt'] > 0){
            $error['book_id'] = 'duplicate';
        }
    }

    //入力に空白が無ければ実行
    if(empty($error)){
        //サムネイルをアップロード
        $thumbnail = date('YmdHis') . $_FILES['thumbnail']['name'];
        move_uploaded_file($_FILES['thumbnail']['tmp_name'], '../assets/thumbnail/' . $thumbnail);

        //書籍pdfをアップロード
        $book_pdf = date('YmdHis') . $_FILES['book_pdf']['name'];
        move_uploaded_file($_FILES['book_pdf']['tmp_name'], '../assets/book_pdf/' . $book_pdf);

        $_SESSION['book'] = $_POST;
        $_SESSION['book']['thumbnail'] = $thumbnail;
        $_SESSION['book']['book_pdf'] = $book_pdf;
        header('Location: check.php');
        exit();
    }
}

if($_REQUEST['action'] == 'rewrite'){
    $_POST = $_SESSION['book'];
}
?>

<form action="" method="post" enctype="multipart/form-data">
    <dl>
        <dt>書籍番号</dt>
        <dd><input type="text" name="book_id" value="<?php echo htmlspecialchars($_POST['book_id'], 3); ?>" />
        <?php if($error['book_id'] == 'blank'): ?>
        <p>*書籍番号を入力してください</p>
        <?php endif; ?>
        <?php if($error['book_id'] == 'duplicate'): ?>
        <P>*この書籍番号は既に登録されています</P>
        <?php endif; ?>
        </dd>

        <dt>書籍名</dt>
        <dd><input type="text" name="book_name" value="<?php echo htmlspecialchars($_POST['book_name'], 3); ?>" />
        <?php if($error['book_name'] == 'blank'): ?>
        <p>*書籍名を入力してください</P>
        <?php endif; ?>
        </dd>

        <dt>作者</dt>
        <dd><input type="text" name="book_maker" value="<?php echo htmlspecialchars($_POST['book_maker'], 3); ?>" />
        <?php if($error['book_maker'] == 'blank'): ?>
        <p>*作者を入力してください</P>
        <?php endif; ?>
        </dd>

        <dt>出版社</dt>
        <dd><input type="text" name="publisher" value="<?php echo htmlspecialchars($_POST['publisher'], 3); ?>" />
        <?php if($error['publisher'] == 'blank'): ?>
        <p>*出版社を入力してください</P>
        <?php endif; ?>
        </dd>

        <dt>出版日</dt>
        <dd><input type="date" name="publication" value="<?php echo htmlspecialchars($_POST['publication'], 3); ?>" />
        <?php if($error['publication'] == 'blank'): ?>
        <p>*出版日を入力してください</P>
        <?php endif; ?>
        </dd>

        <dt>ジャンル</dt>
        <dd>
        <select name="genre">
        <option value="">選択してください</option>
        <option value="のりもの">のりもの</option>
        <option value="どうぶつ">どうぶつ</option>
        <option value="こんちゅう">こんちゅう</option>
        <option value="しょくぶつ">しょくぶつ</option>
        <option value="おばけ・ホラー">おばけ・ホラー</option>
        <option value="むかしばなし">むかしばなし</option>
        <option value="小説">小説</option>
        <option value="その他">その他</option>
        </select>
        <?php if($error['genre'] == 'blank'): ?>
        <p>*ジャンルを入力してください</P>
        <?php endif; ?>
        </dd>

        <dt>ページ数</dt>
        <dd><input type="number" name="page" value="<?php echo htmlspecialchars($_POST['page'], 3); ?>" />
        <?php if($error['page'] == 'blank'): ?>
        <p>*ページ数を入力してください</P>
        <?php endif; ?>
        </dd>

        <dt>対象年齢</dt>
        <dd><input type="number" name="age" value="<?php echo htmlspecialchars($_POST['age'], 3); ?>" />
        <?php if($error['age'] == 'blank'): ?>
        <p>*対象年齢を入力してください</P>
        <?php endif; ?>
        </dd>

        <dt>説明文</dt>
        <dd><textarea name="description" cols="50" rows="5"><?php echo htmlspecialchars($_POST['description'], 3); ?></textarea>
        <?php if($error['description'] == 'blank'): ?>
        <p>*説明文を入力してください</P>
        <?php endif; ?>
        </dd>

        <dt>サムネイル</dt>
        <dd><input type="file" name="thumbnail" size="35" />
        <?php if($error['thumbnail'] == 'blank'): ?>
        <p>*サムネイルを指定してください</P>
        <?php endif; ?>
        <?php if($error['thumbnail'] == 'type'): ?>
        <p>*gif または　jpg の画像を指定してください</P>
        <?php endif; ?>
        </dd>

        <dt>書籍pdf</dt>
        <dd><input type="file" name="book_pdf" size="35" />
        <?php if($error['book_pdf'] == 'blank'): ?>
        <p>*書籍pdfを指定してください</P>
        <?php endif; ?>
        <?php if($error['book_pdf'] == 'type'): ?>
        <p>*pdfファイルを指定してください</P>
        <?php endif; ?>
        
        </dd>
    </dl>
    <div><input type="submit" value="送信"></div>
</form>