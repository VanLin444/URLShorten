<?php
// Функция для генерации токена
function token_gen($min = 6, $max = 10) {
    $chars = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789';
    $token = '';
    $length = mt_rand($min, $max);
    for ($i = 0; $i < $length; $i++) {
        $token .= $chars[mt_rand(0, strlen($chars) - 1)];
    }
    return $token;
}