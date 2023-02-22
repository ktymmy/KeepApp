<?php
    require_once __DIR__ . "/def.php";
    require_once __DIR__ . "/utils.php";
    //GETデータ取得
    $site_name = filter_input(INPUT_GET, "site_name");
    //site_nameがなければ、homeに戻って、処理終了
    if(!$site_name){
        header("Location: home.php");
        exit;
    }

    //site_nameをキーにDBからレコードを取得
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    try{
        $db = new PDO($dsn, DB_USER, DB_PASS);
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT * FROM keep_url WHERE site_name = :site_name";

        $stmt = $db->prepare($sql);
        $stmt->bindparam(':site_name', $site_name, PDO::PARAM_STR);

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
            <h2 class="h2">DELETE</h2>
        </div>
    </header>

    <main>

    <form action="deleteresult.php" method="POST">
        <div class="col">
            <p class="text-danger fs-5 fw-bold">
                <h3>下記のデータを<span class="attention">削除</span>します。
            </p>
        </div>

        <div id="containter">

            <div class="col">
                <label class="form-label" for="site_name">Name</label>
                <input  class="textbox" type="text" name="site_name" id="site_name"  value="<?=$result["site_name"]?>" readonly>
            </div>

            <div class="col">
                <label class="form-label" for="url">Url</label>
                <input  class="textbox" type="text" name="url" id="url"  value="<?=$result["url"]?>" readonly>
            </div>

            <div class="p-5 d-grid gap-2 d-md-flex justify-content-md-start">
                <button type="submit" class="R-button">削除</button>
            </div><!-- .p-5 d-grid gap-2 d-md-flex justify-content-md-end -->

            <div class="btn-wrap--perspective">
                <a href="list.php" class="btn btn-3d btn-3db">戻る<i class="fas fa-angle-down fa-position-right"></i></a>
            </div>
            

        </div>

    </form> 
    
    </main>
</body>

</html>