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
        if ($item['category'] === $name_project) {
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
