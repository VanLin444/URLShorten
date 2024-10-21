<?php
    
    $conn = mysqli_connect('localhost', 'root', 'root', 'url-shorten');

    if ($conn) die("Ошибка подключения к БД - " . mysqli_connect_error());

?>