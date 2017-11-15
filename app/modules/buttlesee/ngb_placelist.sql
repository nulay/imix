# SQL-Front 5.1  (Build 4.16)

/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE */;
/*!40101 SET SQL_MODE='STRICT_TRANS_TABLES,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES */;
/*!40103 SET SQL_NOTES='ON' */;


# Host: localhost    Database: shop6
# ------------------------------------------------------
# Server version 5.0.15-nt

#
# Source for table ngb_gamebs
#

DROP TABLE IF EXISTS `ngb_gamebs`;
CREATE TABLE `ngb_gamebs` (
  `Id` int(11) NOT NULL auto_increment,
  `type` int(11) default NULL,
  `uid` int(11) default NULL,
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ngb_placelist`;
CREATE TABLE `ngb_placelist` (
  `Id` int(11) NOT NULL auto_increment,
  `uid` int(11) default NULL,
  `place` varchar(255) default NULL,
  `name` varchar(255) default NULL,
  `plgame` varchar(255) default '[[0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0]]',
  `idgame` int(11) default NULL,
  `hit` text,
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;


