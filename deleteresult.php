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
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . "charset=utf8mb4";
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
        $db->rollback();
        echo "db接続エラー". $poe->getMessage();
    }finally{
        $stmt = null;
        $db = null;
    }

?>
