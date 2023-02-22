<?php
    require_once __DIR__ . "/def.php";
    require_once __DIR__ . "/utils.php";
    if($_SERVER["REQUEST_METHOD"] !== "POST"){
        header("Location: home.php");
        exit;
    }

    $result = [
        "status"  => true, //エラーがあった場合false
        "message" => null, //表示するメッセージ
        "result"  => null, //更新結果（件数）
    ];
    
    // POSTデータ取得
    $site_name = filter_input(INPUT_POST,"site_name");
    try{
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $db = new PDO($dsn, "kp_user", "ecc");
        //接続の属性設定 ATTR_EMULATE_PREPARESはいつもfalseにしておく
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->beginTransaction();
        $sql = "DELETE FROM keep_url where SITE_NAME = :site_name";
        $stmt = $db->prepare($sql);
        $stmt->bindparam(":site_name",$site_name, PDO::PARAM_STR);

        $result["result"] = $stmt->execute();
        if($result["result"] !== 0){
            $db->commit();
            $result["message"] = "データ削除に成功しました！";
        }
    }catch(PDOException $poe){
        echo "db接続エラー". $poe->getMessage();
        $db->rollback();
    }finally{
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
            <h2 class="h2">DELETE RESULT</h2>
        </div>
    </header>

    <main>
        <div id="containter">

            <div class="col">
                <h3><p class="text-danger"><?= $result["message"];?></p></h3>
            </div>

           
            <div class="btn-wrap--perspective">
                <a class="btn btn-3d btn-3db" href="list.php">LISTへ戻る</a>
            </div>
            <br/>
            <div class="btn-wrap--perspective">
                <a class="btn btn-3d btn-3db" href="home.php">HOMEへ戻る</a>
            </div>
            
        
        </div>    
    </main>
</body>

</html>
