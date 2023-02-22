<?php
    require_once __DIR__ . "/def.php";
    require_once __DIR__ . "/utils.php";



    // リクエスト形式がPOSTでなければhome.htmlに遷移
    if($_SERVER["REQUEST_METHOD"] !== "POST"){
        header("Location: home.php");
        exit;
    }

    // フォームデータ取得
    $site_name = filter_input(INPUT_POST, "site_name");
    $url = filter_input(INPUT_POST, "url");

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
       $site_name = str_replace(array(" ","　"),"",$site_name);
       if(!$site_name){
        $result['status'] = false;
        $result['massage'] = "名前を入力してください。";
       }

       //URL空文字チェック
       if(!$url){
        $result['status'] = false;
        $result['massage'] = "URLを入力してください。";
       }

       $sql_cnt = "SELECT COUNT(site_name) FROM keep_url WHERE site_name = :site_name";
       $stmt = $db->prepare($sql_cnt);
       $stmt->bindParam('site_name', $site_name);
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
            $sql = "INSERT INTO keep_url VALUES(:site_name, :url)";
            //SQLの準備
            $stmt = $db->prepare($sql);
            //データのバインド
            $stmt->bindParam('site_name', $site_name, PDO::PARAM_STR);
            $stmt->bindParam('url', $url, PDO::PARAM_STR);
            // SQL実行(戻り値は変更した件数)
            $result["result"] = $stmt->execute();
            //結果が1(1件挿入できた)ときはコミットする
            if ($result["result"] !== 0) {
                $db->commit();
                $result["message"] = "データ登録に成功しました！";
            }
        }
    } catch (PDOException $poe) {
        echo "DB接続エラー" . $poe->getMessage();
        $db->rollBack(); // ⑥ロールバック
    
    } finally {
        $stmt = null;
        $db = null;
    }
?>


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
            <h2 class="h2">ADDRESULT</h2>
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
