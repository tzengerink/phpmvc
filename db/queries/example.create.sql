/** -- DROP, CREATE AND USE DB -------------------------------------------- */

DROP DATABASE IF EXISTS `[database_name]`;
CREATE DATABASE IF NOT EXISTS `[database_name]`;
USE `[database_name]`;

/** -- CREATE TABLES ------------------------------------------------------ */

CREATE TABLE IF NOT EXISTS `users` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`username` VARCHAR(255) NOT NULL,
	`password` VARCHAR(255) NOT NULL,
	`email` VARCHAR(255) NOT NULL,
	`level` INT(11) DEFAULT 1,
	`nonce` VARCHAR(32) NOT NULL,
	`ip_address` VARCHAR(255) DEFAULT NULL,
	`logins` INT(11) NOT NULL DEFAULT 0,
	`last_login` INT(10) DEFAULT NULL,
	`created` INT(10) NOT NULL,
	`updated` INT(10) DEFAULT NULL,
	PRIMARY KEY (`id`)   
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

/** -- ADD CONSTRAINTS ---------------------------------------------------- */

/** -- INSERTS ------------------------------------------------------------ */
