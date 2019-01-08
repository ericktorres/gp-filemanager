# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.5.5-10.1.13-MariaDB)
# Database: gp_filemanager
# Generation Time: 2019-01-08 08:13:15 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table files
# ------------------------------------------------------------

DROP TABLE IF EXISTS `files`;

CREATE TABLE `files` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `version` float(5,2) NOT NULL,
  `description` text,
  `path` varchar(100) NOT NULL DEFAULT '',
  `creation_date` datetime NOT NULL,
  `creation_user` int(11) NOT NULL,
  `modification_date` datetime DEFAULT NULL,
  `modification_user` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `files` WRITE;
/*!40000 ALTER TABLE `files` DISABLE KEYS */;

INSERT INTO `files` (`id`, `version`, `description`, `path`, `creation_date`, `creation_user`, `modification_date`, `modification_user`)
VALUES
	(1,1.00,'2.0','../backend/files/','2019-01-08 09:01:54',1,NULL,NULL),
	(2,1.00,'Archivo de prueba','mac-symbols.png','2019-01-08 09:05:38',1,NULL,NULL),
	(3,1.00,'Otro archivo de prueba','Screen Shot 2018-09-14 at 00.03.00.png','2019-01-08 09:06:46',1,NULL,NULL),
	(4,1.00,'Ok','Screen Shot 2018-01-07 at 19.44.50.png','2019-01-08 09:08:43',1,NULL,NULL),
	(5,1.00,'Vamos','Screen Shot 2018-06-20 at 16.33.16.png','2019-01-08 09:09:02',1,NULL,NULL),
	(6,1.00,'ok esta es otra','mac-symbols.png','2019-01-08 09:10:05',1,NULL,NULL),
	(7,2.00,'Listo','Screen Shot 2018-04-19 at 23.33.33.png','2019-01-08 09:10:38',1,NULL,NULL);

/*!40000 ALTER TABLE `files` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(25) COLLATE utf8_bin NOT NULL DEFAULT '',
  `lastname` varchar(30) COLLATE utf8_bin DEFAULT '',
  `username` varchar(45) COLLATE utf8_bin NOT NULL DEFAULT '',
  `password` varchar(60) COLLATE utf8_bin NOT NULL DEFAULT '',
  `authkey` char(50) COLLATE utf8_bin NOT NULL DEFAULT '',
  `status` int(1) NOT NULL,
  `privileges` int(1) NOT NULL,
  `creation_date` datetime NOT NULL,
  `modification_date` datetime DEFAULT NULL,
  `creation_user` int(11) NOT NULL,
  `modification_user` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;

INSERT INTO `user` (`id`, `name`, `lastname`, `username`, `password`, `authkey`, `status`, `privileges`, `creation_date`, `modification_date`, `creation_user`, `modification_user`)
VALUES
	(1,X'457269636B',X'546F72726573',X'657269636B746F72726573383740676D61696C2E636F6D',X'34373530393665643566626661376463306265316231313132633163656231303163373962356232',X'',1,1,'2018-09-18 16:56:00','2019-01-04 00:10:11',1,1);

/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_privileges
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_privileges`;

CREATE TABLE `user_privileges` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `privilege` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `user_privileges` WRITE;
/*!40000 ALTER TABLE `user_privileges` DISABLE KEYS */;

INSERT INTO `user_privileges` (`id`, `privilege`)
VALUES
	(1,'Administrador'),
	(2,'Editor');

/*!40000 ALTER TABLE `user_privileges` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
