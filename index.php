<?php
require_once('init.php');
require_once('functions.php');

// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

// код SQL-запроса

$sql_projects = 'SELECT * FROM projects WHERE user_id = ' . $user_id;
$result = mysqli_query($link, $sql_projects);

if ($result) {
    $projects = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

$sql_tasks = 'SELECT * FROM tasks WHERE user_id = ' . $user_id;
if ($res = mysqli_query($link, $sql_tasks)) {
    $tasks = mysqli_fetch_all($res, MYSQLI_ASSOC);
}

$sql_tasks_active = $sql_tasks . ' AND status = 0';
if ($res_active = mysqli_query($link, $sql_tasks_active)) {
    $tasks_active = mysqli_fetch_all($res_active, MYSQLI_ASSOC);
}

if (isset($_GET['project_id'])) {
    $project_id = intval($_GET['project_id']);
    $sql_projects_select = $sql_projects . ' AND project_id = ' . $project_id;
    $sql_tasks_select = $sql_tasks . ' AND project_id = ' . $project_id;

    $result_project = mysqli_query($link, $sql_projects_select);

    $result_tasks = mysqli_query($link, $sql_tasks_select);
    $row = mysqli_num_rows($result_tasks);

    if ($result_project and $row !== 0) {
        $tasks = mysqli_fetch_all($result_tasks, MYSQLI_ASSOC);
    }
    else {
        http_response_code(404);
        exit();
    }
}

$page_content = include_template('index.php', [
    'tasks' => $tasks,
    'show_complete_tasks' => $show_complete_tasks
]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'tasks_active' => $tasks_active,
    'projects' => $projects,
    'title' => 'Дела в порядке',
    'user_name' => 'Константин'
]);

print($layout_content);
