<?php
    require_once __DIR__ . "/def.php";
    require_once __DIR__ . "/utils.php";
    $favo_site_name = filter_input(INPUT_GET, "favo_site_name");
    $favo_url = filter_input(INPUT_GET, "favo_url");
    $category = filter_input(INPUT_GET, "category");

    $dsn = "mysql:host=localhost;dbname=keep;charset=utf8mb4";
    try{
        $db = new PDO($dsn, "kp_user", "ecc");
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $sql = "SELECT * FROM keep_url";
        $where = "";
        $nameLike = "";
        if($favo_site_name != ""){
            $nameLike = "%".$favo_site_name."%";
            $where = " WHERE favo_site_name like :favo_site_name ";
        }
        if($category != 1 && $category != ""){
            if($category == 2){
              $categorywhere = "favorite";
            }else{
              $categorywhere = "list";
            }
            if($name != ""){
              $where = $where."AND category = :category";
            }else{
              $where = "WHERE category = :category";
        }

        $stmt = $db->prepare($sql.$where);
        if($favo_site_name != ""){
            $stmt->bindParam(":favo_site_name",$nameLike,PDO::PARAM_STR);
        }

        if($category != ""){
            $stmt->bindParam(":category",$categorywhere,PDO::PARAM_STR);
        }

        $stmt->execute();

        $result = [];
        while($rows = $stmt->fetch(PDO::FETCH_ASSOC)){
            $result[] = $rows;
        }
        $stmt = null;
        $db = null;
    }catch(PDOException $poe){
        exit("DBエラー".$poe->getMessage());
    }
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>keepApp</title>
    <link href="./all.css" rel="stylesheet">
    <link href="./table.css" rel="stylesheet">
    <link href="./add.css" rel="stylesheet">
    <script src="./KeepApp.js"></script>
</head>

<body>

    <!--ヘッダー-->
    <header class="header">
        <div class="title-text">
            <h1 class="h1">keepApp</h1>
            <h2 class="h2">FAVORITE</h2>
        </div>
    </header>

    <main>
        <div id="containter">
            <div class="btn-wrap--perspective">
                <a class="btn btn-3d btn-3db" href="home.php">HOMEへ戻る</a>
            </div>
        </div>  
        <div class="container">
            <table class="main_table">
                <thead class="main_thead">
                    <tr>
                        <th>name</th>
                        <th>delete</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($result as $val):?>
                    <tr>
                        <td> 
                            <div class="t_button">
                                <a href="<?= $val["favo_url"] ?>"><span><?= $val["favo_site_name"] ?></span></a>
                            </div>
                        </td>

                        <td>
                                <div class="t_button">
                                    <a href="deletefavo.php?favo_site_name=<?= $val["favo_site_name"] ?>">delete</a>
                                </div>
                            </td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>

    </main>
</body>

</html>
