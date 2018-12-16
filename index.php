<?php
require_once('init.php');

if (!$user){
    $page_content = include_template('guest.php', [
        ]);
    $layout_content = include_template('layout.php', [
        'content' => $page_content,
        'tasks_active' => '',
        'projects' => '',
        'title' => 'Дела в порядке',
        'guest' => true,
        'user' => ''
    ]);
    print($layout_content);
    exit();
}

if (isset($_GET['project_id'])) {
    $project_id = intval($_GET['project_id']);
    if ($tasks) {
        $tasks = get_tasks_project($link, $project_id, $user_id);
    }
    else {
        http_response_code(404);
        exit();
    }
}

// Меняет статус задачи
if (isset($_GET['task_id'])) {
    $task_id = intval($_GET['task_id']);

    $sql = 'UPDATE tasks SET status = NOT status WHERE task_id = ' . $task_id . ' AND user_id = ' . $user_id;

    $result = mysqli_query($link, $sql);
    if ($result) {
        header("Location: /");
        exit();
    }
}

// Показывать выполненные задачи
if (isset($_GET['show_completed'])) {
    $show_complete_tasks = intval($_GET['show_completed']);
    if (isset($user['show_completed'])) {
        $show_complete_tasks = intval($user['show_completed']);
    }
}

// Фильтр по задачам
if (isset($_GET['tasks-switch'])) {
    $task_filter =  $_GET['tasks-switch'];

    switch ($task_filter) {
        case $task_filter === 'today':
            $data = ' AND deadline = CURDATE()';
            break;
        case $task_filter === 'tomorrow':
            $data = ' AND deadline = ADDDATE(CURDATE(),INTERVAL 1 DAY)';
            break;
        case $task_filter === 'expired':
            $data = ' AND deadline < CURDATE()';
            break;
    }

    $tasks = filter_tasks($link, $data, $user_id);
}

$page_content = include_template('index.php', [
    'tasks' => $tasks,
    'show_complete_tasks' => $show_complete_tasks,
    'task_filter' => $task_filter
]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'tasks_active' => $tasks_active,
    'projects' => $projects,
    'title' => 'Дела в порядке',
    'user' => $user
]);

print($layout_content);
