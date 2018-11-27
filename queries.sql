# Придумайте пару пользователей
INSERT INTO users (date_registration, email, name, password)
VALUES ('2018-06-04 22:00:00', 'aaa@mail.ru', 'aaa', '111'),
       ('2018-09-09 20:00:00', 'bbb@mail.ru', 'bbb', '222');

# Существующий список проектов
INSERT INTO projects (name, user_id)
VALUES ('Входящие', 1),
       ('Учеба', 1),
       ('Работа', 2),
       ('Домашние дела', 1),
       ('Авто', 2);

# Существующий список задач
INSERT INTO tasks (creation_date, execution_date, status, name, file, deadline, user_id, project_id)
VALUES ('2018-10-10 22:00:00', NULL, 0, 'Собеседование в IT компании', 'работа.psd', '2018-12-01 00:00:00', 1, 3),
       ('2018-11-11 22:00:00', NULL, 0, 'Выполнить тестовое задание', 'работа.psd', '2018-11-28 00:00:00', 2, 3),
       ('2018-11-14 22:00:00', '2018-11-15 22:00:00', 1, 'Сделать задание первого раздела', 'учеба.psd', '2018-12-21 00:00:00', 1, 2),
       ('2018-11-18 22:00:00', NULL, 0, 'Купить корм для кота', NULL, NULL, 1, 4),
       ('2018-11-18 22:00:00', NULL, 0, 'Заказать пиццу', NULL, NULL, 2, 4);

# получить список из всех проектов для одного пользователя
SELECT * FROM projects WHERE user_id = 1;

# получить список из всех задач для одного проекта
SELECT * FROM tasks WHERE project_id = 2;

# пометить задачу как выполненную
UPDATE tasks SET status = 1
WHERE task_id = 3;

# получить все задачи для завтрашнего дня
SELECT * FROM tasks WHERE deadline = '2018-11-28';

# обновить название задачи по её идентификатору
UPDATE tasks SET name = 'Название задачи'
WHERE task_id = 2;
