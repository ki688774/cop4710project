-- MySQL dump 10.13  Distrib 9.1.0, for Win64 (x86_64)
--
-- Host: localhost    Database: cop4710project
-- ------------------------------------------------------
-- Server version	9.1.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `cop4710project`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `cop4710project` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `cop4710project`;

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admins` (
  `admin_id` int NOT NULL AUTO_INCREMENT,
  `uid` int NOT NULL,
  PRIMARY KEY (`admin_id`),
  KEY `uid` (`uid`),
  CONSTRAINT `admins_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comments` (
  `uid` int NOT NULL,
  `event_id` int NOT NULL,
  `text` varchar(1024) DEFAULT NULL,
  `rating` int NOT NULL,
  `timestamp` datetime NOT NULL,
  PRIMARY KEY (`uid`,`event_id`),
  KEY `event_id` (`event_id`),
  CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `events` (
  `event_id` int NOT NULL AUTO_INCREMENT,
  `startTime` datetime NOT NULL,
  `endTime` datetime NOT NULL,
  `eventName` varchar(50) NOT NULL,
  `eventDescription` varchar(1024) DEFAULT NULL,
  `lname` varchar(200) NOT NULL,
  PRIMARY KEY (`event_id`),
  KEY `lname` (`lname`),
  CONSTRAINT `events_ibfk_1` FOREIGN KEY (`lname`) REFERENCES `locations` (`lname`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `EventStartsBeforeEnd` CHECK ((`startTime` < `endTime`))
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` VALUES (2,'2000-01-01 00:00:00','2001-01-01 00:00:00','Null Island Party','Party like it\'s 2000! Because it is!','Null Island'),(3,'2001-01-01 00:00:01','2002-01-01 00:00:00','Null Island Party 2','Party like it\'s 2001! Because it is!','Null Island'),(5,'2000-01-01 00:00:00','2001-01-01 00:00:00','Reindeer Party','Thi- this isn\'t an actual party, we\'re trying to get a union for the reindeer.','North Pole');
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `StopOverlappingTimes` BEFORE INSERT ON `events` FOR EACH ROW begin
if (exists (
select *
from events E2
where new.lname=E2.lname AND ((new.startTime >= E2.startTime AND new.startTime <= E2.endTime) OR (new.endTime >= E2.startTime AND new.endTime <= E2.endTime)))) then
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'New event overlapped with another event at the same location.';
end if;
end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `locations`
--

DROP TABLE IF EXISTS `locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `locations` (
  `lname` varchar(200) NOT NULL,
  `address` varchar(200) NOT NULL,
  `longitude` varchar(30) NOT NULL,
  `latitude` varchar(30) NOT NULL,
  PRIMARY KEY (`lname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `locations`
--

LOCK TABLES `locations` WRITE;
/*!40000 ALTER TABLE `locations` DISABLE KEYS */;
INSERT INTO `locations` VALUES ('North Pole','???','90.0 N','0'),('Null Island','???','0','0');
/*!40000 ALTER TABLE `locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `private_events`
--

DROP TABLE IF EXISTS `private_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `private_events` (
  `event_id` int NOT NULL,
  `admin_id` int NOT NULL,
  `super_admin_id` int NOT NULL,
  PRIMARY KEY (`event_id`),
  KEY `admin_id` (`admin_id`),
  KEY `super_admin_id` (`super_admin_id`),
  CONSTRAINT `private_events_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `private_events_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`admin_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `private_events_ibfk_3` FOREIGN KEY (`super_admin_id`) REFERENCES `super_admins` (`super_admin_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `private_events`
--

LOCK TABLES `private_events` WRITE;
/*!40000 ALTER TABLE `private_events` DISABLE KEYS */;
/*!40000 ALTER TABLE `private_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `public_events`
--

DROP TABLE IF EXISTS `public_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `public_events` (
  `event_id` int NOT NULL,
  `super_admin_id` int NOT NULL,
  PRIMARY KEY (`event_id`),
  KEY `super_admin_id` (`super_admin_id`),
  CONSTRAINT `public_events_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `public_events_ibfk_2` FOREIGN KEY (`super_admin_id`) REFERENCES `super_admins` (`super_admin_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `public_events`
--

LOCK TABLES `public_events` WRITE;
/*!40000 ALTER TABLE `public_events` DISABLE KEYS */;
/*!40000 ALTER TABLE `public_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rso_events`
--

DROP TABLE IF EXISTS `rso_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rso_events` (
  `event_id` int NOT NULL,
  `rso_id` int NOT NULL,
  PRIMARY KEY (`event_id`),
  KEY `rso_id` (`rso_id`),
  CONSTRAINT `rso_events_ibfk_1` FOREIGN KEY (`rso_id`) REFERENCES `rsos` (`rso_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `rso_events_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rso_events`
--

LOCK TABLES `rso_events` WRITE;
/*!40000 ALTER TABLE `rso_events` DISABLE KEYS */;
/*!40000 ALTER TABLE `rso_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rso_joins`
--

DROP TABLE IF EXISTS `rso_joins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rso_joins` (
  `uid` int NOT NULL,
  `rso_id` int NOT NULL,
  PRIMARY KEY (`uid`,`rso_id`),
  KEY `rso_id` (`rso_id`),
  CONSTRAINT `rso_joins_ibfk_1` FOREIGN KEY (`rso_id`) REFERENCES `rsos` (`rso_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `rso_joins_ibfk_2` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rso_joins`
--

LOCK TABLES `rso_joins` WRITE;
/*!40000 ALTER TABLE `rso_joins` DISABLE KEYS */;
/*!40000 ALTER TABLE `rso_joins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rsos`
--

DROP TABLE IF EXISTS `rsos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rsos` (
  `rso_id` int NOT NULL AUTO_INCREMENT,
  `admin_id` int NOT NULL,
  PRIMARY KEY (`rso_id`),
  KEY `admin_id` (`admin_id`),
  CONSTRAINT `rsos_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`admin_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rsos`
--

LOCK TABLES `rsos` WRITE;
/*!40000 ALTER TABLE `rsos` DISABLE KEYS */;
/*!40000 ALTER TABLE `rsos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `super_admins`
--

DROP TABLE IF EXISTS `super_admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `super_admins` (
  `super_admin_id` int NOT NULL AUTO_INCREMENT,
  `uid` int NOT NULL,
  PRIMARY KEY (`super_admin_id`),
  KEY `uid` (`uid`),
  CONSTRAINT `super_admins_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `super_admins`
--

LOCK TABLES `super_admins` WRITE;
/*!40000 ALTER TABLE `super_admins` DISABLE KEYS */;
/*!40000 ALTER TABLE `super_admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `uid` int NOT NULL AUTO_INCREMENT,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(256) NOT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-03-28 20:52:42
