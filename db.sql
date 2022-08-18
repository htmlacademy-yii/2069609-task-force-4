CREATE DATABASE taskforce;
use taskforce;
/* города */
CREATE TABLE city (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  latitude DECIMAL NOT NULL,
  longitude DECIMAL NOT NULL
);
/* пользователи */
CREATE TABLE user (
  id INT AUTO_INCREMENT PRIMARY KEY,
  role enum('executor', 'customer') NOT NULL,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  /* сохраняется в формате 9253266302 */
  phone VARCHAR(32),
  telegram VARCHAR(255),
  city_id INT NOT NULL,
  password CHAR(64) NOT NULL,
  dt_add TIMESTAMP DEFAULT NOW(),
  vk VARCHAR(255) UNIQUE,
  rating INT DEFAULT 0,
  /* если availability === true, исполнитель свободен */
  availability BOOLEAN,
  /* если permission === true, данные об исполнителя видны всем */
  permission BOOLEAN,
  FOREIGN KEY (city_id) REFERENCES city (id)
);
/* категории */
CREATE TABLE category (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(64) NOT NULL
);
/* задания */
CREATE TABLE task (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  status VARCHAR(64) NOT NULL,
  budget INT UNSIGNED,
  city_id INT,
  latitude DECIMAL NOT NULL,
  longitude DECIMAL NOT NULL,
  date_of_publication TIMESTAMP DEFAULT NOW(),
  description VARCHAR(255) NOT NULL,
  date_of_execution DATE,
  id_category INT NOT NULL,
  files VARCHAR(255),
  FOREIGN KEY (city_id) REFERENCES city (id),
  FOREIGN KEY (user_id) REFERENCES user (id),
  FOREIGN KEY (id_category) REFERENCES category (id)
);
/* отклики */
CREATE TABLE response (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_user INT NOT NULL,
  id_task INT NOT NULL,
  price INT UNSIGNED NOT NULL,
  comment VARCHAR(255) NOT NULL,
  score TINYINT NOT NULL,
  feedback VARCHAR(255) NOT NULL,
  FOREIGN KEY (id_user) REFERENCES user (id),
  FOREIGN KEY (id_task) REFERENCES task (id)
);
/* таблица связывающая исполнителей и категории */
CREATE TABLE executor_category (
  id_category INT AUTO_INCREMENT PRIMARY KEY,
  id_user INT NOT NULL,
  FOREIGN KEY (id_user) REFERENCES user (id),
  FOREIGN KEY (id_category) REFERENCES category (id)
);


