
<?php
    
    $conn = mysqli_connect('localhost', 'root', 'root', 'url-shorten', '3306');

    if (!$conn) die("Ошибка подключения к БД - " . mysqli_connect_error());

?>