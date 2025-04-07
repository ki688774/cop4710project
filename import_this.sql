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
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comments` (
  `uid` int NOT NULL,
  `event_id` int NOT NULL,
  `text` varchar(4096) DEFAULT NULL,
  `rating` int NOT NULL,
  `timestamp` datetime NOT NULL,
  PRIMARY KEY (`uid`,`event_id`),
  KEY `event_id` (`event_id`),
  CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `RatingBetween1And5` CHECK (((`rating` >= 1) and (`rating` <= 5)))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` VALUES (1,8,'Hopefully y\'all are able to come. I brought moss for everyone!',5,'2025-04-04 15:21:13');
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `update_total_rating_insert` AFTER INSERT ON `comments` FOR EACH ROW UPDATE events E
SET E.total_rating = (
    SELECT AVG(rating)
    FROM comments C
    WHERE C.event_id=E.event_id
)
WHERE E.event_id=new.event_id */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `update_total_rating_update` AFTER UPDATE ON `comments` FOR EACH ROW UPDATE events E
SET E.total_rating = (
    SELECT AVG(rating)
    FROM comments C
    WHERE C.event_id=E.event_id
)
WHERE E.event_id=new.event_id */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `update_total_rating_delete` AFTER DELETE ON `comments` FOR EACH ROW UPDATE events E
SET E.total_rating = (
    SELECT AVG(rating)
    FROM comments C
    WHERE C.event_id=E.event_id
)
WHERE E.event_id=old.event_id */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `events` (
  `event_id` int NOT NULL AUTO_INCREMENT,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `event_name` varchar(50) NOT NULL,
  `event_description` varchar(1024) NOT NULL,
  `contact_phone` varchar(20) NOT NULL,
  `contact_email` varchar(256) NOT NULL,
  `total_rating` decimal(3,2) DEFAULT NULL,
  `location_id` int NOT NULL,
  PRIMARY KEY (`event_id`),
  KEY `location_id` (`location_id`),
  CONSTRAINT `events_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `locations` (`location_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `EventStartsBeforeEnd` CHECK ((`start_time` < `end_time`))
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` VALUES (8,'2025-12-23 00:00:00','2026-01-01 00:00:00','Reindeer Strike and Unionization','No more will Santa hold out on our moss and dental benefits! We organize for a better workshop, now and forevermore!','(555) 123-4567','rudolph.reindeer@proton.me',5.00,3),(9,'2025-02-01 20:00:00','2026-02-02 00:00:00','Party To Celebrate Us Getting Dental Benefits','WE GOT THE DENTAL LET\'S GOOOOOOOOOO\r\nWE\'VE GOT MOSS. WE\'VE GOT VIDEO GAMES. LET\'S MAKE THIS THE BEST NIGHT OF OUR LIVES.','(555) 123-4567','rudolph.reindeer@proton.me',NULL,6);
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
where new.location_id=E2.location_id AND ((new.start_time >= E2.start_time AND new.start_time <= E2.end_time) OR (new.end_time >= E2.start_time AND new.end_time <= E2.end_time)))) then
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
  `location_id` int NOT NULL AUTO_INCREMENT,
  `location_name` varchar(256) NOT NULL,
  `address` varchar(256) NOT NULL,
  `longitude` decimal(9,6) NOT NULL,
  `latitude` decimal(8,6) NOT NULL,
  PRIMARY KEY (`location_id`),
  CONSTRAINT `LatitudeClamp` CHECK (((`latitude` >= -(90)) and (`latitude` <= 90))),
  CONSTRAINT `LongitudeClamp` CHECK (((`longitude` >= -(180)) and (`longitude` <= 180)))
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `locations`
--

LOCK TABLES `locations` WRITE;
/*!40000 ALTER TABLE `locations` DISABLE KEYS */;
INSERT INTO `locations` VALUES (3,'Santa\'s Workshop','123 North Pole Rd.',0.000000,90.000000),(6,'Rudolph\'s House','1009 North Pole Rd.',0.000000,90.000000);
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
  `rso_id` int NOT NULL,
  `university_id` int NOT NULL,
  PRIMARY KEY (`event_id`),
  KEY `rso_id` (`rso_id`),
  KEY `university_id` (`university_id`),
  CONSTRAINT `private_events_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `private_events_ibfk_2` FOREIGN KEY (`rso_id`) REFERENCES `rsos` (`rso_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `private_events_ibfk_3` FOREIGN KEY (`university_id`) REFERENCES `universities` (`university_id`) ON DELETE CASCADE ON UPDATE CASCADE
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
  `university_id` int NOT NULL,
  PRIMARY KEY (`event_id`),
  KEY `university_id` (`university_id`),
  CONSTRAINT `public_events_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `public_events_ibfk_2` FOREIGN KEY (`university_id`) REFERENCES `universities` (`university_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `public_events`
--

LOCK TABLES `public_events` WRITE;
/*!40000 ALTER TABLE `public_events` DISABLE KEYS */;
INSERT INTO `public_events` VALUES (8,1),(9,1);
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
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `activate_rso` AFTER INSERT ON `rso_joins` FOR EACH ROW BEGIN
    IF (5 <= (SELECT COUNT(*) FROM rso_joins J WHERE J.rso_id=new.rso_id))
    THEN
        UPDATE rsos R SET active=1 WHERE R.rso_id=new.rso_id;
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `deactivate_rso` AFTER DELETE ON `rso_joins` FOR EACH ROW BEGIN
    IF (5 > (SELECT COUNT(*) FROM rso_joins J WHERE J.rso_id=old.rso_id))
    THEN
        UPDATE rsos R SET active=0 WHERE R.rso_id=old.rso_id;
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `rsos`
--

DROP TABLE IF EXISTS `rsos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rsos` (
  `rso_id` int NOT NULL AUTO_INCREMENT,
  `university_id` int NOT NULL,
  `rso_name` varchar(256) NOT NULL,
  `admin_id` int NOT NULL,
  `active` bit(1) NOT NULL,
  PRIMARY KEY (`rso_id`),
  KEY `admin_id` (`admin_id`),
  KEY `rsos_ibfk_2` (`university_id`),
  CONSTRAINT `rsos_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`uid`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `rsos_ibfk_2` FOREIGN KEY (`university_id`) REFERENCES `universities` (`university_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rsos`
--

LOCK TABLES `rsos` WRITE;
/*!40000 ALTER TABLE `rsos` DISABLE KEYS */;
/*!40000 ALTER TABLE `rsos` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = cp850 */ ;
/*!50003 SET character_set_results = cp850 */ ;
/*!50003 SET collation_connection  = cp850_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `delete_rso_locations` BEFORE DELETE ON `rsos` FOR EACH ROW DELETE FROM locations L WHERE ( EXISTS (
SELECT *
FROM events E, private_events P, rso_events R
WHERE L.location_id = E.location_id 
AND ((E.event_id = R.event_id AND R.rso_id = old.rso_id) 
OR (E.event_id = P.event_id AND P.rso_id = old.rso_id))
)
) */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `universities`
--

DROP TABLE IF EXISTS `universities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `universities` (
  `university_id` int NOT NULL AUTO_INCREMENT,
  `university_domain` varchar(256) NOT NULL,
  `university_name` varchar(256) NOT NULL,
  `location_id` int NOT NULL,
  `super_admin_id` int NOT NULL,
  PRIMARY KEY (`university_id`),
  UNIQUE KEY `university_domain` (`university_domain`),
  KEY `super_admin_id` (`super_admin_id`),
  KEY `location_id` (`location_id`),
  CONSTRAINT `universities_ibfk_1` FOREIGN KEY (`super_admin_id`) REFERENCES `users` (`uid`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `universities_ibfk_2` FOREIGN KEY (`location_id`) REFERENCES `locations` (`location_id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `universities`
--

LOCK TABLES `universities` WRITE;
/*!40000 ALTER TABLE `universities` DISABLE KEYS */;
INSERT INTO `universities` VALUES (1,'northpole.org','Santa\'s Workshop',3,1),(2,'coal.org','The Coal Bringers',4,6);
/*!40000 ALTER TABLE `universities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `uid` int NOT NULL AUTO_INCREMENT,
  `university_id` int DEFAULT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `email` varchar(256) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(256) NOT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `users_ibfk_1` (`university_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`university_id`) REFERENCES `universities` (`university_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,1,'Rudolph','Reindeer','rednose@northpole.org','RedNose1939','$2y$10$DP.R71h2M7O6NJ/M/foMjOU2gWu837wdvkx5MPOHz4kDd0uCzg1ua'),(2,1,'Prancer','Reindeer','prancer@northpole.org','xXPrancerXx','$2y$10$2jSW1yPWAMi4mTsmnv12keBqcTjg6W.e0v.sGYgAxY95IQjWsF.4G'),(4,1,'Donner','Reindeer','donner@northpole.org','KingCaribou','$2y$10$qHMYrANcpdUmicI3RFXpT.VckOmSb/vw5FWfgGI7tMLJFnw/GXntK'),(6,2,'Krampus','Goat','krampus@coal.org','CoalBringer','$2y$10$gjeBqspfJuLnv9afK13l0.sg.a1CvMijP0Uge5hA73lsTkCCTdGG6'),(7,2,'Yule','Cat','yuleeee@coal.org','ClothesCat','$2y$10$8gPK52VVVJnqGMbBG8ybHOMHJSLHjArHAFOwElGrfwWzpBUsdMrG2'),(10,1,'Blitzen','Reindeer','blitzen@northpole.org','blitzzzzz','$2y$10$lRxd5J0QMvYqA5ouhM4eS.TW5HtXffZciiIzFu28BXE5TNb95kk4e'),(11,1,'Cupid','Reindeer','cupid@northpole.org','TwelveTwentyfive','$2y$10$tz9S6yRz8FCsdjhEVvx9weITDJhZrPC7EgkRLLnLV1mcRBuMW7cZG'),(12,1,'Vixen','Reindeer','vixen@northpole.org','0u7f0x3d','$2y$10$w4CfBXXrX/MlH1nGtlfk.u21Al6yub3Y87D59GSO3diFKGRzI9C42');
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

-- Dump completed on 2025-04-07  0:37:02
