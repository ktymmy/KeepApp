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
             
            <div id="containter">
                <h3>Name</h3>
                <input  class="textbox" type="text" name="site_name" placeholder="" required>
           
                <h3>URL</h3>
                <input class="textbox" type="text" name="url" placeholder="" required>
            
           
              <div class="p-5 d-grid gap-2 d-md-flex justify-content-md-start">
                 <button type="submit" class="R-button">登録</button>
              </div>
            
            </div>
        </form>

    </main>
</body>

</html>