--
-- DRAIWIKI
-- Open source wiki software
--
-- @version     1.0 Alpha 1
-- @author      Robert Monden
-- @copyright   2017-2018 DraiWiki
-- @license     Apache 2.0
--

CREATE TABLE IF NOT EXISTS {db_prefix}setting (
    `key` VARCHAR(32) NOT NULL,
    `value` VARCHAR(255) NOT NULL
) CHARACTER SET=utf8mb4;

CREATE TABLE IF NOT EXISTS {db_prefix}log_errors (
    id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    message TEXT NOT NULL,
    data TEXT,
    type SMALLINT NOT NULL,
    dtime DATETIME NOT NULL,
    ip_address VARCHAR(45)
) CHARACTER SET=utf8mb4;

CREATE TABLE IF NOT EXISTS {db_prefix}log_updates (
    id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    version_from VARCHAR(15) NOT NULL,
    version_to VARCHAR(15) NOT NULL,
    dtime DATETIME NOT NULL,
    performed_by INT NOT NULL,
    status SMALLINT NOT NULL
) CHARACTER SET=utf8mb4;

CREATE TABLE IF NOT EXISTS {db_prefix}locale (
    id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    code CHAR(5) NOT NULL
) CHARACTER SET=utf8mb4;

CREATE TABLE IF NOT EXISTS {db_prefix}permission_group (
    id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    title VARCHAR(20) NOT NULL,
    permissions LONGTEXT
) CHARACTER SET=utf8mb4;

CREATE TABLE IF NOT EXISTS {db_prefix}group (
    id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    title VARCHAR(20) NOT NULL,
    color CHAR(6) NOT NULL DEFAULT '000000',
    permission_group_id INT UNSIGNED,
    FOREIGN KEY (permission_group_id) REFERENCES {db_prefix}permission_group(id)
) CHARACTER SET=utf8mb4;

CREATE TABLE IF NOT EXISTS {db_prefix}user (
    id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    username VARCHAR(32) NOT NULL,
    password TEXT NOT NULL,
    email_address VARCHAR(32) NOT NULL,
    locale_id INT UNSIGNED,
    sex INT NOT NULL DEFAULT 0,
    birthdate DATE,
    first_name VARCHAR(20),
    last_name VARCHAR(30),
    ip_address VARCHAR(45) NOT NULL,
    registration_date DATETIME NOT NULL DEFAULT NOW(),
    group_id INT UNSIGNED NOT NULL DEFAULT 2,
    secondary_groups TEXT,
    activated INT NOT NULL DEFAULT 0,
    FOREIGN KEY (locale_id) REFERENCES {db_prefix}locale(id),
    FOREIGN KEY (group_id) REFERENCES {db_prefix}group(id)
) CHARACTER SET=utf8mb4;

CREATE TABLE IF NOT EXISTS {db_prefix}article (
    id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    title VARCHAR(60) NOT NULL,
    group_id INT UNSIGNED DEFAULT 0,
    locale_id INT UNSIGNED,
    status INT NOT NULL DEFAULT 1,
    FOREIGN KEY (locale_id) REFERENCES {db_prefix}locale(id)
) CHARACTER SET=utf8mb4;

CREATE TABLE IF NOT EXISTS {db_prefix}article_history (
    id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    article_id INT UNSIGNED,
    user_id INT UNSIGNED,
    body LONGTEXT,
    updated DATETIME NOT NULL DEFAULT NOW(),
    FOREIGN KEY (article_id) REFERENCES {db_prefix}article(id),
    FOREIGN KEY (user_id) REFERENCES {db_prefix}user(id)
) CHARACTER SET=utf8mb4;

CREATE TABLE IF NOT EXISTS {db_prefix}homepage (
    article_id INT UNSIGNED NOT NULL,
    locale_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (article_id, locale_id),
    FOREIGN KEY (article_id) REFERENCES {db_prefix}article(id),
    FOREIGN KEY (locale_id) REFERENCES {db_prefix}locale(id)
) CHARACTER SET=utf8mb4;

CREATE TABLE IF NOT EXISTS {db_prefix}agreement (
    id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    body LONGTEXT,
    locale_id INT UNSIGNED,
    FOREIGN KEY (locale_id) REFERENCES {db_prefix}locale(id)
) CHARACTER SET=utf8mb4;

CREATE TABLE IF NOT EXISTS {db_prefix}session (
    session_key VARCHAR(32) NOT NULL PRIMARY KEY,
    data TEXT NOT NULL,
    created_at BIGINT(20) NOT NULL
) CHARACTER SET=utf8mb4;

CREATE TABLE IF NOT EXISTS {db_prefix}activation_code (
    id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    user_id INT UNSIGNED NOT NULL,
    code CHAR(20) NOT NULL,
    creation_date DATETIME NOT NULL DEFAULT NOW(),
    FOREIGN KEY (user_id) REFERENCES {db_prefix}user(id)
) CHARACTER SET=utf8mb4;

CREATE TABLE IF NOT EXISTS {db_prefix}upload (
    id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    original_name TEXT NOT NULL,
    uploaded_name TEXT NOT NULL,
    description LONGTEXT,
    user_id INT UNSIGNED,
    type TINYTEXT NOT NULL,
    upload_date DATETIME NOT NULL DEFAULT NOW(),
    FOREIGN KEY (user_id) REFERENCES {db_prefix}user(id)
) CHARACTER SET=utf8mb4;

CREATE TABLE IF NOT EXISTS {db_prefix}log_changes (
    id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    user_id INT UNSIGNED,
    locale_id INT UNSIGNED NOT NULL,
    article_id INT UNSIGNED,
    type SMALLINT NOT NULL DEFAULT 0,
    creation_date DATETIME NOT NULL DEFAULT NOW(),
    FOREIGN KEY (user_id) REFERENCES {db_prefix}user(id),
    FOREIGN KEY (locale_id) REFERENCES {db_prefix}locale(id),
    FOREIGN KEY (article_id) REFERENCES {db_prefix}article(id)
) CHARACTER SET=utf8mb4;