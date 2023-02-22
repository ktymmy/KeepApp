<?php
    require_once __DIR__ . "/def.php";
    require_once __DIR__ . "/utils.php";

    // リクエスト形式がPOSTでなければhome.htmlに遷移
    if($_SERVER["REQUEST_METHOD"] !== "POST"){
        header("Location: home.php");
        exit;
    }

    // フォームデータ取得
    $favo_site_name = filter_input(INPUT_POST, "favo_site_name");
    $favo_url = filter_input(INPUT_POST, "favo_url");

    //結果保存用配列
    $result = [
        "status"  => true, //エラーがあった場合false
        "message" => null, //表示するメッセージ
        "result"  => null, //更新結果（件数）
    ];
    try{
       $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
       $db = new PDO($dsn, "kp_user", "ecc");
       //接続の属性設定 ATTR_EMULATE_PREPARESはいつもfalseにしておく
       $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
       $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
       $db->beginTransaction();

       // スペースを空文字に置き換える
       $favo_site_name = str_replace(array(" ","　"),"",$favo_site_name);
       if(!$favo_site_name){
        $result['status'] = false;
        $result['massage'] = "名前を入力してください。";
       }

       //URL空文字チェック
       if(!$favo_url){
        $result['status'] = false;
        $result['massage'] = "URLを入力してください。";
       }

       $sql_cnt = "SELECT COUNT(favo_site_name) FROM keep_favo_url WHERE favo_site_name = :favo_site_name";
       $stmt = $db->prepare($sql_cnt);
       $stmt->bindParam('favo_site_name', $favo_site_name);
       $stmt->execute();
       if ($stmt->fetch()[0] == 1) {
        $result['status'] = false;
        $result['message'] = "既に登録されています";
       }

       // いったん切断
       $stmt = null;
       $db = null;
       //DB接続
       $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
       $db = new PDO($dsn, "kp_user", "ecc");
       //接続の属性設定 ATTR_EMULATE_PREPARESはいつもfalseにしておく
       $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
       $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

       if($result['status']){
        $sql = "INSERT INTO keep_favo_url VALUES(:favo_site_name, :favo_url)";
        //SQLの準備
        $stmt = $db->prepare($sql);
        //データのバインド
        $stmt->bindParam('favo_site_name', $favo_site_name, PDO::PARAM_STR);
        $stmt->bindParam('favo_url', $favo_url, PDO::PARAM_STR);
        // SQL実行(戻り値は変更した件数)
        $result["result"] = $stmt->execute();
        //結果が1(1件挿入できた)ときはコミットする
        if ($result["result"] !== 0) {
         $db->commit();
         $result["message"] = "データ登録に成功しました！";
        }
    }
    }catch(PDOException $poe){
        $db->rollBack(); // ⑥ロールバック
        echo "DB接続エラー" . $poe->getMessage();
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
    <link href="./add.css" rel="stylesheet">
    <script src="./KeepApp.js"></script>
</head>

<body>

    <!--ヘッダー-->
    <header class="header">
        <div class="title-text">
            <h1 class="h1">keepApp</h1>
            <h2 class="h2">ADD FAVORITE RESULT</h2>
        </div>
    </header>

    <main>
        <!-- 結果表示     -->
        <div class="text-result">
                <p class="text-danger"><?= $result["message"] ?></p>
            </div>
            </div>
    </main>
</body>

</html>

