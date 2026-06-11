<?php
    $dbHost = 'localhost';
    $dbUsername = 'root';
    $dbPassword = 'root';
    $dbName = 'url-shorten';
    $dbPort = '3306';

    try {
        $dsn = "mysql:host=$dbHost;dbname=$dbName;charset=utf8";
        $pdo = new PDO($dsn, $dbUsername, $dbPassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die('Database Error');
    }
?>