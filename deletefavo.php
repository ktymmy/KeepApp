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
        $stmt->bindparam(':favosite_name', $favo_site_name, PDO::PARAM_STR);

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