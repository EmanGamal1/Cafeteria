<?php

function connect_pdo(){

    $dsn = 'mysql:dbname=php-eman;host=nader-mo.tech;port=3306;';
    $user = 'php-eman';
    $password = 'Aa123456';
    $db= new PDO($dsn, $user, $password);

    return $db;
}
?>