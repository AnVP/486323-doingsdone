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

session_start();

$data = [];
$errors = [];

if (!empty($_POST)) {
    foreach ($_POST as $key => $value) {
        $data[$key] = mysqli_real_escape_string($link, $_POST[$key]);
    }
    $required = ['email', 'password'];
    // Обязательные поля
    foreach ($required as $key) {
        // Удаляет пробелы из начала и конца строки
        if (!empty($data[$key])) {
            $data[$key] = trim($data[$key]);
        }

        if (empty($data[$key])) {
            $errors[$key] = 'Это поле надо заполнить';
        }
    }

    // Проверка полей
    if (!empty($data['email'])) {
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'E-mail введён некорректно';
        }
        else {
            $email = mysqli_real_escape_string($link, $data['email']);
            $sql = 'SELECT * FROM users WHERE email = "' . $email . '"';
            $res = mysqli_query($link, $sql);

            $user = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;

            if (empty($errors) and $user) {
                if (password_verify($data['password'], $user['password'])) {
                    $_SESSION['user'] = $user;
                    header("Location: /");
                }
                else {
                    $errors['password'] = 'Неверный пароль';
                }
            }
            else {
                $errors['email'] = 'Такой пользователь не найден';
            }
        }
    }
}

$page_content = include_template('auth.php', [
    'data' => $data,
    'errors' => $errors
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => 'Вход на сайт',
    'tasks_active' => $tasks_active,
    'projects' => $projects,
]);

print($layout_content);
