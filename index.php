<?php include "requests.php";?>

<!DOCTYPE html>
<html lang = "ru">
<head>
    <title>URLShorten</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <style>
        a{
            text-decoration: none;
            color: black;
        }
    </style>
</head>
<body>
    <div class = "container text-center">
        <div class = "row" style = "margin-top: 20%;">
            <a href="http://localhost/URLShorten/">
                <h1>
                    URL SHORTEN
                </h1>
            </a>
        </div>
        <div class = "row">
            <form action = "" method="GET">
                <div class="input-group mb-3">
                    <input type="text" value="<?=$_GET['url_shorten'];?>" name="url_shorten" class="form-control" placeholder="Вставьте ссылку"  aria-describedby="button-addon2">
                    <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Сократить</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>