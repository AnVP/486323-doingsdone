<h2 class="content__main-heading">Список задач</h2>

<form class="search-form" action="index.php" method="get">
    <input class="search-form__input" type="text" name="search" value="" placeholder="Поиск по задачам">

    <input class="search-form__submit" type="submit" name="" value="Искать">
</form>

<div class="tasks-controls">
    <nav class="tasks-switch">
        <a href="/?<?php if (!empty($project_id)) : ?>project_id=<?= $project_id; ?><?php endif; ?>" class="tasks-switch__item <?php if ($task_filter === NULL): ?> tasks-switch__item--active <?php endif; ?>">Все задачи</a>
        <a href="/?<?php if (!empty($project_id)) : ?>project_id=<?= $project_id; ?>&<?php endif; ?>tasks-switch=today" class="tasks-switch__item <?php if ($task_filter === 'today'): ?> tasks-switch__item--active <?php endif; ?>">Повестка дня</a>
        <a href="/?<?php if (!empty($project_id)) : ?>project_id=<?= $project_id; ?>&<?php endif; ?>tasks-switch=tomorrow" class="tasks-switch__item <?php if ($task_filter === 'tomorrow'): ?> tasks-switch__item--active <?php endif; ?>">Завтра</a>
        <a href="/?<?php if (!empty($project_id)) : ?>project_id=<?= $project_id; ?>&<?php endif; ?>tasks-switch=expired" class="tasks-switch__item <?php if ($task_filter === 'expired'): ?> tasks-switch__item--active <?php endif; ?>">Просроченные</a>
    </nav>

    <label class="checkbox">
        <!--добавить сюда аттрибут "checked", если переменная $show_complete_tasks равна единице-->
    <input class="checkbox__input visually-hidden show_completed" type="checkbox" <?php if ($show_complete_tasks === 1): ?> checked <?php endif; ?>>
        <span class="checkbox__text">Показывать выполненные</span>
    </label>
</div>

<?php if (!empty($tasks)): ?>
<table class="tasks">
    <?php foreach ($tasks as $key => $item): ?>
    <?php if (!$item['status'] or $show_complete_tasks === 1): ?>
    <tr class="tasks__item task
        <?php if ($item['status']): ?> task--completed <?php endif; ?>
        <?php if (check_important_task($item)): ?> task--important <?php endif; ?>">
        <td class="task__select">
            <label class="checkbox task__checkbox">
                <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" value="<?=$item['task_id']; ?>" <?php if ($item['status']): ?> checked <?php endif; ?>>
                <span class="checkbox__text"><?= esc($item['name']); ?></span>
            </label>
        </td>

        <td class="task__file">
          <?php if ($item['file']): ?>
            <a class="download-link" href="<?= 'uploads/' . $item['file']; ?>">Файл</a>
          <?php endif; ?>
        </td>

        <td class="task__date"><?= check_deadline($item['deadline']); ?></td>
    </tr>
    <?php endif; ?>
    <?php endforeach; ?>

</table>
<?php elseif(!empty($search)): ?>
<p>Ничего не найдено по вашему запросу</p>
<?php endif; ?>
