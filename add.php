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
            <h2 class="h2">ADD</h2>
        </div>
    </header>

    <main>
        <form action="addresult.php" method="POST" novalidate>
             
             <div class="col">
                <label class="form-label" for="name">Name<em class="text-danger">※必須</em></label>
                    <input type="text" name="site_name" id="site_name" class="form-control form-control-lg border-info" placeholder="" required>
              </div>
              
              <div class="col">
                <label class="form-label" for="name">Url<em class="text-danger">※必須</em></label>
                    <input type="text" name="url" id="url" class="form-control form-control-lg border-info" placeholder="" required>
              </div>   

              <div class="p-5 d-grid gap-2 d-md-flex justify-content-md-start">
              <button type="submit" class="btn btn-danger btn-lg">入力内容の確認</button>
        </form>
    </main>
</body>

</html>