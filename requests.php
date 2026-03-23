<?php
ob_start();
include 'connection.php';

$request = isset($_GET['url_shorten']) ? trim($_GET['url_shorten']) : '';
$request = mysqli_real_escape_string($conn, $request);

if (!empty($request)) {
    // Проверка есть ли уже такая ссылка
    $urlCheck = mysqli_query($conn, "SELECT * FROM `links` WHERE `link` = '$request'");
    
    if (mysqli_num_rows($urlCheck) > 0) {
        // Если ссылка уже есть, просто берётся её токен, а не создаётся новый
        $row = mysqli_fetch_assoc($urlCheck);
        $_GET['url_shorten'] = $_SERVER['SERVER_NAME'] . '/' . $row['token'];
    } else {
        // Если ссылки нет, генерируется уникальный токен
        $token = '';
        $is_unique = false;
        while (!$is_unique) {
            $token = token_gen();
            $checkToken = mysqli_query($conn, "SELECT id FROM `links` WHERE `token` = '$token'");
            if (mysqli_num_rows($checkToken) == 0) {
                $is_unique = true;
            }
        }

        // Сохранение новой ссылки
        $add = mysqli_query($conn, "INSERT INTO `links` (`link`, `token`) VALUES ('$request', '$token')");
        if ($add) {
            $_GET['url_shorten'] = $_SERVER['SERVER_NAME'] . '/' . $token;
        }
    }
} else {
    // Блок перенаправления по токену
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $token = trim($uri, '/');

    if (!empty($token)) {
        $check = mysqli_query($conn, "SELECT `link` FROM `links` WHERE `token` = '$token'");
        if (mysqli_num_rows($check) > 0) {
            $row = mysqli_fetch_assoc($check);
            header("Location: " . $row['link']);
            exit; // Закрываем header
        }
    }
}

// Функция для генерации токена
function token_gen($min = 4, $max = 10) {
    $chars = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789';
    $token = '';
    $length = mt_rand($min, $max);
    for ($i = 0; $i < $length; $i++) {
        $token .= $chars[mt_rand(0, strlen($chars) - 1)];
    }
    return $token;
}

ob_end_flush();
?>