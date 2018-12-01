<?php
require_once('functions.php');
$db = require_once('config/db.php');
var_dump($db);

// массив проектов
$projects = [];

// массив задач
$tasks = [];

// подключение к СУБД
$link = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
mysqli_set_charset($link, 'utf8');
