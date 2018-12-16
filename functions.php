<?php
// функция шаблонизатор
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

// функция подсчета задач
function count_tasks($tasks_list, $name_project) {
    $count_name_project = 0;

    foreach ($tasks_list as $key => $item) {
        if ($item['project_id'] === $name_project) {
            $count_name_project++;
        }
    }
    return $count_name_project;
}

// функция для фильтрации данных
function esc($str) {
    $text = htmlspecialchars($str);

    return $text;
}

// функция для определения дел с датой выполнения меньше 24 часов
function check_important_task($task) {
    $important_hours = 24;
    $important_date = $task['deadline'];
    $important_date_ts = strtotime($important_date);
    $current_date_ts = time();
    $ts_diff = $important_date_ts - $current_date_ts;
    $sec_in_hour = 3600;
    $diff_hour = floor($ts_diff / $sec_in_hour);

    if ($diff_hour < $important_hours and $important_date != NULL) {
        return true;
    }

    return false;
}

function check_deadline($item) {
    if ($item === NULL) {
        return 'Нет';
    }
    $date = date_create($item);
    $dt_format = date_format($date, 'd.m.Y');
    return $dt_format;
}

// подключение к СУБД
function connect_db($db) {
    $link = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
    mysqli_set_charset($link, 'utf8');

    if (!$link) {
        exit('Сайт временно не доступен');
    }

    return $link;
}

// Получение списка проектов для данного пользователя
function get_projects($link, $user_id) {
    $data = [];
    $sql_projects = 'SELECT * FROM projects WHERE user_id = ' . $user_id;
    $result = mysqli_query($link, $sql_projects);

    if ($result) {
        $data['result'] = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $data['result'];
    }
}

// Получение списка задач для данного пользователя
function get_tasks($link, $user_id) {
    $data = [];
    $sql_tasks = 'SELECT * FROM tasks WHERE user_id = ' . $user_id;
    $result = mysqli_query($link, $sql_tasks);

    if ($result) {
        $data['result'] = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $data['result'];
    }
}

// Получение списка активных задач для данного пользователя
function get_active_tasks($link, $user_id) {
    $data = [];
    $sql_tasks = 'SELECT * FROM tasks WHERE user_id = ' . $user_id . ' AND status = 0';;
    $result = mysqli_query($link, $sql_tasks);

    if ($result) {
        $data['result'] = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $data['result'];
    }
}

// Получение списка задач для данного проекта
function get_tasks_project($link, $project_id, $user_id) {
    $tasks = [];
    $sql_projects = 'SELECT * FROM projects WHERE user_id = ' . $user_id;
    $sql_tasks = 'SELECT * FROM tasks WHERE user_id = ' . $user_id;
    $sql_projects_select = $sql_projects . ' AND project_id = ' . $project_id;
    $sql_tasks_select = $sql_tasks . ' AND project_id = ' . $project_id;

    $result_project = mysqli_query($link, $sql_projects_select);

    $result_tasks = mysqli_query($link, $sql_tasks_select);
    $row = mysqli_num_rows($result_project);

    if ($result_project and $result_tasks and $row !== 0) {
        $tasks['result'] = mysqli_fetch_all($result_tasks, MYSQLI_ASSOC);
        return $tasks['result'];
    }
}

// Фильтр задач
function filter_tasks($link, $data, $user_id) {
    $tasks = [];
    $sql_tasks = 'SELECT * FROM tasks WHERE user_id = ' . $user_id . $data;
    $result = mysqli_query($link, $sql_tasks);
    if ($result) {
        $tasks['result'] = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $tasks['result'];
    }
}
