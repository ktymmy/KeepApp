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
    $favo_site_name = filter_input(INPUT_POST,"favo_site_name");
    try{
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . "charset=utf8mb4";
        $db = new PDO($dsn, "kp_user", "ecc");
        //接続の属性設定 ATTR_EMULATE_PREPARESはいつもfalseにしておく
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->beginTransaction();
        $sql = "DELETE FROM keep_favo_url where FAVO_SITE_NAME = :favo_site_name";
        $stmt = $db->prepare($sql);
        $stmt->bindparam(":favo_site_name",$favo_site_name, PDO::PARAM_STR);

        $result["result"] = $stmt->execute();
        if($result["result"] !== 0){
            $db->commit();
            $result["message"] = "データ登録に成功しました！";
        }
    }catch(PDOException $poe){
        $db->rollback();
        echo "db接続エラー". $poe->getMessage();
    }finally{
        $stmt = null;
        $db = null;
    }

?>


