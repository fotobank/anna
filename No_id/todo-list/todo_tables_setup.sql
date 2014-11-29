CREATE DATABASE  IF NOT EXISTS `todo`
USE `todo`;

DROP TABLE IF EXISTS `cat0`;
CREATE TABLE `cat0` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `posizione` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `todo`;
CREATE TABLE `todo` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `posizione` int(8) unsigned NOT NULL DEFAULT '0',
  `testo` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `priorita` int(10) unsigned DEFAULT '0',
  `id_cat` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `position` (`posizione`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;

