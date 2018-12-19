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
        $tasks = get_tasks($link, $user_id, (' AND project_id = ' . $project_id));
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
if (isset($_GET['show_completed']) && in_array(isset($_GET['show_completed']), $show_complete_values)) {
    $show_complete_tasks = intval($_GET['show_completed']);
    $_SESSION['show_completed'] = $show_complete_tasks;
}

if (isset($_SESSION['show_completed'])) {
    $show_complete_tasks = $_SESSION['show_completed'];
}
else {
$show_complete_tasks = 0;
}

// Фильтр по задачам
if (isset($_GET['project_id'])) {
    $project_id = $_GET['project_id'];
    $value = ' AND project_id = ' . $project_id;
}

if (isset($_GET['tasks-switch'])) {
    switch ($task_filter) {
        case $task_filter === 'today':
            $value = $value . ' AND deadline = CURDATE()';
            break;
        case $task_filter === 'tomorrow':
            $value .= $value . ' AND deadline = ADDDATE(CURDATE(),INTERVAL 1 DAY)';
            break;
        case $task_filter === 'expired':
            $value .= $value . ' AND deadline < CURDATE()';
            break;
    }

    $tasks = get_tasks($link, $user_id, $value);
}

if (isset($_GET['search'])) {
    $search = trim(mysqli_real_escape_string($link, $_GET['search']));

    if (!empty($search)) {
        $value_search = ' AND MATCH(name) AGAINST("' . $search . '")';
        $tasks = get_tasks($link, $user_id, $value_search);
    }
}

$page_content = include_template('index.php', [
    'tasks' => $tasks,
    'show_complete_tasks' => $show_complete_tasks,
    'task_filter' => $task_filter,
    'project_id' => $project_id,
    'search' => $search
]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'tasks_active' => $tasks_active,
    'projects' => $projects,
    'title' => 'Дела в порядке',
    'user' => $user
]);

print($layout_content);
