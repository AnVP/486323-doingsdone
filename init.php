<?php
require_once('config/db.php');
require_once('functions.php');

session_start();

$user = !empty($_SESSION['user']) ?  $_SESSION['user'] : [];
$user_id = !empty($user['user_id']);
$link = connect_db($db);
