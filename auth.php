<?php
require_once('init.php');

if ($user){
    header("Location: /");
}
$data = [];
$errors = [];

if (!empty($_POST)) {
    foreach ($_POST as $key => $value) {
        // Экранируем спецсимволы
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
    if (empty($errors['email']) and (!filter_var($data['email'], FILTER_VALIDATE_EMAIL) or strlen($data['email']) > 128)) {
        $errors['email'] = 'E-mail введён некорректно';
    }
    if (empty($errors)) {
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
$page_content = include_template('auth.php', [
    'data' => $data,
    'errors' => $errors
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => 'Вход на сайт',
    'tasks_active' => '',
    'projects' => '',
]);

print($layout_content);
