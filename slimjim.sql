CREATE DATABASE IF NOT EXISTS `slimjim`;

USE `slimjim`;

CREATE TABLE `projects` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `clone_url` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `branch` varchar(255) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `settings` (
  `key` varchar(100) NOT NULL DEFAULT '',
  `value` text,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `settings` WRITE;

INSERT INTO `settings` (`key`, `value`)
VALUES
	('allowed_from','207.97.227.253,50.57.128.197,108.171.174.178'),
	('hook_file','Hooks');

UNLOCK TABLES;