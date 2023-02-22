<?php
    require_once __DIR__ . "/def.php";
    require_once __DIR__ . "/utils.php";
    $site_name = filter_input(INPUT_GET, "site_name");
    $url = filter_input(INPUT_GET, "url");
    $category = filter_input(INPUT_GET, "category");


    $dsn = "mysql:host=localhost;dbname=keep;charset=utf8mb4";
    try{
        $db = new PDO($dsn, "kp_user", "ecc");
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $sql = "SELECT * FROM keep_url ";
        $where = "";
        $nameLike = "";
        $categorywhere = "";
        if($site_name != ""){
            $nameLike = "%".$site_name."%";
            $where = " WHERE site_name like :site_name ";
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
          }

        $stmt = $db->prepare($sql.$where);
        if($site_name != ""){
            $stmt->bindParam(":site_name",$nameLike,PDO::PARAM_STR);
        }

        if($url != ""){
            $stmt->bindParam(":url",$categorywhere,PDO::PARAM_STR);
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
            <h2 class="h2">LIST</h2>
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
                                    <a href="<?= $val["url"] ?>"><span><?= $val["site_name"] ?></span></a>
                                </div>
                            </td>

                            <td>
                                <div class="t_button">
                                    <a href="delete.php?site_name=<?= $val["site_name"] ?>">delete</a>
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