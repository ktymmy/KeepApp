<?php
    require_once __DIR__ . "/def.php";
    require_once __DIR__ . "/utils.php";
    //GETデータ取得
    $favo_site_name = filter_input(INPUT_GET, "favo_site_name");
    //site_nameがなければ、homeに戻って、処理終了
    if(!$favo_site_name){
        header("Location: home.php");
        exit;
    }

    //site_nameをキーにDBからレコードを取得
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    try{
        $db = new PDO($dsn, DB_USER, DB_PASS);
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT * FROM keep_favo_url WHERE favo_site_name = :favo_site_name";

        $stmt = $db->prepare($sql);
        $stmt->bindparam(':favo_site_name', $favo_site_name, PDO::PARAM_STR);

        $stmt->execute();

        $count = $stmt->rowCount();
        if($count != 1){
            header("Location: home.php");
            exit;
        }

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    }catch(PDOExeption $poe){
        echo "DBエラー" . $poe->getMassage();
    }finally{
        //切断
        $stmt = null;
        $db = null;
    }
?>



<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>keepApp</title>
    <link href="./all.css" rel="stylesheet">
    <link href="./delete.css" rel="stylesheet">
    <script src="./KeepApp.js"></script>
</head>

<body>

    <!--ヘッダー-->
    <header class="header">
        <div class="title-text">
            <h1 class="h1">keepApp</h1>
            <h2 class="h2">FAVORITE DELETE</h2>
        </div>
    </header>

    <main>

    <form action="deletefavoresult.php" method="POST">
        <div class="col">
            <p class="text-danger fs-5 fw-bold">
                下記のデータを削除します。
            </p>
        </div>

        <div id="containter">

            <div class="col">
                <label class="form-label" for="favo_site_name">Name</label>
                <input class="textbox" type="text" name="favo_site_name" id="favo_site_name" value="<?=$result["favo_site_name"]?>" readonly>
            </div>

            <div class="col">
                <label class="form-label" for="favo_url">Url</label>
                <input  class="textbox" type="text" name="favo_url" id="favo_url" value="<?=$result["favo_url"]?>" readonly>
            </div>

            <div class="p-5 d-grid gap-2 d-md-flex justify-content-md-start">
                <button type="submit" class="R-button">削除</button>   
            </div><!-- .p-5 d-grid gap-2 d-md-flex justify-content-md-end -->
            <a class="R-button" href="favorite.php">戻る</a>
        </div>

    </form> 
   
    </main>
</body>

</html>