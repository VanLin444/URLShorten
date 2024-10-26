<?php
    ob_start();
    include 'connection.php';
    
    $request = trim($_GET['url_shorten']); // Удаляем пробелы с начала и конца строки
    $request = mysqli_real_escape_string($conn, $request); // Проверка на инъекцию
    if(isset($_GET['url_shorten'])){
        $search_bool = false;
        $token = '';

        while (!$search_bool){
            $token = token_gen();
            $sel = mysqli_query($conn, "SELECT * FROM `links` WHERE `token` = '".$token."'"); // Проверка есть ли такой токен в БД
            
            if(!mysqli_num_rows($sel)){
                $search_bool = true;
                break;
            }
    
        }

        
        if($search_bool){
            $ins = mysqli_query($conn, "INSERT INTO `links` (`link`,`token`) VALUES ('".$request."', '".$token."')"); // Добавление ссылки в БД
    
            if ($ins){
                $_GET['url_shorten'] = $_SERVER['SERVER_NAME'] . '/' . $token;
            } else {
                //echo "Ошибка";
            }
        } else {
            //echo "Не добавлена";
        }
    } else {
        $uri = $_SERVER['REQUEST_URI'];
        $token = substr($uri, 1);

        if(iconv_strlen($token)){
            $check = mysqli_query($conn, "SELECT * FROM `links` WHERE `token` ='".$token."'");
            if(!mysqli_num_rows($check)){
                $row = mysqli_fetch_assoc($check);
                header("Location: " . $row['link']);
            } else {
                die("Ошибка токена");
            }
        }
    }

    

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