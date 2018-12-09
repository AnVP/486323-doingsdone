<?php
require_once('init.php');
require_once('functions.php');

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

$task = [];
$errors = [];

// Валидация формы
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Экранируем спецсимволы
    if (!empty($_POST)) {
        $task = $_POST;
        foreach ($task as $key => $value) {
            $task[$key] = mysqli_real_escape_string($link, $task[$key]);
            // Удаляет пробелы из начала и конца строки
            $task[$key] = trim($task[$key]);
        }
    }

    $required = ['name', 'project'];

    // Обязательные поля
    foreach ($required as $key) {
        if (empty($_POST[$key])) {
            $errors[$key] = 'Это поле надо заполнить';
        }
    }

    // Проверка полей
    if (empty($errors['name']) and strlen($task['name']) > 128) {
        $errors['name'] = 'Название не может быть длиннее 128 символов';
    }

    if (empty($task['date'])) {
        $task['date'] = NULL;
    }
    elseif(empty($errors['date']) and $task['date'] != NULL and strtotime($task['date']) < time()) {
            $errors['date'] = 'Дата не может быть раньше текущей';
    }

    // Загрузка файла
    if (is_uploaded_file($_FILES['preview']['name'])) {
        $tmp_name = $_FILES['preview']['tmp_name'];
        $path = uniqid();
        move_uploaded_file($tmp_name, 'uploads/' . $path);
        $file = $path;
    }
    else {
        $file = "";
    }

    if (empty($errors)) {

        $sql = 'INSERT INTO tasks (name, project_id, user_id, file, deadline)
        VALUES (
            ' . $task['name'] . ',
            ' . $task['project'] . ',
            ' . $user_id . ',
            ' . $file . ',
            ' . $task['date'] .'
        )';

        $result_task = mysqli_query($link, $sql);

        if ($result_task) {
            header("Location: /");
        }
    }
}

$page_content = include_template('form-task.php', [
    'task' => $task,
    'errors' => $errors,
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

