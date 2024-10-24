<?php
    include 'connection.php';

    $request = trim($_POST['url_shorten']);
    $request = mysqli_real_escape_string($conn, $request);

    if(!empty($request)){

        $search_bool = false;
        $token = '';

        while (!$search_bool){
            $token = token_gen();
            $sel = mysqli_query($conn, "SELECT * FROM `links` WHERE `token` ='".$token."'");
            
            if(!mysqli_num_rows($sel)){
                $search_bool = true;
            }
    
        }

        
        if($search_bool){
            $ins = mysqli_query($conn, "INSERT INTO `links` (`link`,`token`) VALUES ('".$request."', '".token_gen()."')");
    
            if ($ins){
                echo "Добавлено";
            } else {
                echo "Ошибка";
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