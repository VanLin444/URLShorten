<?php
ob_start();
require_once __DIR__ . '/connection.php';
require_once __DIR__ . '/token.php';

$request = isset($_GET['url_shorten']) ? trim($_GET['url_shorten']) : '';

if (!empty($request)) {
    // Проверка есть ли уже такая ссылка
    $urlCheck = $pdo->prepare("SELECT * FROM links WHERE link = ?");
    $urlCheck->execute([$request]);
    $row = $urlCheck->fetch();

    if ($row) {
        // Если ссылка уже есть, просто берётся её токен, а не создаётся новый
        $_GET['url_shorten'] = $_SERVER['SERVER_NAME'] . '/' . $row['token'];
    } else {
        // Если ссылки нет, генерируется уникальный токен
        $token = '';
        $is_unique = false;
        while (!$is_unique) {
            $token = token_gen();
            $checkToken = $pdo->prepare("SELECT id FROM links WHERE token = ?");
            $checkToken->execute([$token]);
            if (!$checkToken->fetch()) {
                $is_unique = true;
            }
        }

        // Сохранение новой ссылки
        $stmt = $pdo->prepare("INSERT INTO links (link, token) VALUES (?, ?)");
        $add = $stmt->execute([$request, $token]);
        if ($add) {
            $_GET['url_shorten'] = $_SERVER['SERVER_NAME'] . '/' . $token;
        }
    }
} else {
    // Блок перенаправления по токену
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $token = trim($uri, '/');
    // Поиск ссылки по токену
    if (!empty($token)) {
        $stmt = $pdo->prepare("SELECT link FROM links WHERE token = ?");
        $stmt->execute([$token]);
        $row = $stmt->fetch();
        if ($row) {
            header("Location: " . $row['link']);
            exit; // Закрываем header
        }
    }
}

ob_end_flush();
?>