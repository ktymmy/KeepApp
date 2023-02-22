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
        if($category == 1){
            $where = "where category = 'list'";
        }else if($category == 2){
            $where = "where category = 'favorite'";
        }

        // if($category != 1 && $category != ""){
        //     if($category == 2){
        //       $categorywhere = "favorite";
        //     }else{
        //       $categorywhere = "list";
        //     }
        //     if($name != ""){
        //       $where = $where."AND category = :category";
        //     }else{
        //       $where = "WHERE category = :category";
        //     }
        // }

        if($site_name != ""){
            $where = $where. " AND site_name like '%site_name%' ";
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
            <form action="list.php" method="GET" class="mt-5 m-3">
                <h3><label  for="name">NAME</label></h3>
                <input class="category" type="text" name="name">
            

            
                <h3><label class="form-label" for="category">CATEGORY</label></h3>
                <select class="category" name="category">
                    <option value="1" selected>LIST</option>
                    <option value="2">FAVORITE</option>
                </select>
                
            
                <div class="pt-5 px-0 d-grid gap-2 d-md-flex justify-content-md-end">
                    <input class="R-button" type="submit" value="検索">
                </div><!-- .p-5 d-grid gap-2 d-md-flex justify-content-md-end -->
            </form>
        
        
           
       
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


            <div class="btn-wrap--perspective">
                <a class="btn btn-3d btn-3db" href="home.php">HOMEへ戻る</a>
            </div>
        </div>

    </main>
</body>

</html>