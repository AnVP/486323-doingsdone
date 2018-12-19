<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once('vendor/autoload.php');
require_once('config/db.php');
require_once('functions.php');

session_start();

$show_complete_values = [0, 1];
$value = '';
$user = !empty($_SESSION['user']) ?  $_SESSION['user'] : [];
$user_id = !empty($user['user_id']) ? $user['user_id'] : '';
$link = connect_db($db);

$projects = get_projects($link, $user_id);
$tasks = get_tasks($link, $user_id, $value);
$tasks_active = get_tasks($link, $user_id, ' AND status = 0');
$task_filter =  $_GET['tasks-switch'] ?? '';
$project_id = $_GET['project_id'] ?? '';
$search = '';

