<?php
require_once('init.php');

if ($user){

    $task = [];
    $errors = [];

    // Валидация формы
    if (!empty($_POST)) {
        $task = $_POST;
        // Экранируем спецсимволы
        foreach ($task as $key => $value) {
            $value = mysqli_real_escape_string($link, $value);
            // Удаляет пробелы из начала и конца строки
            $task[$key] = trim($value);
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
        elseif (empty($errors['date']) and strtotime($task['date']) < strtotime(date('Y-m-d'))) {
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
                exit();
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
        'title' => 'Добавление задачи',
        'user' => $user
    ]);

    print($layout_content);
}
