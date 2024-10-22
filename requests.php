<?php
    include 'connection.php';

    $request = $_POST['url_shorten'];
    echo $request;
    if (!empty($request)){
        $check = mysqli_query($conn, "SELECT * FROM `links` WHERE link='".$request."'");
        print_r($check);

        if (!mysqli_num_rows($check)){
            echo "Good";
        } else {
            echo "Bad";
        }
    }
?>