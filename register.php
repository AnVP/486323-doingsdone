<?php
require_once('init.php');

$data = [];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Экранируем спецсимволы
    if (!empty($_POST)) {
        $data = $_POST;
        foreach ($data as $key => $value) {
            $value = mysqli_real_escape_string($link, $value);
            // Удаляет пробелы из начала и конца строки
            $data[$key] = trim($value);
        }
    }

    $required = ['email', 'password', 'name'];

    // Обязательные поля
    foreach ($required as $key) {
        if (empty($_POST[$key])) {
            $errors[$key] = 'Это поле надо заполнить';
        }
    }

    // Проверка полей
    if (empty($errors['name']) and strlen($data['name']) > 128) {
        $errors['name'] = 'Имя не может быть длиннее 128 символов';
    }

    if (empty($errors['email']) and strlen($data['email']) > 128) {
        $errors['email'] = 'E-mail не может быть длиннее 128 символов';
    }

    if (empty($errors['password']) and strlen($data['password']) > 128) {
        $errors['password'] = 'Пароль не может быть длиннее 64 символов';
    }

    // Проверка email
    if (!empty($data['email'])) {
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'E-mail введён некорректно';
        }
        $sql = 'SELECT user_id FROM users WHERE email = "' . $data['email'] . '"';
        $res = mysqli_query($link, $sql);
        if (mysqli_num_rows($res) > 0) {
            $errors['email'] = 'Пользователь с этим email уже зарегистрирован';
        }
    }

    // Пароль
    if (!empty($data['password'])) {
        $password = password_hash($data['password'], PASSWORD_DEFAULT);
    }

    if (empty($errors)) {

        $sql_user = 'INSERT INTO users (date_registration, email, name, password) VALUES (NOW(), "' . $data['email'] . '", "' . $data['name'] . '", "' . $password . '")';

        $result_user = mysqli_query($link, $sql_user);

        if ($result_user) {
            header("Location: /");
        }
    }
}

$layout_content = include_template('reg.php', [
    'data' => $data,
    'errors' => $errors,
    'title' => 'Дела в порядке',
]);

print($layout_content);
