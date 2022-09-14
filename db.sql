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
  category_id INT NOT NULL,
  files VARCHAR(255),
  FOREIGN KEY (city_id) REFERENCES city (id),
  FOREIGN KEY (user_id) REFERENCES user (id),
  FOREIGN KEY (category_id) REFERENCES category (id)
);
/* отклики */
CREATE TABLE response (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  task_id INT NOT NULL,
  price INT UNSIGNED NOT NULL,
  comment VARCHAR(255) NOT NULL,
  score TINYINT NOT NULL,
  feedback VARCHAR(255) NOT NULL,
  FOREIGN KEY (user_id) REFERENCES user (id),
  FOREIGN KEY (task_id) REFERENCES task (id)
);
/* таблица связывающая исполнителей и категории */
CREATE TABLE executor_category (
  category_id INT NOT NULL,
  user_id INT NOT NULL,
  PRIMARY KEY (category_id, user_id),
  FOREIGN KEY (user_id) REFERENCES user (id),
  FOREIGN KEY (category_id) REFERENCES category (id)
);

ALTER TABLE category
  ADD icon VARCHAR(255) NOT NULL;

ALTER TABLE city
  MODIFY longitude DECIMAL(11,7);
ALTER TABLE city
  MODIFY latitude DECIMAL(11,7);

ALTER TABLE response
  MODIFY score TINYINT;
ALTER TABLE response
  MODIFY feedback VARCHAR(255);

UPDATE task SET details = 'Убрать трешку, помыть все и отмыть до блеска' WHERE id = 1;
UPDATE task SET details = 'Всю мебель (кровать, чемодан и кресло) загрузить и в лучшем виде привезти' WHERE id = 2;
UPDATE task SET details = 'Покрасить стены в белый' WHERE id = 3;
UPDATE task SET details = 'Неизвестно что случилось, просто починить, чтобы работал как раньше' WHERE id = 4;
UPDATE task SET details = 'Аккрутно, красиво и быстро сделать френч' WHERE id = 5;
UPDATE task SET details = 'Сделать 100 фоток за полчаса на природе или у фонтана' WHERE id = 6;

ALTER TABLE task
    MODIFY longitude DECIMAL(11,7);
ALTER TABLE task
    MODIFY latitude DECIMAL(11,7);

UPDATE task SET latitude = 55.7963352, longitude = 37.9381413 WHERE id < 5;
UPDATE task SET latitude = 52.6517296, longitude = 90.0885929 WHERE id > 4;