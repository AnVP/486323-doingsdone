<?php
require_once('functions.php');

$user_id = 1;
$link = connect_db($db);

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

// Валидация формы
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $task = $_POST;

    $required = ['name', 'project'];
    $dict = ['name' => 'Название', 'project' => 'Проект', 'date' => 'Дата выполнения', 'preview' => 'Файл'];
    $errors = [];

    // Обязательные поля
    foreach ($required as $key) {
        if (empty($_POST[$key])) {
            $errors[$key] = 'Это поле надо заполнить';
        }
    }

    $sql = 'INSERT INTO tasks (name, project_id, user_id)
    VALUES (
        ' . $task['name'] . ',
        ' . $task['project'] . ',
        ' . $user_id . '
    )';

    // Загрузка файла
    if (isset($_FILES['preview']['name'])) {
        $tmp_name = $_FILES['preview']['tmp_name'];
        $path = $_FILES['preview']['name'];

        move_uploaded_file($tmp_name, 'uploads/' . $path);
        $task['path'] = $path;

        $sql .= 'file = ' . $path;
    }

    if (isset($task['date'])) {
        $sql .= 'deadline = ' . $task['date'];
    }

    $result = mysqli_query($link, $sql);

    if ($result) {
        header("Location: /");
    }

    if (count($errors)) {
        $page_content = include_template('form-task.php', [
            'task' => $task,
            'errors' => $errors,
            'dict' => $dict
        ]);
    }
}

$page_content = include_template('form-task.php', [
    'projects' => $projects
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'tasks_active' => $tasks_active,
    'projects' => $projects,
    'title' => 'Дела в порядке',
    'user_name' => 'Константин'
]);

print($layout_content);

