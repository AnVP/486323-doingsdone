<?php
require_once('init.php');

if ($user){
    header("Location: /");
    exit();
}

$data = [];
$errors = [];

if (!empty($_POST)) {
    $required = ['email', 'password', 'name'];
    // Обязательные поля
    foreach ($required as $key) {
        if (!empty($_POST[$key])){
            // Экранируем спецсимволы
            $data[$key] = mysqli_real_escape_string($link, $_POST[$key]);
        }
        // Удаляет пробелы из начала и конца строки
        if (!empty($data[$key])) {
            $data[$key] = trim($data[$key]);
        }

        if (empty($data[$key])) {
            $errors[$key] = 'Это поле надо заполнить';
        }
    }

    // Проверка полей
    if (empty($errors['name']) and strlen($data['name']) > 128) {
        $errors['name'] = 'Имя не может быть длиннее 128 символов';
    }

    if (empty($errors['email']) and (!filter_var($data['email'], FILTER_VALIDATE_EMAIL) or strlen($data['email']) > 128)) {
        $errors['email'] = 'E-mail введён некорректно';
    }
    if (empty($errors['email'])) {
        $sql = 'SELECT user_id FROM users WHERE email = "' . $data['email'] . '"';
        $res = mysqli_query($link, $sql);
        if (mysqli_num_rows($res) > 0) {
            $errors['email'] = 'Пользователь с этим email уже зарегистрирован';
        }
    }

    // Пароль
    if (!empty($data['password'])) {
        if (empty($errors['password']) and strlen($data['password']) > 128) {
            $errors['password'] = 'Пароль не может быть длиннее 64 символов';
        }
        $password = password_hash($data['password'], PASSWORD_DEFAULT);
    }

    if (empty($errors)) {

        $sql_user = 'INSERT INTO users (date_registration, email, name, password) VALUES (NOW(), "' . $data['email'] . '", "' . $data['name'] . '", "' . $password . '")';

        $result_user = mysqli_query($link, $sql_user);

        if ($result_user) {
            header("Location: /");
            exit();
        }
    }
}

$page_content = include_template('reg.php', [
    'data' => $data,
    'errors' => $errors
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'tasks_active' => '',
    'projects' => '',
    'title' => 'Регистрация аккаунта'
]);

print($layout_content);
