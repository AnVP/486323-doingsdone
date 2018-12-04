<?php
require_once('init.php');

// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

// код SQL-запроса

$sql_projects = 'SELECT * FROM projects WHERE user_id = 1';
$result = mysqli_query($link, $sql_projects);

if ($result) {
    $projects = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

$sql_tasks = 'SELECT * FROM tasks WHERE user_id = 1';
if ($res = mysqli_query($link, $sql_tasks)) {
    $tasks = mysqli_fetch_all($res, MYSQLI_ASSOC);
}

$all_tasks = $tasks;

if (isset($_GET['project_id'])) {
    $project_id = $_GET['project_id'];
    $sql_projects_select = $sql_projects . ' AND project_id = $project_id';
    $sql_tasks_select = $sql_tasks . ' AND project_id = $project_id';

    $result_project = mysqli_query($link, $sql_projects_select);

    if ($result_project) {
        $result_tasks = mysql_query($link, $sql_tasks_select);
        $tasks = mysqli_fetch_all($result_tasks, MYSQLI_ASSOC);
    }
    else {
        http_response_code(404);
    }
}
else {
    $tasks = $all_tasks;
}

$page_content = include_template('index.php', [
    'tasks' => $tasks,
    'show_complete_tasks' => $show_complete_tasks
]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'tasks' => $tasks,
    'projects' => $projects,
    'title' => 'Дела в порядке',
    'user_name' => 'Константин'
]);

print($layout_content);
