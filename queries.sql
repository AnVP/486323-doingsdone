# Придумайте пару пользователей
INSERT INTO users (date_registration, email, name, password)
VALUES ('2018-06-04 22:00:00', 'aaa@mail.ru', 'aaa', '111'),
       ('2018-09-09 20:00:00', 'bbb@mail.ru', 'bbb', '222');

# Существующий список проектов
INSERT INTO projects (name, user_id)
VALUES ('Входящие', 1),
       ('Учеба', 1),
       ('Работа', 2),
       ('Работа', 1),
       ('Домашние дела', 1),
       ('Домашние дела', 2),
       ('Авто', 2);

# Существующий список задач
INSERT INTO tasks (creation_date, execution_date, status, name, file, deadline, user_id, project_id)
VALUES ('2018-10-10 22:00:00', NULL, 0, 'Собеседование в IT компании', 'работа', '2018-12-01 00:00:00', 1, 4),
       ('2018-11-11 22:00:00', NULL, 0, 'Выполнить тестовое задание', 'работа', '2018-11-29 00:00:00', 2, 3),
       ('2018-11-14 22:00:00', '2018-11-15 22:00:00', 1, 'Сделать задание первого раздела', 'учеба', '2018-12-21 00:00:00', 1, 2),
       ('2018-11-18 22:00:00', NULL, 0, 'Купить корм для кота', NULL, NULL, 1, 5),
       ('2018-11-18 22:00:00', NULL, 0, 'Заказать пиццу', NULL, NULL, 2, 6);

# получить список из всех проектов для одного пользователя
SELECT * FROM projects WHERE user_id = 1;

# получить список задач для одного пользователя
SELECT * FROM tasks WHERE user_id = 1;

# получить список из всех задач для одного проекта
SELECT * FROM tasks WHERE project_id = 1;

# пометить задачу как выполненную
UPDATE tasks SET status = 1
WHERE task_id = 5;

# получить все задачи для завтрашнего дня
SELECT * FROM tasks WHERE deadline = ADDDATE(CURDATE(), INTERVAL 1 DAY);

# обновить название задачи по её идентификатору
UPDATE tasks SET name = 'Название задачи'
WHERE task_id = 2;
