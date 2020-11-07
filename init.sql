DROP DATABASE IF EXISTS billing;
CREATE DATABASE billing DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;



DROP TABLE IF EXISTS billing_account;
CREATE TABLE billing_account
(
    id                    INTEGER PRIMARY KEY AUTO_INCREMENT,
    id_type               TINYINT UNSIGNED DEFAULT 0 COMMENT 'тип аккаунта: 1 - пользователь, 2 - фирма',
    id_account            INTEGER UNSIGNED DEFAULT 0 COMMENT 'id пользователя, id фирмы',
    account_balance       DECIMAL(10, 2)   DEFAULT 0 COMMENT 'баланс',
    account_balance_bonus DECIMAL(10, 2)   DEFAULT 0 COMMENT 'баланс в бонусах',
    account_add           INTEGER UNSIGNED DEFAULT 0,
    account_update        INTEGER UNSIGNED DEFAULT 0
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8 COMMENT 'аккаунты пользователей и фирм';
ALTER TABLE billing_account
    ADD INDEX (id_account);
ALTER TABLE billing_account
    ADD INDEX (id_type);



DROP TABLE IF EXISTS billing_posting;
CREATE TABLE billing_posting
(
    id              INTEGER PRIMARY KEY AUTO_INCREMENT,
    id_account      INTEGER UNSIGNED DEFAULT 0,
    posting_amount  DECIMAL(10, 2)   DEFAULT 0,
    posting_comment CHAR(250)        DEFAULT '',
    posting_day     INTEGER UNSIGNED DEFAULT 0,
    posting_add     DECIMAL(12, 2)   DEFAULT 0
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8 COMMENT 'проводки в рублях';
ALTER TABLE billing_posting
    ADD INDEX (id_account);



DROP TABLE IF EXISTS billing_posting_bonus;
CREATE TABLE billing_posting_bonus
(
    id              INTEGER PRIMARY KEY AUTO_INCREMENT,
    id_account      INTEGER UNSIGNED DEFAULT 0,
    posting_amount  DECIMAL(10, 2)   DEFAULT 0,
    posting_comment CHAR(250)        DEFAULT '',
    posting_day     INTEGER UNSIGNED DEFAULT 0,
    posting_add     DECIMAL(12, 2)   DEFAULT 0
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8 COMMENT 'проводки в бонусах';
ALTER TABLE billing_posting_bonus
    ADD INDEX (id_account);