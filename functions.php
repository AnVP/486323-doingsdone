<?php
/**
 * Подключает шаблон
 * @param $name string Имя шаблона
 * @param $data array Массив данных
 *
 * @return $result string Шаблон
 */
function include_template($name, $data) {
    $name = 'templates/' . $name;
    $result = '';

    if (!file_exists($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

/**
 * Считает количество задач в проекте
 * @param $tasks_list array Список задач
 * @param $name_project integer id проекта
 *
 * @return $count_name_project integer Количество задач
 */
function count_tasks($tasks_list, $name_project) {
    $count_name_project = 0;

    foreach ($tasks_list as $key => $item) {
        if ($item['project_id'] === $name_project) {
            $count_name_project++;
        }
    }
    return $count_name_project;
}

/**
 * Преобразует специальные символы HTML
 * @param $str string Данные, введенные пользователем
 *
 * @return $text string Преобразованные данные
 */
function esc($str) {
    $text = htmlspecialchars($str);

    return $text;
}

/**
 * Определяет дела с датой выполнения меньше 24 часов
 * @param $task array Задача
 *
 * @return bool Истекает время выполнения да/нет
 */
function check_important_task($task) {
    $important_hours = 24;
    $important_date = $task['deadline'];
    $important_date_ts = strtotime($important_date);
    $current_date_ts = time();
    $ts_diff = $important_date_ts - $current_date_ts;
    $sec_in_hour = 3600;
    $diff_hour = floor($ts_diff / $sec_in_hour);

    if ($diff_hour < $important_hours and $important_date !== NULL) {
        return true;
    }

    return false;
}

/**
 * Проверяет, есть ли дата выполнения у задачи, если есть, возвращает дату в нужном формате
 * @param $item NULL/string Дата выполнения задачи
 *
 * @return $dt_format string Дата в формате Д.М.Г
 */
function check_deadline($item) {
    if ($item === NULL) {
        return 'Нет';
    }
    $date = date_create($item);
    $dt_format = date_format($date, 'd.m.Y');
    return $dt_format;
}

/**
 * Подключение к СУБД
 * @param $db array Параметры подключения
 *
 * @return $link mysqli Соединение с СУБД
 */
function connect_db($db) {
    $link = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
    mysqli_set_charset($link, 'utf8');

    if (!$link) {
        exit('Сайт временно не доступен');
    }

    return $link;
}

/**
 * Получение списка проектов для данного пользователя
 * @param $link mysqli Соединение с СУБД
 * @param $user_id integer id пользователя
 *
 * @return $data NULL/array Массив проектов
 */
function get_projects($link, $user_id) {
    $data = [];
    $sql_projects = 'SELECT * FROM projects WHERE user_id = ' . $user_id;
    $result = mysqli_query($link, $sql_projects);

    if ($result) {
        $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    return $data;
}

/**
 * Получение списка задач для данного пользователя
 * @param $link mysqli Соединение с СУБД
 * @param $user_id integer id пользователя
 * @param $value string Дополнительные условия для выбора задач
 *
 * @return $data NULL/array Массив проектов
 */
function get_tasks($link, $user_id, $value) {
    $data = [];
    $sql_tasks = 'SELECT * FROM tasks WHERE user_id = ' . $user_id;
    if ($value) {
        $sql_tasks = $sql_tasks . $value;
    }
    $result = mysqli_query($link, $sql_tasks);

    if ($result) {
        $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    return $data;
}
