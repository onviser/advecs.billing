DROP DATABASE IF EXISTS billing;
CREATE DATABASE billing DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;



DROP TABLE IF EXISTS billing_account;
CREATE TABLE billing_account
(
    id_account            INTEGER PRIMARY KEY AUTO_INCREMENT,
    id_type               TINYINT UNSIGNED DEFAULT 0 COMMENT 'тип аккаунта: 1 - пользователь, 2 - фирма',
    id_external           INTEGER UNSIGNED DEFAULT 0 COMMENT 'внешний id пользователя, id фирмы',
    account_balance       DECIMAL(10, 2)   DEFAULT 0 COMMENT 'баланс',
    account_balance_bonus DECIMAL(10, 2)   DEFAULT 0 COMMENT 'баланс в бонусах',
    account_add           INTEGER UNSIGNED DEFAULT 0,
    account_update        INTEGER UNSIGNED DEFAULT 0
) ENGINE = InnoDB
  AUTO_INCREMENT = 1000
  DEFAULT CHARSET = utf8 COMMENT 'аккаунты пользователей и фирм';
ALTER TABLE billing_account
    ADD INDEX (id_external);
ALTER TABLE billing_account
    ADD INDEX (id_type);



DROP TABLE IF EXISTS billing_posting;
CREATE TABLE billing_posting
(
    id              INTEGER PRIMARY KEY AUTO_INCREMENT,
    id_account      INTEGER UNSIGNED DEFAULT 0,
    id_from         INTEGER UNSIGNED DEFAULT 0 COMMENT 'откуда пришла проводка, id пользователя, id фирмы',
    id_to           INTEGER UNSIGNED DEFAULT 0 COMMENT 'куда ушла проводка, id пользователя, id фирмы',
    posting_amount  DECIMAL(10, 2)   DEFAULT 0,
    posting_comment CHAR(250)        DEFAULT '',
    posting_day     INTEGER UNSIGNED DEFAULT 0,
    posting_add     DECIMAL(12, 2)   DEFAULT 0
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8 COMMENT 'проводки в рублях';
ALTER TABLE billing_posting
    ADD INDEX (id_account);
ALTER TABLE billing_posting
    ADD INDEX (posting_day);



DROP TABLE IF EXISTS billing_posting_bonus;
CREATE TABLE billing_posting_bonus
(
    id              INTEGER PRIMARY KEY AUTO_INCREMENT,
    id_account      INTEGER UNSIGNED DEFAULT 0,
    id_from         INTEGER UNSIGNED DEFAULT 0 COMMENT 'откуда пришла проводка, id пользователя, id фирмы',
    id_to           INTEGER UNSIGNED DEFAULT 0 COMMENT 'куда ушла проводка, id пользователя, id фирмы',
    posting_amount  DECIMAL(10, 2)   DEFAULT 0,
    posting_comment CHAR(250)        DEFAULT '',
    posting_day     INTEGER UNSIGNED DEFAULT 0,
    posting_add     DECIMAL(12, 2)   DEFAULT 0
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8 COMMENT 'проводки в бонусах';
ALTER TABLE billing_posting_bonus
    ADD INDEX (id_account);
ALTER TABLE billing_posting_bonus
    ADD INDEX (posting_day);


DROP TABLE IF EXISTS billing_pscb_payment;
CREATE TABLE billing_pscb_payment
(
    id_payment      INTEGER PRIMARY KEY AUTO_INCREMENT,
    id_account      INTEGER UNSIGNED DEFAULT 0,
    payment_amount  DECIMAL(10, 2)   DEFAULT 0,
    payment_comment CHAR(250)        DEFAULT '',
    payment_type    CHAR(20)         DEFAULT '',
    payment_status  TINYINT UNSIGNED DEFAULT 0,
    payment_add     INTEGER UNSIGNED DEFAULT 0,
    payment_json    TEXT
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8 COMMENT 'ПСКБ, платежи';
ALTER TABLE billing_pscb_payment
    ADD INDEX (id_account);
ALTER TABLE billing_pscb_payment
    ADD INDEX (payment_status);
ALTER TABLE billing_pscb_payment
    ADD INDEX (payment_add);


DROP TABLE IF EXISTS billing_pscb_notify;
CREATE TABLE billing_pscb_notify
(
    id_notify   INTEGER PRIMARY KEY AUTO_INCREMENT,
    notify_raw  TEXT,
    notify_json TEXT,
    notify_add  INTEGER UNSIGNED DEFAULT 0
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8 COMMENT 'ПСКБ, уведомления от банка';