<?php
ob_start();
require_once __DIR__ . "/connection.php";
require_once __DIR__ . '/token.php';

$error = null;
$request = isset($_GET['url_shorten']) ? trim($_GET['url_shorten']) : '';

if (!empty($request)) {
    if (!validateUrl($request)) {
        $error = 'Введите корректный URL!';
    } else {
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
                $_GET['url_shorten'] = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . '/' . $token;
            }
        }
    }
} else {
    // Блок перенаправления по токену
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $segments = explode('/', trim($uri, '/'));
    $token = end($segments);
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

// Проверка URL
function validateUrl(string $url): bool
{
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return false;
    }
    $scheme = parse_url($url, PHP_URL_SCHEME);
    return in_array($scheme, ['http', 'https']);
}

ob_end_flush();
