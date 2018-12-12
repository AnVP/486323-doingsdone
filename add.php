<?php
require_once('init.php');

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
            $value = mysqli_real_escape_string($link, $value);
            // Удаляет пробелы из начала и конца строки
            $task[$key] = trim($value);
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
        $deadline = 'null';
    }
    elseif (empty($errors['date']) and strtotime($task['date']) < time()) {
        $errors['date'] = 'Дата не может быть раньше текущей';
    }
    else {
        $deadline = '"' . $task['date'] . '"';
    }

    // Загрузка файла
    if (is_uploaded_file($_FILES['preview']['tmp_name'])) {
        $tmp_name = $_FILES['preview']['tmp_name'];
        $path = uniqid();
        move_uploaded_file($tmp_name, 'uploads/' . $path);
        $file = '"' . $path .'"';
    }
    else {
        $file = 'null';
    }

    if (empty($errors)) {
        $task_name = $task['name'];
        $project_name = $task['project'];

        $sql = 'INSERT INTO tasks (creation_date, execution_date, status, name, file, deadline, user_id, project_id)
        VALUES (NOW(), NULL, 0, "' . $task_name .'", ' . $file . ', ' . $deadline . ', ' . $user_id . ', ' . $project_name . ')';

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
