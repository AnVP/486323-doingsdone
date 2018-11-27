CREATE DATABASE IF NOT EXISTS doingsdone_486323
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE doingsdone_486323;

CREATE TABLE IF NOT EXISTS users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  date_registration DATETIME,
  user_email VARCHAR(128) NOT NULL UNIQUE,
  user_name VARCHAR(128) NOT NULL UNIQUE,
  user_password VARCHAR(64) NOT NULL
);

CREATE TABLE IF NOT EXISTS projects (
  project_id INT AUTO_INCREMENT PRIMARY KEY,
  project_name VARCHAR(128) NOT NULL UNIQUE,
  user_id INT,

  FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE IF NOT EXISTS tasks (
  task_id INT AUTO_INCREMENT PRIMARY KEY,
  creation_date DATETIME,
  execution_date DATETIME,
  task_status INT DEFAULT 0,
  task_name VARCHAR(128) NOT NULL,
  task_file VARCHAR(128),
  task_deadline DATETIME,
  user_id INT,
  project_id INT,

  FOREIGN KEY (user_id) REFERENCES users(user_id),
  FOREIGN KEY (project_id) REFERENCES projects(project_id)
);
