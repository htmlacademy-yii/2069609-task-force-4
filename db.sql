CREATE DATABASE taskforce;
use taskforce;
/* города */
CREATE TABLE cities (
  id_city INT AUTO_INCREMENT PRIMARY KEY,
  name_city VARCHAR(255) NOT NULL,
  point POINT NOT NULL
);
/* пользователи */
CREATE TABLE users (
  id_user INT AUTO_INCREMENT PRIMARY KEY,
  /* если role === true, это исполнитель */
  role BOOLEAN NOT NULL,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  /* сохраняется в формате 9253266302 */
  phone DECIMAL(10,0) NOT NULL,
  telegram VARCHAR(255),
  id_city INT NOT NULL,
  password CHAR(64),
  dt_add TIMESTAMP DEFAULT NOW(),
  vk VARCHAR(255) UNIQUE,
  FOREIGN KEY (id_city) REFERENCES cities (id_city)
);
/* исполнители */
CREATE TABLE executors (
  id_executor INT AUTO_INCREMENT PRIMARY KEY,
  id_user INT NOT NULL,
  rating INT DEFAULT 0,
  /* если availability === true, исполнитель свободен */
  availability BOOLEAN NOT NULL,
  /* если permission === true, данные об исполнителя видны всем */
  permission BOOLEAN NOT NULL,
  FOREIGN KEY (id_user) REFERENCES users (id_user)
);
/* категории */
CREATE TABLE categories (
  id_category INT AUTO_INCREMENT PRIMARY KEY,
  name_category VARCHAR(64) NOT NULL
);
/* задания */
CREATE TABLE tasks (
  id_task INT AUTO_INCREMENT PRIMARY KEY,
  id_user INT NOT NULL,
  status VARCHAR(64) NOT NULL,
  budget INT UNSIGNED,
  id_city INT,
  point POINT,
  date_of_publication TIMESTAMP DEFAULT NOW(),
  description VARCHAR(255) NOT NULL,
  date_of_execution DATE,
  id_category INT NOT NULL,
  files VARCHAR(255),
  FOREIGN KEY (id_city) REFERENCES cities (id_city),
  FOREIGN KEY (id_user) REFERENCES users (id_user),
  FOREIGN KEY (id_category) REFERENCES categories (id_category)
);
/* отклики */
CREATE TABLE responses (
  id_response INT AUTO_INCREMENT PRIMARY KEY,
  id_user INT NOT NULL,
  id_task INT NOT NULL,
  price INT UNSIGNED NOT NULL,
  comment VARCHAR(255) NOT NULL,
  score DECIMAL(1, 0) NOT NULL,
  feedback VARCHAR(255) NOT NULL,
  FOREIGN KEY (id_user) REFERENCES users (id_user),
  FOREIGN KEY (id_task) REFERENCES tasks (id_task)
);
/* таблица связывающая исполнителей и категории */
CREATE TABLE executor_categories (
  id_category INT AUTO_INCREMENT PRIMARY KEY,
  id_user INT NOT NULL,
  FOREIGN KEY (id_user) REFERENCES users (id_user),
  FOREIGN KEY (id_category) REFERENCES categories (id_category)
);


