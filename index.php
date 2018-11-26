<?php
require_once('functions.php');

// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

// массив проектов
$projects = ["Входящие", "Учеба", "Работа", "Домашние дела", "Авто"];

// массив задач
$tasks = [
    0 => [
        'task' => 'Собеседование в IT компании',
        'date' => '01.12.2018',
        'category' => 'Работа',
        'status' => false
    ],
    1 => [
        'task' => 'Выполнить тестовое задание',
        'date' => '25.11.2018',
        'category' => 'Работа',
        'status' => false
    ],
    2 => [
        'task' => 'Сделать задание первого раздела',
        'date' => '21.12.2018',
        'category' => 'Учеба',
        'status' => true
    ],
    3 => [
        'task' => 'Встреча с другом',
        'date' => '22.12.2018',
        'category' => 'Входящие',
        'status' => false
    ],
    4 => [
        'task' => 'Купить корм для кота',
        'date' => 'Нет',
        'category' => 'Домашние дела',
        'status' => false
    ],
    5 => [
        'task' => 'Заказать пиццу',
        'date' => 'Нет',
        'category' => 'Домашние дела',
        'status' => false
    ]
];

$page_content = include_template('index.php', [
    'tasks' => $tasks,
    'show_complete_tasks' => $show_complete_tasks
]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'tasks' => $tasks,
    'projects' => $projects,
    'title' => 'Дела в порядке',
    'user_name' => 'Константин'
]);

print($layout_content);
