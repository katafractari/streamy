-- MySQL dump 10.13  Distrib 5.1.54, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: streammanager
-- ------------------------------------------------------
-- Server version	5.1.54-1ubuntu4

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `outputs`
--
DROP TABLE IF EXISTS `outputs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `outputs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `hostname` varchar(128) NOT NULL,
  `port` int(11) NOT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `selected` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `outputs`
--

LOCK TABLES `outputs` WRITE;
/*!40000 ALTER TABLE `outputs` DISABLE KEYS */;
INSERT INTO `outputs` VALUES (1,'Kuhna','10.2.2.254',6611,'2011-05-14 22:40:19',0),(2,'Rok','localhost',6600,'2011-05-14 22:40:19',1);
/*!40000 ALTER TABLE `outputs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `streams`
--

DROP TABLE IF EXISTS `streams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `streams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(512) NOT NULL,
  `url` varchar(1024) NOT NULL,
  `cover_path` varchar(512) DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=133 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `streams`
--

LOCK TABLES `streams` WRITE;
/*!40000 ALTER TABLE `streams` DISABLE KEYS */;
INSERT INTO `streams` VALUES (124,'Bassdrive','http://blank-tv.de.streams.bassdrive.com:8000',NULL,'2011-05-13 03:45:28'),(125,'Študent','http://kruljo.radiostudent.si:8000/hiq',NULL,'2011-05-13 05:41:55'),(9,'Monkey Radio','http://basu.cockos.com:6969','monkeyradio.jpg','2011-05-08 21:22:55'),(10,'Sofaspace','http://s1.sofaspace.net:8888/stream.ogg',NULL,'2011-05-08 21:22:55'),(11,'Yellow','http://10.2.2.254:8000/yellow',NULL,'2011-05-08 21:22:55'),(12,'Purple','http://10.2.2.254:8000/purple',NULL,'2011-05-08 21:22:55'),(130,'Red','http://10.2.2.254:8000/red',NULL,'2011-05-29 15:31:43'),(131,'Cyan','http://10.2.2.254:8000/cyan',NULL,'2011-05-29 15:32:32'),(75,'Radio Swiss Jazz','http://streaming30.radionomy.com:80/ABC-Lounge',NULL,'2011-05-08 21:22:55'),(74,'unifier-live','http://10.2.2.254:8000/live',NULL,'2011-05-08 21:22:55'),(121,'Green','http://10.2.2.254:8000/green',NULL,'2011-05-13 03:34:52'),(122,'Black','http://10.2.2.254:8000/black',NULL,'2011-05-13 03:38:04'),(69,'Somafm - Groove Salad','http://streamer-ntc-aa08.somafm.com:80/stream/1018',NULL,'2011-05-08 21:22:55'),(68,'MARŠ ','http://online.radiomars.si:8000/radiomars',NULL,'2011-05-08 21:22:55'),(132,'Brown','http://10.2.2.254:8000/brown',NULL,'2011-05-29 15:32:48'),(127,'Blue','http://10.2.2.254:8000/blue',NULL,'2011-05-29 15:20:02'),(128,'White','http://10.2.2.254:8000/white',NULL,'2011-05-29 15:31:06'),(129,'Orange','http://10.2.2.254:8000/orange',NULL,'2011-05-29 15:31:30');
/*!40000 ALTER TABLE `streams` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-06-16  0:42:55
