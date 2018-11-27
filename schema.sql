CREATE DATABASE IF NOT EXISTS doingsdone_486323
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE doingsdone_486323;

CREATE TABLE IF NOT EXISTS users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  date_registration TIMESTAMP,
  email VARCHAR(128) NOT NULL UNIQUE,
  name VARCHAR(128) NOT NULL,
  password VARCHAR(64) NOT NULL
);

CREATE TABLE IF NOT EXISTS projects (
  project_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(128) NOT NULL,
  user_id INT,

  FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE IF NOT EXISTS tasks (
  task_id INT AUTO_INCREMENT PRIMARY KEY,
  creation_date TIMESTAMP,
  execution_date TIMESTAMP,
  status INT DEFAULT 0,
  name VARCHAR(128) NOT NULL,
  file VARCHAR(128),
  deadline TIMESTAMP,
  user_id INT NOT NULL,
  project_id INT NOT NULL,

  FOREIGN KEY (user_id) REFERENCES users(user_id),
  FOREIGN KEY (project_id) REFERENCES projects(project_id)
);
