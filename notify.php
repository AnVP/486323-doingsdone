<?php
require_once('init.php');

$transport = new Swift_SmtpTransport('phpdemo.ru', 25);
$transport -> setUsername('keks@phpdemo.ru');
$transport -> setPassword('htmlacademy');

$mailer = new Swift_Mailer($transport);

$logger = new Swift_Plugins_Loggers_ArrayLogger();
$mailer -> registerPlugin(new Swift_Plugins_LoggerPlugin($logger));

$sql = 'SELECT users.*, tasks.name as task_name, tasks.deadline FROM tasks JOIN users ON tasks.user_id = users.user_id WHERE deadline <= ADDDATE(CURRENT_TIMESTAMP, INTERVAL 1 HOUR) AND deadline >= CURRENT_TIMESTAMP AND status = 0';

$res = mysqli_query($link, $sql);

if ($res and mysqli_num_rows($res)) {
    $tasks = mysqli_fetch_all($res, MYSQLI_ASSOC);

    foreach($tasks as $item) {
        $users[$item['user_id']][] = [
            'email' => $item['email'],
            'user_name' => $item['name'],
            'task_name' => $item['task_name'],
            'deadline' => $item['deadline']
        ];
    }

    foreach($users as $key => $value) {
        $user_tasks = $value;
        $user_email = $value[0]['email'];
        $user_name = $value[0]['user_name'];

        $message -> setSubject('Уведомление от сервиса «Дела в порядке»');
        $message -> setFrom (['keks@phpdemo.ru' => 'Doingsdone']);
        $message -> setTo([$user_email => $user_name]);

        $msg_content = include_template('message.php', [
            'user_name' => $user_name,
            'user_tasks' => $user_tasks
        ]);
        $message -> setBody($msg_content, 'text/html');
        $result = $mailer -> send($message);
    }

    if ($result) {
        print('Рассылка успешно отправлена');
    }
    else {
        print('Не удалось отправить рассылку: ' . $logger -> dump());
    }
}
