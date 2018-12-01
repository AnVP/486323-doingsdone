<?php
require_once('init.php');

// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

// код SQL-запроса
if (!$link) {
    $error = mysqli_connect_error();
    print('Ошибка ' . $error);
}
else {
    $sql = 'SELECT * FROM projects WHERE user_id = 1';
    $result = mysqli_query($link, $sql);

    if ($result) {
        $projects = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    else {
        $error = mysqli_error($link);
        print('Ошибка ' . $error);
    }

    $sql = 'SELECT * FROM tasks WHERE user_id = 1';
    if ($res = mysqli_query($link, $sql)) {
        $tasks = mysqli_fetch_all($res, MYSQLI_ASSOC);
    }
    else {
        $error = mysqli_error($link);
        print('Ошибка ' . $error);
    }
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
