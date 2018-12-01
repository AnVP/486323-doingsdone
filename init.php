<?php
require_once('functions.php');
require_once('config/db.php');

// массив проектов
$projects = [];

// массив задач
$tasks = [];

// подключение к СУБД
$link = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
mysqli_set_charset($link, 'utf8');

if (!$link) {
    exit('Сайт временно не доступен');
}
