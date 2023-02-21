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
    <link href="./table.css" rel="stylesheet">
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
                下記のデータを削除します。
            </p>
        </div>


        <div class="col">
            <label class="form-label" for="site_name">Name</label>
            <input type="text" name="site_name" id="site_name" class="form-control form-control-lg border-info bg-light" value="<?=$result["site_name"]?>" readonly>
        </div>

        <div class="col">
            <label class="form-label" for="url">Url</label>
            <input type="text" name="url" id="url" class="form-control form-control-lg border-info bg-light" value="<?=$result["url"]?>" readonly>
        </div>

        <div class="p-5 d-grid gap-2 d-md-flex justify-content-md-start">
            <button type="submit" class="btn btn-danger btn-lg">削除</button>
            <a class="btn btn-secondary btn-lg" href="">戻る</a>
        </div><!-- .p-5 d-grid gap-2 d-md-flex justify-content-md-end -->


    </form> 
    </main>
</body>

</html>