CREATE DATABASE lection_3;
USE lection_3;

-- В MySQL Workbench:
-- 1. Открыть редактирование Connection через контекстное меню
-- 2. На вкладке "Connection" перейти в группу "Advanced" и в поле "Others:" добавить строку "OPT_LOCAL_INFILE=1"
-- 3. Подключиться к БД
-- https://stackoverflow.com/questions/63361962/error-2068-hy000-load-data-local-infile-file-request-rejected-due-to-restrict
SET GLOBAL local_infile = 1;

-- 1. Загрузка данных

DROP TABLE IF EXISTS limitation_data;

CREATE TABLE limitation_data
(
  created_at     VARCHAR(100),
  act_number     VARCHAR(50),
  type           VARCHAR(50),
  start_date     VARCHAR(100),
  country_name   VARCHAR(100),
  region_name    VARCHAR(100),
  ban_on_transit MEDIUMTEXT,
  PRIMARY KEY (act_number)
);

-- NOTE: Здесь следует указать путь в своей файловой системе
LOAD DATA LOCAL INFILE
  '/home/sergeyshambir/projects/ips/2_ui_table/data/limitation.csv'
  INTO TABLE limitation_data
  FIELDS TERMINATED BY ',' ENCLOSED BY '"'
  LINES TERMINATED BY '\r\n'
  IGNORE 1 LINES;

DROP TABLE IF EXISTS limitation_ban_on_product_data;

CREATE TABLE limitation_ban_on_product_data
(
  act_number   VARCHAR(50),
  product_name MEDIUMTEXT
);

-- NOTE: Здесь следует указать путь в своей файловой системе
LOAD DATA LOCAL INFILE
  '/home/sergeyshambir/projects/ips/2_ui_table/data/limitation_ban_on_product.csv'
  INTO TABLE limitation_ban_on_product_data
  FIELDS TERMINATED BY ',' ENCLOSED BY '"'
  LINES TERMINATED BY '\r\n'
  IGNORE 1 LINES;

-- 2. Создание справочных таблиц

DROP TABLE IF EXISTS country;

CREATE TABLE country
(
  id           INT UNSIGNED AUTO_INCREMENT,
  country_name VARCHAR(100) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY (country_name)
);

INSERT INTO country (country_name)
SELECT DISTINCT
  country_name
FROM limitation_data;

DROP TABLE IF EXISTS limitation_type;
CREATE TABLE limitation_type
(
  type      INT UNSIGNED NOT NULL,
  type_name VARCHAR(100) NOT NULL,
  PRIMARY KEY (type)
);

INSERT INTO limitation_type(type, type_name)
VALUES (1, 'Введение ограничений'),
       (2, 'Отмена ограничений')
;

-- 3. Разбор даты-времени в формате DECIMAL

DROP FUNCTION IF EXISTS parse_decimal_date;

CREATE FUNCTION parse_decimal_date(value VARCHAR(50))
  RETURNS DATE
  DETERMINISTIC
  RETURN
    DATE_ADD(
      '1900-01-01',
      INTERVAL SUBSTRING_INDEX(value, '.', 1) DAY
      )
;

DROP FUNCTION IF EXISTS parse_decimal_date_time;

CREATE FUNCTION parse_decimal_date_time(value VARCHAR(50))
  RETURNS DATETIME
  DETERMINISTIC
  RETURN
    DATE_ADD(
      DATE_ADD('1900-01-01', INTERVAL SUBSTRING_INDEX(value, '.', 1) DAY),
      INTERVAL ROUND(
        3600 * 24 * CONCAT('0.', SUBSTRING_INDEX(value, '.', -1))) SECOND
      )
;

SELECT parse_decimal_date_time('44007.40070601852');
SELECT parse_decimal_date('44007');

-- 4. Таблица limitation

DROP TABLE IF EXISTS limitation;

CREATE TABLE limitation
(
  act_number     VARCHAR(50)  NOT NULL,
  created_at     DATETIME     NOT NULL,
  type           INT UNSIGNED NOT NULL,
  start_date     DATE         NOT NULL,
  country_id     INT UNSIGNED NOT NULL,
  ban_on_transit MEDIUMTEXT   NOT NULL,
  PRIMARY KEY (act_number),
  FOREIGN KEY fk_limitation_country_id (type) REFERENCES country (id),
  FOREIGN KEY fk_limitation_type (type) REFERENCES limitation_type (type)
);

INSERT INTO limitation (act_number, created_at, type, start_date, country_id, ban_on_transit)
SELECT DISTINCT
  d.act_number,
  parse_decimal_date_time(d.created_at),
  t.type,
  parse_decimal_date(d.start_date),
  c.id,
  d.ban_on_transit
FROM limitation_data d
  LEFT JOIN limitation_type t ON d.type = t.type_name
  LEFT JOIN country c ON d.country_name = c.country_name
;

DROP TABLE IF EXISTS product;
CREATE TABLE product
(
  id           INT UNSIGNED AUTO_INCREMENT,
  product_name MEDIUMTEXT NOT NULL,
  PRIMARY KEY (id)
);

INSERT INTO product (product_name)
SELECT DISTINCT
  product_name
FROM limitation_ban_on_product_data
;

DROP TABLE IF EXISTS limitation_ban_on_product;
CREATE TABLE limitation_ban_on_product
(
  act_number VARCHAR(50)  NOT NULL,
  product_id INT UNSIGNED NOT NULL,
  PRIMARY KEY (act_number, product_id),
  FOREIGN KEY fk_limitation_ban_on_product_act_number (act_number) REFERENCES limitation (act_number),
  FOREIGN KEY fk_limitation_ban_on_product_product_id (product_id) REFERENCES product (id)
);

INSERT INTO limitation_ban_on_product (act_number, product_id)
SELECT
  d.act_number,
  p.id
FROM limitation_ban_on_product_data d
  INNER JOIN product p ON d.product_name = p.product_name
;
