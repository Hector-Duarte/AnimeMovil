-- MySQL dump 10.13  Distrib 5.5.54, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: animemovil
-- ------------------------------------------------------
-- Server version	5.5.54-0ubuntu0.14.04.1

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
-- Table structure for table `animes`
--

DROP TABLE IF EXISTS `animes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `animes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(11) NOT NULL,
  `title` varchar(300) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `simulcasts` int(11) NOT NULL,
  `sinopsis` varchar(3000) DEFAULT NULL,
  `emision` varchar(35) DEFAULT NULL,
  `nextEpi` varchar(35) DEFAULT NULL,
  `collection` int(11) DEFAULT NULL,
  `message` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `animes`
--

LOCK TABLES `animes` WRITE;
/*!40000 ALTER TABLE `animes` DISABLE KEYS */;
INSERT INTO `animes` VALUES (1,1,'Ao no Exorcist','ao-no-exorcist',1,'El exorcista azul... ','SÃ¡bado 29 de mayo','SÃ¡bado 6 de junio',133,'Este anime tendra una semana de descanso');
/*!40000 ALTER TABLE `animes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `collections`
--

DROP TABLE IF EXISTS `collections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `collections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) DEFAULT NULL,
  `string` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `collections`
--

LOCK TABLES `collections` WRITE;
/*!40000 ALTER TABLE `collections` DISABLE KEYS */;
INSERT INTO `collections` VALUES (1,'Ao no Exorcist','__ao_no_exorcist_sub_espanol__');
/*!40000 ALTER TABLE `collections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crunchyroll`
--

DROP TABLE IF EXISTS `crunchyroll`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crunchyroll` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idEpisodio` int(11) DEFAULT NULL,
  `idCrunchy` int(11) DEFAULT NULL,
  `subRequerido` int(11) NOT NULL,
  `subtitleId` int(11) NOT NULL,
  `streamInfo` varchar(10000) NOT NULL,
  `pass` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idEpisodio` (`idEpisodio`),
  UNIQUE KEY `idCrunchy` (`idCrunchy`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crunchyroll`
--

LOCK TABLES `crunchyroll` WRITE;
/*!40000 ALTER TABLE `crunchyroll` DISABLE KEYS */;
INSERT INTO `crunchyroll` VALUES (1,69,734345,1,260909,'W3sidHlwZSI6ICJydG1wZSIsICJob3N0IjogInJ0bXBlOi8vY3AxNTA3NTcuZWRnZWZjcy5uZXQvb25kZW1hbmQvP2F1dGg9ZGFFYWxiQmRDYU9kemJJYW1jaWM3Y3FhQmRHY25jWGRDYWgtYnpCcVlNLWRIYS1tQkt6dHFIREN2eSZhbXA7YWlmcD0wMDA5JmFtcDtzbGlzdD1jNi9zL3ZlMjc0Mzk3MS92aWRlby5tcDQiLCAicXVhbGl0eSI6ICI0ODBwIiwgImZpbGUiOiAibXA0OmM2L3MvdmUyNzQzOTcxL3ZpZGVvLm1wNCJ9LCB7InR5cGUiOiAicnRtcGUiLCAiaG9zdCI6ICJydG1wZTovL2NwMTUwNzU3LmVkZ2VmY3MubmV0L29uZGVtYW5kLz9hdXRoPWRhRWJvYzJiQWJaY1djcGJSZHJhMGJsYUthY2E5Y0tjSmJ0LWJ6QnFZTi1kSGEtcENIeHJySUNGd3omYW1wO2FpZnA9MDAwOSZhbXA7c2xpc3Q9YzE4L3MvdmUyNzQzOTczL3ZpZGVvLm1wNCIsICJxdWFsaXR5IjogIjcyMHAiLCAiZmlsZSI6ICJtcDQ6YzE4L3MvdmUyNzQzOTczL3ZpZGVvLm1wNCJ9XQ==',1),(3,71,734341,1,258665,'W3sidHlwZSI6ICJydG1wZSIsICJob3N0IjogInJ0bXBlOi8vY3AxNTA3NTcuZWRnZWZjcy5uZXQvb25kZW1hbmQvP2F1dGg9ZGFFYzFibGN1Ym1iWGNvZFJjbmJxYUpiRGJmZGFkS2J5ZE4tYnpCcVlMLWRIYS1vQkV5bHJKQkd3eSZhbXA7YWlmcD0wMDA5JmFtcDtzbGlzdD1jNi9zL3ZlMjcxODEzNS92aWRlby5tcDQiLCAicXVhbGl0eSI6ICI0ODBwIiwgImZpbGUiOiAibXA0OmM2L3MvdmUyNzE4MTM1L3ZpZGVvLm1wNCJ9LCB7InR5cGUiOiAicnRtcGUiLCAiaG9zdCI6ICJydG1wZTovL2NwMTUwNzU3LmVkZ2VmY3MubmV0L29uZGVtYW5kLz9hdXRoPWRhRWI0YlFkX2N5YlFjTWQxY3NjbGFaY2VjR2RaZEhhamQyLWJ6QnFZTC1kSGEtckJGem9zTEJ6d0MmYW1wO2FpZnA9MDAwOSZhbXA7c2xpc3Q9YzYvcy92ZTI3MTgxMzcvdmlkZW8ubXA0IiwgInF1YWxpdHkiOiAiNzIwcCIsICJmaWxlIjogIm1wNDpjNi9zL3ZlMjcxODEzNy92aWRlby5tcDQifV0=',1),(4,72,734339,1,257539,'W3sidHlwZSI6ICJydG1wZSIsICJob3N0IjogInJ0bXBlOi8vY3AxNTA3NTcuZWRnZWZjcy5uZXQvb25kZW1hbmQvP2F1dGg9ZGFFY2ZiQmNEYzFhQ2JaYnNiQmIzY2tjY2NhYWVjOGJpYTMtYnpCY2hBLWRIYS1xQkt5dXNLQUZ2diZhbXA7YWlmcD0wMDA5JmFtcDtzbGlzdD1jMTgvcy92ZTI3MDc2NjkvdmlkZW8ubXA0IiwgInF1YWxpdHkiOiAiNDgwcCIsICJmaWxlIjogIm1wNDpjMTgvcy92ZTI3MDc2NjkvdmlkZW8ubXA0In0sIHsidHlwZSI6ICJydG1wZSIsICJob3N0IjogInJ0bXBlOi8vY3AxNTA3NTcuZWRnZWZjcy5uZXQvb25kZW1hbmQvP2F1dGg9ZGFFYlFhdWE1YUdjTGR3YUxjTmJQYmhkaGE1ZHFkWmROZFQtYnpCY2hBLWRIYS1uQUR3c3FKQ0V4dyZhbXA7YWlmcD0wMDA5JmFtcDtzbGlzdD1jMTgvcy92ZTI3MDc2NzEvdmlkZW8ubXA0IiwgInF1YWxpdHkiOiAiNzIwcCIsICJmaWxlIjogIm1wNDpjMTgvcy92ZTI3MDc2NzEvdmlkZW8ubXA0In1d',0),(6,74,741183,1,0,'0',0),(7,75,741185,1,0,'0',0),(8,76,741187,1,0,'0',0);
/*!40000 ALTER TABLE `crunchyroll` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crunchyroll_anime`
--

DROP TABLE IF EXISTS `crunchyroll_anime`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crunchyroll_anime` (
  `idAnime` int(11) NOT NULL,
  `slugAnimeCrunchy` varchar(100) NOT NULL,
  `calidadPrimaria` varchar(2) NOT NULL,
  `title` varchar(500) NOT NULL,
  `path` varchar(600) DEFAULT NULL,
  `number_start` int(11) NOT NULL,
  PRIMARY KEY (`idAnime`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crunchyroll_anime`
--

LOCK TABLES `crunchyroll_anime` WRITE;
/*!40000 ALTER TABLE `crunchyroll_anime` DISABLE KEYS */;
INSERT INTO `crunchyroll_anime` VALUES (44,'twin-angels-break','SD','Ao No Exorcist EPISODE_NUMBER sub español','ao-no-exorcist-sub-espanol',5),(80,'elegant-yokai-apartment-life','SD','otro anime EPISODE_NUMBER suub español','durarara-sub-espanol',3);
/*!40000 ALTER TABLE `crunchyroll_anime` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `episodios`
--

DROP TABLE IF EXISTS `episodios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `episodios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(11) NOT NULL,
  `title` varchar(250) NOT NULL,
  `slug` varchar(250) NOT NULL,
  `numEpi` int(11) DEFAULT NULL,
  `imgCustom` int(11) DEFAULT NULL,
  `parentId` int(11) NOT NULL,
  `simulcasts` int(11) DEFAULT NULL,
  `message` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `episodios`
--

LOCK TABLES `episodios` WRITE;
/*!40000 ALTER TABLE `episodios` DISABLE KEYS */;
INSERT INTO `episodios` VALUES (52,1,'Ao No Exorcist sub espaÃ±ol ffffffff','ao-no-exorcist-sub-espanol-ffffffff',1,1,44,1,''),(53,1,'Dragon Ball Super 1 Sub EspaÃ±ol','dragon-ball-super-1-sub-espanol',1,1,47,1,''),(54,1,'Ao No Exorcist sub espaÃ±ol dddd','ao-no-exorcist-sub-espanol-dddd',2,1,44,1,''),(55,1,'Ao No Exorcist sub espaÃ±ol ddddaaaaaa','ao-no-exorcist-sub-espanol-ddddaaaaaa',3,1,44,1,''),(56,1,'Ao No Exorcist sub espaÃ±ol ddddaaaaaaaaa','ao-no-exorcist-sub-espanol-ddddaaaaaaaaa',4,1,44,1,''),(57,1,'Ao No Exorcist sub espaÃ±ol test','ao-no-exorcist-sub-espanol-test',5,0,44,1,''),(58,1,'Ao No Exorcist sub espaÃ±ol test','ao-no-exorcist-sub-espanol-test',6,0,44,1,''),(59,1,'Ao No Exorcist sub espaÃ±ol test','ao-no-exorcist-sub-espanol-test',7,0,44,1,''),(60,1,'Ao No Exorcist sub espaÃ±ol test','ao-no-exorcist-sub-espanol-test',8,1,44,1,''),(61,1,'aaaaaaa','aaaaaaa',9,1,44,1,''),(62,1,'teststa','teststa',10,1,44,1,''),(63,1,'haikyuu-s2-sub-espanol','haikyuus2subespanol',2,1,44,1,''),(64,1,'nisekoi','nisekoi',1,1,44,1,''),(65,1,'poke','poke',11,1,44,1,''),(66,1,'poke','poke',12,1,44,1,''),(67,1,'aaa','aaa',13,1,44,1,''),(68,1,'a','a',14,1,44,1,''),(69,0,'Ao No Exorcist 1 sub espaÃ±ol','ao-no-exorcist-1-sub-espanol',1,0,44,1,'Procesando versiÃ³n HD...'),(70,0,'Ao No Exorcist 2 sub espaÃ±ol','ao-no-exorcist-2-sub-espanol',2,0,44,1,'Procesando versiÃ³n HD...'),(71,0,'Ao No Exorcist 3 sub espaÃ±ol','ao-no-exorcist-3-sub-espanol',3,0,44,1,'Procesando versiÃ³n HD...'),(72,0,'Ao No Exorcist 4 sub espaÃ±ol','ao-no-exorcist-4-sub-espanol',4,0,44,1,'Procesando versiÃ³n HD...'),(73,0,'Ao No Exorcist 5 sub espaÃ±ol','ao-no-exorcist-5-sub-espanol',5,0,44,1,'Procesando versiÃ³n HD...'),(74,0,'otro anime 1 suub espaÃ±ol','otro-anime-1-suub-espanol',1,0,80,1,'Procesando versiÃ³n HD...'),(75,0,'otro anime 2 suub espaÃ±ol','otro-anime-2-suub-espanol',2,0,80,1,'Procesando versiÃ³n HD...'),(76,0,'otro anime 3 suub espaÃ±ol','otro-anime-3-suub-espanol',3,0,80,1,'Procesando versiÃ³n HD...'),(77,1,'Ao no Exorcist','ao-no-exorcist',1,0,1,NULL,'Este anime tendra una semana de descanso'),(78,1,'Ao no Exorcist','ao-no-exorcist',1,0,1,1,'Este anime tendra una semana de descanso');
/*!40000 ALTER TABLE `episodios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `favoritos`
--

DROP TABLE IF EXISTS `favoritos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `favoritos` (
  `id` int(11) NOT NULL,
  `nodeId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favoritos`
--

LOCK TABLES `favoritos` WRITE;
/*!40000 ALTER TABLE `favoritos` DISABLE KEYS */;
INSERT INTO `favoritos` VALUES (1,1),(9,2),(9,44);
/*!40000 ALTER TABLE `favoritos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `files`
--

DROP TABLE IF EXISTS `files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `status` int(11) DEFAULT NULL,
  `videoCodec` varchar(15) DEFAULT NULL,
  `audioCodec` varchar(15) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `width` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `md5` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `files`
--

LOCK TABLES `files` WRITE;
/*!40000 ALTER TABLE `files` DISABLE KEYS */;
INSERT INTO `files` VALUES (52,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(53,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(54,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(55,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(56,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(57,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(58,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(59,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(60,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(61,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(62,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(63,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(64,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(65,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(66,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(67,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(68,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `generos`
--

DROP TABLE IF EXISTS `generos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `generos` (
  `idAnime` int(11) NOT NULL,
  `idGenero` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `generos`
--

LOCK TABLES `generos` WRITE;
/*!40000 ALTER TABLE `generos` DISABLE KEYS */;
INSERT INTO `generos` VALUES (46,1),(46,15),(46,18),(47,1),(47,8),(47,14),(44,1),(44,5);
/*!40000 ALTER TABLE `generos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `user_level` int(1) DEFAULT NULL,
  `ip` varchar(60) DEFAULT NULL,
  `token` varchar(100) DEFAULT NULL,
  `expire` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES (2,9,0,'187.253.120.188','CSO7DL9zFboO2jiOShvDFgJjEBLwNSbELd9PYQwxNjU=',1502221553),(3,9,0,'187.253.120.188','UqbckdkVSRP+4WySzo7djUVeaWW15z8DkYnVQUq14Ps=',1502221781),(4,9,0,'187.253.120.188','g4rUbPKfcUKLmlaxr4vyH+w049evUHPK78YwMrVY6gg=',1502395048),(5,9,0,'187.253.120.188','oLjKGgXvErlAIM35mkbV+fG8uLq4JbSkc/pqtU0J1yM=',1502395067),(6,9,0,'187.253.120.188','kgNZDex6V5zn3BOMUY5yo49IsmihoMpgQ20b4l5Vbkc=',1502839046),(7,9,0,'187.253.120.188','FBm4L2ajEGfThuX5JVzooClLoLFOCvjjqdyFUMJvmyg=',1502843998),(8,9,0,'187.253.120.188','1JUnQs5O2+bZrsLBDhLcE54ZZk0fwO/ys6hoGsXwX0g=',1502910332),(9,9,0,'187.253.120.188','igvIDeCc3Gd3Qumb2xR0tfbeFsBpZzTv4QGkX/AM8wA=',1505175234),(10,9,0,'187.253.120.188','h1Tg1T5Ch5i1gVS5Ux5gumdBB/875DBykzCjRDGaxsQ=',1505175349);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stream`
--

DROP TABLE IF EXISTS `stream`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stream` (
  `id` int(11) NOT NULL,
  `path` varchar(300) NOT NULL,
  `file` varchar(100) NOT NULL,
  `nora` varchar(200) DEFAULT NULL,
  `cloud` varchar(200) DEFAULT NULL,
  `rin` varchar(200) DEFAULT NULL,
  `achede` varchar(200) DEFAULT NULL,
  `photo` varchar(200) DEFAULT NULL,
  `vizard` varchar(200) DEFAULT NULL,
  `copy` varchar(200) DEFAULT NULL,
  `minh` varchar(200) DEFAULT NULL,
  `fire` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stream`
--

LOCK TABLES `stream` WRITE;
/*!40000 ALTER TABLE `stream` DISABLE KEYS */;
INSERT INTO `stream` VALUES (30,'test-animemovil-v2','2','','','','','','1','','',NULL),(31,'test-animemovil-v2','2','','','','','','1','','',NULL),(32,'test-animemovil-v2','2',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(33,'test-animemovil-v2','2',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(46,'test-animemovil-v2','2',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(47,'test-animemovil-v2','2',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(48,'test-animemovil-v2','2',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(50,'test-animemovil-v2','2',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(51,'test-animemovil-v2','2',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(52,'test-animemovil-v2','2','0B7s0kjjiwZQcc0FaaTZKOUIweWc','test','AZVdMXZxffxTLKwu5','xHIIwRxUQAMrRwl9xUTaW5C72Ek5mKkMwDlr5wvzZao','5R1Ccz3Ju7nkbe4J6','3','5938104830','1123758212','27fy5qdf8m3apc7'),(53,'test-animemovil-v2','2','0BxJN9MzkaV15cXNUNjd5dG90Smc','n6QZ7cmkTXMgdw4-_XNpN4','B9rHWu4tUKU9Vjs59','7jGL1soJvoT57aO8vkUnVDlklr558NlIz4HCoqn2J0n','B9rHWu4tUKU9Vjs59','1','5299970872','859785979','2uh4zqzy1fe69h4'),(54,'test-animemovil-v2','2','0B7s0kjjiwZQcNmpRbDJ2VldwVUE','test','e3QMz6vk7YqBU8C47','fbpDBrlpHnnFwys0nif9WVQgPpjYzzAWLHiWKmm1TUz','oZRsEevmU7Nicd939','1','5938104787','1123761978','ptwgcr57nqrtb8g'),(55,'test-animemovil-v2','2','0B7s0kjjiwZQcYXZJRzRRQzhpZEE','test','zJuQXCVzrkLAX5kf6','2B2ptdt4Pujm8jTsTyhh03M7WYnyJh58RAKHZzH3DeN','P3Qs1i8dUndkhH1t9','3','5938104778','1123758209','c792zaawk1xc85c'),(56,'test-animemovil-v2','2','0B7s0kjjiwZQccFZpa0xnT01pdGM','test','3ghbGGZ1YRyqxfeF8','iXrF393F1m5SQTl5LczIeoMibvInHx3rJ5H54sZlolz','n4ZWn31e52ipMXPc6','3','5938104838','1123758216','ebil3r5hy3pc6l2'),(60,'test-animemovil-v2','9','0B7s0kjjiwZQcc3lRRW1rYlhhcjg','','aAe4MYqSCCAYHhdt8','VxqHkPUnGDB78GWCV1KUdiD2dkpjyU0c6HAifQBbfO6','E2SJqkEYDJwrDAFC7','3','5941142766','1125372426','43tf42f9mt63b8h'),(61,'test-animemovil-v2','9','0B7s0kjjiwZQceVdEcFFiYkZfS28',NULL,NULL,NULL,NULL,'3','5944580628','1127359593','rwguwsov3nvz157'),(62,'test-animemovil-v2','9','0B7s0kjjiwZQcRmpOUmUxbHJHSzA',NULL,NULL,'v0fbAngGGR9NrT9a9NJeAYmBwvKDE2HivVMdklgPdUK',NULL,'3','5944596469','1127370607','r5i5le2650z1tbr'),(63,'test-animemovil-v2','2','0B7s0kjjiwZQcUjZNeDhPem9FT1U',NULL,NULL,NULL,NULL,'3','5944607285','1127378963','ng91lsv7xgk3ree'),(64,'test-animemovil-v2','3','0B7s0kjjiwZQcaUNoQ3NmYXdLLWs',NULL,NULL,'hD55c6Y5myg37QCRtS2zpS6rZWIhDlxT1SaGqc1cAw',NULL,'3','5944610450','1127381746','lzuzebbbjur5zm3'),(65,'test-animemovil-v2','120','0B7s0kjjiwZQcVHF4a2cyekRVWDg',NULL,NULL,'U9GwvNSZPbyHkiXNnFnPdy2OJDPO1lyyFeGfYuLlPCy',NULL,'3','5944611861','1127405893','sttwdspnkfidpx6'),(66,'test-animemovil-v2','170','0B7s0kjjiwZQcbGZlQ1d0SUhyNzQ',NULL,NULL,NULL,NULL,'3','5944611856','1127410113','nf89sn7iq5qevak'),(67,'test-animemovil-v2','171','0B7s0kjjiwZQcQnhUYnNOQlVlVWs',NULL,NULL,'qYNTOGlsXMQQAxiaY8VxDJffuWLLEWZrQ7dT3djqQnC',NULL,'3','5944612084','1127410974','9jobgxzjkiovd9n'),(68,'test-animemovil-v2','3','0B7s0kjjiwZQcSDBhZXZVMkVIUFk',NULL,NULL,NULL,NULL,'3','5971979089','1127413633','6mde6pk41ao4n4o'),(69,'test-animemovil-v2','1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(70,'test-animemovil-v2','2',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(71,'test-animemovil-v2','3',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(72,'test-animemovil-v2','4',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(73,'test-animemovil-v2','5',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(74,'test-animemovil-v2','1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(75,'test-animemovil-v2','2',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(76,'test-animemovil-v2','3',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `stream` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(30) NOT NULL,
  `level` int(11) NOT NULL,
  `mail` varchar(50) NOT NULL,
  `password` char(128) NOT NULL,
  `salt` char(128) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user` (`user`),
  UNIQUE KEY `mail` (`mail`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (9,'jarri1999',0,'hectordanielunsc@gmail.com','5c905b40fea57b4470a98cebc613479f2f52671af22cbdba5aecd56520a91c8846b47d68653e7863daf3c41cc5555aee0eb3f07d101b1ad15551a6c36c16afdc','7f41b41fe8aed50ad5de3fb8b93d726de828358075fb94bcc2949744bd356590ba9e867a9c4665d37197ba1ba1775cdefc97ebf5fd5f87a044dfc520f13b110d'),(10,'daniel',0,'daniel1999@gmail.com','c3c6b693a10b4a78f14ceb9c2f5fcd8e143450c14bfdb9be638864e864f78d92d87ca0188e459ae08482cf50250f09953432610bebe33f2e735f43c6c725a3c0','db8cf8c497b61754e1e4c5f4e5fbeeee91ce41bf2a3819a5e3294324d94f3875a002749d27c74c1d2859353a6d144d456084b31f7c4adfc5d4701f9efe986470');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-08-31  0:16:49
