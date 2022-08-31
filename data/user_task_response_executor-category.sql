INSERT INTO user (role, name, email, phone, telegram, city_id, password, dt_add)
  VALUES ('customer', 'Анна', 'anna_delyan@mail.ru', '+79253266302', 'https://t.me/delannushka', 67, 'qwerty12345', '2022-08-31');
INSERT INTO user (role, name, email, city_id, password)
  VALUES ('customer', 'Боря', 'bor@mail.ru', 67, 'boryacool');
INSERT INTO user (role, name, email, phone, city_id, password, dt_add)
  VALUES ('customer', 'Таня', 'tan@mail.ru', '+79253266303', 1, 'lalala', '2022-08-25');
INSERT INTO user (role, name, email, phone, telegram, city_id, password, dt_add, availability, permission)
  VALUES ('executor', 'Миша', 'misha@mail.ru', '+79253266301', 'https://t.me/misha', 67, 'qwerty12345', '2022-08-31', true, true);
INSERT INTO user (role, name, email, phone,city_id, password, availability, permission)
  VALUES ('executor', 'Гоша', 'gosha@mail.ru', '+79253266307', 67, 'qwerty12345', true, false);
INSERT INTO user (role, name, email, city_id, password, availability, permission)
  VALUES ('executor', 'Саша', 'sasha@mail.ru', 67, 'qwerty12345', false, true);

INSERT INTO task (user_id, status, budget, city_id, latitude, longitude, description, date_of_execution, category_id)
  VALUE (1, 'new', 5000, 67, 55.7963352, 37.9381413, 'Генеральная уборка', '2022-10-05', 2);
INSERT INTO task (user_id, status, budget, city_id, latitude, longitude, description, category_id)
  VALUE (1, 'new', 3000, 67, 55.7963352, 37.9381413, 'Перевозка мебели', 3);
INSERT INTO task (user_id, status, budget, city_id, latitude, longitude, description, date_of_execution, category_id)
  VALUE (2, 'new', 2000, 67, 55.7963352, 37.9381413, 'Покрасить стены', '2022-09-15', 5);
INSERT INTO task (user_id, status, budget, city_id, latitude, longitude, description, date_of_execution, category_id)
  VALUE (2, 'new', 1500, 67, 55.7963352, 37.9381413, 'Починить компьютер', '2022-10-05', 6);
INSERT INTO task (user_id, status, budget, city_id, latitude, longitude, description, category_id)
  VALUE (3, 'new', 7000, 1, 52.6517296, 90.0885929, 'Сделать ноготочки', 7);
INSERT INTO task (user_id, status, budget, city_id, latitude, longitude, description, category_id)
  VALUE (3, 'new', 5000, 1, 52.6517296, 90.0885929, 'Фотосессия', 8);

INSERT INTO executor_category (category_id, user_id)
  VALUES (2, 4);
INSERT INTO executor_category (category_id, user_id)
  VALUES (7, 4);
INSERT INTO executor_category (category_id, user_id)
  VALUES (7, 5);
INSERT INTO executor_category (category_id, user_id)
  VALUES (8, 6);
INSERT INTO executor_category (category_id, user_id)
  VALUES (8, 4);
INSERT INTO executor_category (category_id, user_id)
  VALUES (1, 5);

INSERT INTO response (user_id, task_id, price, comment)
  VALUES (4, 1, 4000, 'Сделаю все на высшем уровне');
INSERT INTO response (user_id, task_id, price, comment)
  VALUES (4, 5, 7000, 'Сделаю все быстро');
INSERT INTO response (user_id, task_id, price, comment)
  VALUES (5, 5, 2000, 'Я - лучший в своем деле');
INSERT INTO response (user_id, task_id, price, comment)
  VALUES (4, 6, 3000, 'Выполню работу аккуратно и быстро');
INSERT INTO response (user_id, task_id, price, comment)
  VALUES (6, 6, 6000, 'Лекго справляюсь с вашей задачей');
