<?php require_once __DIR__ . '/../includes/requests.php'?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <title>URLShorten</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/styles.css">
</head>

<body>
    <div class="container text-center">
        <div class="title">
            <a href="<?= 'http://' . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"] ?>">
                <h1>
                    URL SHORTEN
                </h1>
            </a>
        </div>
        <div class="row">
            <form action="" method="GET">
                <div class="input-group mb-3">
                    <input type="text" value="<?= $_GET['url_shorten']; ?>" name="url_shorten" class="form-control" placeholder="Вставьте ссылку..." aria-describedby="button-addon2">
                    <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Сократить</button>
                </div>
            </form>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>