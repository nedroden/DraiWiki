/**
 * DRAIWIKI
 * Open source wiki software
 *
 * @version     1.0 Alpha 1
 * @author      Robert Monden
 * @copyright   DraiWiki, 2017
 * @license     Apache 2.0
 */

CREATE DATABASE IF NOT EXISTS DraiWiki_test;
USE DraiWiki_test;

CREATE TABLE IF NOT EXISTS drai_setting (
    `key` VARCHAR(32) NOT NULL,
    `value` VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS drai_log_errors (
    id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    message TEXT NOT NULL,
    data TEXT,
    type SMALLINT NOT NULL,
    dtime DATETIME NOT NULL,
    ip_address VARCHAR(45)
);

CREATE TABLE IF NOT EXISTS drai_log_updates (
    id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    version_from VARCHAR(15) NOT NULL,
    version_to VARCHAR(15) NOT NULL,
    dtime DATETIME NOT NULL,
    performed_by INT NOT NULL,
    status SMALLINT NOT NULL
);

CREATE TABLE IF NOT EXISTS drai_locale (
    id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    code CHAR(5) NOT NULL
);

CREATE TABLE IF NOT EXISTS drai_permission_group (
    id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    title VARCHAR(20) NOT NULL,
    permissions LONGTEXT
);

CREATE TABLE IF NOT EXISTS drai_group (
    id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    title VARCHAR(20) NOT NULL,
    color CHAR(6) NOT NULL DEFAULT '000000',
    permission_group_id INT UNSIGNED,
    FOREIGN KEY (permission_group_id) REFERENCES drai_permission_group(id)
);

CREATE TABLE IF NOT EXISTS drai_user (
    id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    username VARCHAR(32) NOT NULL,
    password TEXT NOT NULL,
    email_address VARCHAR(32) NOT NULL,
    sex INT NOT NULL DEFAULT 0,
    birthdate DATE,
    first_name VARCHAR(20),
    last_name VARCHAR(30),
    ip_address VARCHAR(45) NOT NULL,
    registration_date DATETIME NOT NULL DEFAULT NOW(),
    group_id INT UNSIGNED NOT NULL DEFAULT 2,
    secondary_groups TEXT,
    activated INT NOT NULL DEFAULT 0,
    FOREIGN KEY (group_id) REFERENCES drai_group(id)
);

CREATE TABLE IF NOT EXISTS drai_article (
    id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    title VARCHAR(60) NOT NULL,
    locale_id INT UNSIGNED NOT NULL,
    status INT NOT NULL DEFAULT 0,
    FOREIGN KEY (locale_id) REFERENCES drai_locale(id)
);

CREATE TABLE IF NOT EXISTS drai_article_history (
    id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    article_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED,
    body LONGTEXT,
    updated DATETIME NOT NULL DEFAULT NOW(),
    FOREIGN KEY (article_id) REFERENCES drai_article(id),
    FOREIGN KEY (user_id) REFERENCES drai_user(id)
);

CREATE TABLE IF NOT EXISTS drai_homepage (
    article_id INT UNSIGNED NOT NULL,
    locale_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (article_id, locale_id),
    FOREIGN KEY (article_id) REFERENCES drai_article(id),
    FOREIGN KEY (locale_id) REFERENCES drai_locale(id)
);

CREATE TABLE IF NOT EXISTS drai_agreement (
    id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    body LONGTEXT,
    locale_id INT UNSIGNED,
    FOREIGN KEY (locale_id) REFERENCES drai_locale(id)
);

CREATE TABLE IF NOT EXISTS drai_session (
    session_key VARCHAR(32) NOT NULL PRIMARY KEY,
    data TEXT NOT NULL,
    created_at BIGINT(20) NOT NULL
);

CREATE TABLE IF NOT EXISTS drai_activation_code (
    id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    user_id INT UNSIGNED NOT NULL,
    code CHAR(20) NOT NULL,
    creation_date DATETIME NOT NULL DEFAULT NOW(),
    FOREIGN KEY (user_id) REFERENCES drai_user(id)
);

CREATE TABLE IF NOT EXISTS drai_upload (
    id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    original_name TEXT NOT NULL,
    uploaded_name TEXT NOT NULL,
    description LONGTEXT,
    user_id INT UNSIGNED NOT NULL,
    type TINYTEXT NOT NULL,
    upload_date DATETIME NOT NULL DEFAULT NOW(),
    FOREIGN KEY (user_id) REFERENCES drai_user(id)
);