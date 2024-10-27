<?php
    // Включаем буферизацию вывода, чтобы вывод из скрипта не отправлялся, а сохранялся во внутреннем буфере. 
    // Это нужно чтобы Local - корректно работал и перенаправлял на нужную страницу.
    ob_start(); 
    include 'connection.php'; // Подключаемся к БД
    
    $request = trim($_GET['url_shorten']); // Удаляем пробелы с начала и конца строки
    $request = mysqli_real_escape_string($conn, $request); // Проверка на инъекцию
    // Ищем ссылку в БД
    $urlCheck = mysqli_query($conn, "SELECT * FROM `links` WHERE `link` = '".$request."'"); 
    // Проверяем сыществование get-запроса и указанной ссылки в БД
    if(isset($_GET['url_shorten'])){
        $search_bool = false;
        $token = '';
        // Проверяем есть ли такой токен в БД.
        // Если да, то генерируем новый пока он не станет уникальным.
        while (!$search_bool){
            $token = token_gen();
            $sel = mysqli_query($conn, "SELECT * FROM `links` WHERE `token` = '".$token."'"); 
            if(!mysqli_num_rows($sel)){
                $search_bool = true;
            }
        }
        
        if($search_bool){
            // Если нет такого токена, то добавляем в БД
            $add = mysqli_query($conn, "INSERT INTO `links` (`link`,`token`) VALUES ('".$request."', '".$token."')"); // Добавление ссылки и токена в БД
            if ($add){
                $_GET['url_shorten'] = $_SERVER['SERVER_NAME'] . '/' . $token; // Запись в get-запрос новой ссылки, которая выведется в поле ввода
            }
        }

    } else {
        // Хватаем введённый адрес пользователя с токеном и перенаправляем на адресс указанный в БД соответствующий токену
        // Получаем значение из адрессной строки
        $uri = $_SERVER['REQUEST_URI'];
        $token = substr($uri, 1);
        // Проверяем есть ли такой токен и перенаправляем на его адрес
        if(iconv_strlen($token)){
            $check = mysqli_query($conn, "SELECT * FROM `links` WHERE `token` ='".$token."'");
            print_r($check);
            print_r(mysqli_num_rows($check));
            if(mysqli_num_rows($check) || mysqli_num_rows($check) == 0){
                $row = mysqli_fetch_assoc($check);
                header("Location: " . $row['link']); // Используем для сохранности фун-ю ob_start(), чтобы header работал корректно!
            } else {
                die("Ошибка токена");
            }
        }
    }

    // Функция для генерации уникального токена
    function token_gen($min = 4, $max = 10 ){
        $chars = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789';
        $new_chars = str_split($chars);

        $token = '';
        $rand_end = mt_rand($min,$max);

        for($i = 0; $i < $rand_end; $i++){
            $token .= $new_chars[mt_rand(0, sizeof($new_chars)-1)];
        }

        return $token;
    }
?>