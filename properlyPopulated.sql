-- MySQL dump 10.13  Distrib 5.7.22, for Linux (x86_64)
--
-- Host: localhost    Database: crowdmetric
-- ------------------------------------------------------
-- Server version	5.7.22-0ubuntu18.04.1

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
-- Table structure for table `blurts`
--

DROP TABLE IF EXISTS `blurts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blurts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `input_string` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `blurts_user_id_foreign` (`user_id`),
  CONSTRAINT `blurts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blurts`
--

LOCK TABLES `blurts` WRITE;
/*!40000 ALTER TABLE `blurts` DISABLE KEYS */;
INSERT INTO `blurts` VALUES (4,16,'6M52I0X72S6ZVWG','2018-08-05 02:32:30','2018-08-05 02:32:30'),(5,16,'6M52I0X72S6ZVWGF','2018-08-05 02:36:34','2018-08-05 02:36:34'),(6,16,'6M52I0X72S6ZVWGG','2018-08-05 02:36:38','2018-08-05 02:36:38'),(7,16,'NT2QA','2018-08-05 02:36:42','2018-08-05 02:36:42'),(8,16,'1URPA','2018-08-05 02:37:19','2018-08-05 02:37:19'),(9,16,'N','2018-08-05 02:37:32','2018-08-05 02:37:32'),(10,16,'O','2018-08-05 02:37:35','2018-08-05 02:37:35'),(11,16,'P','2018-08-05 02:37:38','2018-08-05 02:37:38');
/*!40000 ALTER TABLE `blurts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (4,'2014_10_12_000000_create_users_table',1),(5,'2014_10_12_100000_create_password_resets_table',1),(6,'2018_08_02_025910_create_blurts_table',1),(7,'2018_08_04_053645_create_second_key',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `secondkeys`
--

DROP TABLE IF EXISTS `secondkeys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `secondkeys` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `blurt_id` int(10) unsigned NOT NULL,
  `secret_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `secondkeys_user_id_foreign` (`user_id`),
  KEY `secondkeys_blurt_id_foreign` (`blurt_id`),
  CONSTRAINT `secondkeys_blurt_id_foreign` FOREIGN KEY (`blurt_id`) REFERENCES `blurts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `secondkeys_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `secondkeys`
--

LOCK TABLES `secondkeys` WRITE;
/*!40000 ALTER TABLE `secondkeys` DISABLE KEYS */;
INSERT INTO `secondkeys` VALUES (7,16,0,'21-19-5-18-28','2018-08-04 12:14:18','2018-08-04 12:14:18'),(8,16,0,'16-1-19-19-23-15-18-4-28','2018-08-04 12:14:18','2018-08-04 12:14:18'),(11,0,4,'20-5-19-20-9-14-16-21-20-19-20-18-9-14-7','2018-08-05 02:32:30','2018-08-05 02:32:30'),(12,0,5,'20-5-19-20-9-14-16-21-20-19-20-18-9-14-7-29','2018-08-05 02:36:34','2018-08-05 02:36:34'),(13,0,6,'20-5-19-20-9-14-16-21-20-19-20-18-9-14-7-30','2018-08-05 02:36:38','2018-08-05 02:36:38'),(14,0,7,'1-12-16-8-1','2018-08-05 02:36:42','2018-08-05 02:36:42'),(15,0,8,'15-13-5-7-1','2018-08-05 02:37:19','2018-08-05 02:37:19'),(16,0,9,'1','2018-08-05 02:37:32','2018-08-05 02:37:32'),(17,0,10,'2','2018-08-05 02:37:35','2018-08-05 02:37:35'),(18,0,11,'3','2018-08-05 02:37:38','2018-08-05 02:37:38');
/*!40000 ALTER TABLE `secondkeys` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nonce` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (16,'70R01','2I51W1ZQA','70R012I51W1ZQA1IHFD0D8F9','2018-08-04 12:14:18','2018-08-05 02:36:26');
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

-- Dump completed on 2018-08-05 15:01:35
