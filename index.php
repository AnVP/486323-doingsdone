<?php
require_once('init.php');

if ($user){
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
        $row = mysqli_num_rows($result_project);

        if ($result_project and $result_tasks and $row !== 0) {
            $tasks = mysqli_fetch_all($result_tasks, MYSQLI_ASSOC);
        }
        else {
            http_response_code(404);
            exit();
        }
    }

    // Меняет статус задачи
    if (isset($_GET['task_id'])) {
        $task_id = intval($_GET['task_id']);

        $sql = 'UPDATE tasks SET status = NOT status WHERE task_id = ' . $task_id;

        $result = mysqli_query($link, $sql);
        if ($result) {
            header("Location: /");
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

        if ($task_filter === 'today') {
            $sql_tasks = $sql_tasks . ' AND deadline = CURDATE()';
            $res = mysqli_query($link, $sql_tasks);
        }
        if ($task_filter === 'tomorrow') {
            $sql_tasks = $sql_tasks . ' AND deadline = ADDDATE(CURDATE(),
             INTERVAL 1 DAY)';
             $res = mysqli_query($link, $sql_tasks);
        }
        if ($task_filter === 'expired') {
            $sql_tasks = $sql_tasks . ' AND deadline < CURDATE()';
            $res = mysqli_query($link, $sql_tasks);
        }
        if ($res) {
            $tasks = mysqli_fetch_all($res, MYSQLI_ASSOC);
        }
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
}
else {
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
}

print($layout_content);
