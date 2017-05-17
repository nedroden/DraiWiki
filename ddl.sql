/**
 * DRAIWIKI
 * Open source wiki software
 *
 * @version     1.0 Alpha 1
 * @author      Robert Monden
 * @copyright   DraiWiki, 2017
 * @license     Apache 2.0
 */

CREATE DATABASE IF NOT EXISTS DraiWiki;
USE DraiWiki;

CREATE TABLE IF NOT EXISTS drai_settings (
    `key` VARCHAR(32) NOT NULL,
    `value` VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS drai_log_errors (
    id INT UNSIGNED PRIMARY KEY NOT NULL,
    message TEXT NOT NULL,
    data TEXT,
    type SMALLINT NOT NULL,
    dtime DATETIME NOT NULL
);

CREATE TABLE IF NOT EXISTS drai_log_updates (
    id INT UNSIGNED PRIMARY KEY NOT NULL,
    version_from VARCHAR(15) NOT NULL,
    version_to VARCHAR(15) NOT NULL,
    dtime DATETIME NOT NULL,
    performed_by INT NOT NULL,
    status SMALLINT NOT NULL
);
