<?php
require_once('functions.php');
require_once('config/db.php');

// подключение к СУБД
function connect_db($db) {
    $link = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
    mysqli_set_charset($link, 'utf8');

    if (!$link) {
        exit('Сайт временно не доступен');
    }

    return $link;
}

$link = connect_db($db);
