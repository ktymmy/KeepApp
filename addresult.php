<?php
    require_once __DIR__ . "/def.php";
    require_once __DIR__ . "/utils.php";

    // リクエスト形式がPOSTでなければhome.htmlに遷移
    if($_SERVER["REQUEST_METHOD"] !== "POST"){
        header("Location: home.html");
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
       
    }catch{

    }finally{
        
    }


?>