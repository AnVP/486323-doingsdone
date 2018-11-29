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

    if ($diff_hour < $important_hours and $task['deadline'] != NULL) {
        return true;
    }

    return false;
}
