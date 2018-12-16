<?php
require_once('init.php');

if ($user){
    $project = [];
    $errors = [];

    // Валидация формы

    if (!empty($_POST)) {
        $required = ['name'];

        // Обязательные поля
        foreach ($required as $key) {
            if (!empty($_POST[$key])){
                // Экранируем спецсимволы
                $project[$key] = mysqli_real_escape_string($link, $_POST[$key]);
            }
            // Удаляет пробелы из начала и конца строки
            if (!empty($project[$key])) {
            $project[$key] = trim($project[$key]);
            }
            if (empty($project[$key])) {
                $errors[$key] = 'Это поле надо заполнить';
            }
            if (empty($errors[$key]) and strlen($project[$key]) > 128) {
                $errors[$key] = 'Название не может быть длиннее 128 символов';
            }
        }

        if (empty($errors)) {
            $sql_project = 'SELECT * FROM projects WHERE user_id = "' . $user_id . '" and name = "' . $project['name'] . '"';
            $res = mysqli_query($link, $sql_project);
            if (mysqli_num_rows($res) > 0) {
                $errors['name'] = 'Такой проект уже существует';
            }
        }

        if (empty($errors)) {
            $sql = 'INSERT INTO projects (name, user_id)
            VALUES ("' . $project['name'] .'", ' . $user_id . ')';

            $result_project = mysqli_query($link, $sql);

            if ($result_project) {
                header("Location: /");
            }
        }
    }

    $page_content = include_template('form-project.php', [
        'project' => $project,
        'errors' => $errors,
        'projects' => $projects
    ]);

    $layout_content = include_template('layout.php', [
        'content' => $page_content,
        'tasks_active' => $tasks_active,
        'projects' => $projects,
        'title' => 'Добавление проекта',
        'user' => $user
    ]);

    print($layout_content);
}
