-- MySQL dump 10.13  Distrib 8.0.42, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: registros
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `equipo_usuarios`
--

DROP TABLE IF EXISTS `equipo_usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `equipo_usuarios` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `usuario` varchar(255) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `equipo_usuarios`
--

LOCK TABLES `equipo_usuarios` WRITE;
/*!40000 ALTER TABLE `equipo_usuarios` DISABLE KEYS */;
INSERT INTO `equipo_usuarios` VALUES (32,'ellibertador10','Ngso2025*','2025-10-16 23:45:16','2025-12-26 21:03:19'),(33,'ellibertador11','NGSO2025***','2025-10-16 23:45:40','2025-10-16 23:45:40'),(34,'ellibertador13','Ngso2025*','2025-10-16 23:45:53','2025-10-16 23:45:53'),(35,'ellibertador15','Ngso2024.*','2025-10-16 23:46:08','2025-12-09 20:13:45'),(36,'ellibertador16','Ngso2025++','2025-10-16 23:46:25','2025-12-26 21:24:30'),(37,'ellibertador17','Ngso2020*','2025-10-16 23:46:39','2025-12-26 20:59:25'),(38,'ellibertador20','Aa15072025.*','2025-10-16 23:46:53','2025-10-16 23:46:53'),(39,'ellibertador22','NGSO2025*+','2025-10-16 23:47:11','2025-10-16 23:47:11'),(40,'ellibertador24','Ngso2025*+','2025-10-16 23:47:31','2025-10-16 23:47:31'),(41,'ellibertador27','ngso2025**','2025-10-16 23:48:03','2025-10-16 23:48:03'),(42,'ellibertador3','Ngso2025*-+','2025-10-16 23:48:16','2025-10-16 23:48:16'),(43,'ellibertador32','Ngso.2025***','2025-10-16 23:49:04','2025-10-16 23:49:04'),(44,'ellibertador33','Ngso2024**','2025-10-16 23:49:24','2025-10-16 23:49:24'),(45,'ellibertador34','Elibertador34**','2025-10-16 23:49:35','2025-10-16 23:49:35'),(46,'ellibertador35','Ngso2025*','2025-10-16 23:49:53','2025-10-16 23:49:53'),(47,'ellibertador36','NGSO2025++','2025-10-16 23:50:04','2025-10-16 23:50:04'),(48,'ellibertador46','ngso2026//','2025-10-16 23:50:32','2025-10-16 23:50:32'),(49,'ellibertador5','Ngso2025/*','2025-10-16 23:50:44','2025-10-16 23:50:44'),(50,'ellibertador50','NGSO2025*++','2025-10-16 23:50:56','2025-12-26 20:53:54'),(51,'ellibertador52','Ngso2024*+','2025-10-16 23:51:07','2025-10-16 23:51:07'),(52,'ellibertador54','NGSO2024*+','2025-10-16 23:51:17','2025-10-16 23:51:17'),(53,'ellibertador57','NGSo2025','2025-10-16 23:51:31','2025-10-16 23:51:31'),(54,'ellibertador6','Ngso2023**','2025-10-16 23:51:42','2025-10-16 23:51:42'),(55,'ellibertador60','NGSO2024*+','2025-10-16 23:51:52','2025-10-16 23:51:52'),(56,'ellibertador61','NGSO2024*+','2025-10-16 23:52:13','2025-10-16 23:52:13'),(57,'ellibertador7','Ngso2025','2025-10-16 23:52:26','2025-12-26 21:12:28'),(58,'ellibertados38','Ngso2025***','2025-10-16 23:52:51','2025-12-26 20:55:58'),(59,'ellibertador19','Ngso2022*','2025-10-16 23:53:15','2025-10-16 23:54:22'),(60,'ellibertador47','Ngso2024*','2025-10-16 23:53:26','2025-10-16 23:54:40'),(61,'ellibertador58','NGSO2024*+','2025-10-16 23:53:37','2025-10-16 23:55:04'),(62,'ellibertador62','NGSO2024*+','2025-10-16 23:53:49','2025-10-16 23:55:29'),(63,'coord_prejuridico1','Calidad123**','2025-10-17 00:16:50','2025-10-17 03:24:59'),(64,'coord_prejuridico2','Ngso2025-*','2025-10-17 00:17:11','2025-10-17 03:23:39'),(65,'supervisor cartera','NGSO2025*+','2025-10-17 00:17:28','2025-10-17 03:22:57'),(66,'lidercartera','NGSO2025+-','2025-10-17 01:56:53','2025-10-17 03:24:11'),(67,'ellibertador37','Ngso2025.*','2025-10-17 02:40:42','2025-10-17 02:40:42'),(68,'ellibertador25','Ngso2025.*','2025-10-17 02:41:10','2025-10-17 02:41:10'),(69,'ellibertador28','Ngso2025.*','2025-10-17 02:41:38','2025-10-17 02:41:38'),(70,'ellibertador2','Ngso2025.*','2025-10-17 02:42:20','2025-10-17 02:42:20'),(71,'ellibertador12','Ngso2025.*','2025-10-17 02:42:42','2025-10-17 02:42:42'),(72,'ellibertador49','Ngso2025.*','2025-10-17 02:43:08','2025-10-17 02:43:08'),(73,'ellibertador30','Ngso2026*','2025-10-17 02:46:24','2025-10-17 02:46:24'),(74,'ellibertador55','NGSO2024*+','2025-10-17 02:59:42','2025-10-17 02:59:42'),(76,'ellibertador9','Ngso2026*+','2025-12-05 00:03:14','2025-12-26 21:18:40'),(77,'ellibertador21','Ngso2023*','2025-12-05 00:03:23','2025-12-05 00:03:23'),(78,'ellibertador43','Ngso2025*+','2025-12-05 00:03:33','2025-12-05 00:03:33'),(79,'ellibertador51','NGSO2024*+','2025-12-05 00:03:43','2025-12-05 00:03:43'),(80,'ellibertador53','NGSO2024*+','2025-12-05 00:03:52','2025-12-05 00:03:52'),(81,'ellibertador56','NGSO2024*+','2025-12-05 00:04:12','2025-12-05 00:04:12'),(82,'ellibertador59','NGSO2024*+','2025-12-05 00:04:22','2025-12-05 00:04:22'),(83,'ellibertador63','NGSO2024*+','2025-12-05 00:04:32','2025-12-05 00:04:32'),(84,'ellibertador64','NGSO2024*+','2025-12-05 00:04:43','2025-12-05 00:04:43'),(85,'ellibertador65','NGSO2024*+','2025-12-05 00:04:53','2025-12-05 00:04:53'),(87,'ellibertador66','NGSO2024*+','2025-12-05 00:05:03','2025-12-05 00:05:03'),(88,'ellibertador67','NGSO2024*+','2025-12-05 00:05:28','2025-12-05 00:05:28'),(89,'ellibertador68','NGSO2024*+','2025-12-05 00:05:39','2025-12-05 00:05:39'),(90,'ellibertador69','NGSO2024*+','2025-12-05 00:05:50','2025-12-05 00:05:50'),(91,'ellibertador70','NGSO2024*+','2025-12-05 00:06:01','2025-12-05 00:06:01');
/*!40000 ALTER TABLE `equipo_usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `huellas`
--

DROP TABLE IF EXISTS `huellas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `huellas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `usuario` varchar(255) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `nombre_usuario` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `huellas`
--

LOCK TABLES `huellas` WRITE;
/*!40000 ALTER TABLE `huellas` DISABLE KEYS */;
INSERT INTO `huellas` VALUES (22,'1129508557','Ngso2026','KAREN MARGARITA CASTELLAR IRIARTE','2025-10-15 02:59:46','2025-12-04 23:30:49'),(23,'1001216235','Ngso2025','LUIS ARMANDO LEON CAÑON','2025-10-15 03:00:05','2025-12-04 23:31:02'),(24,'52879254','Ngso2026','NANCY ALEJANDRA GONZALEZ','2025-10-15 03:00:24','2025-12-26 21:18:40'),(25,'1033684323','Colombia86','LUZ ADRIANA LINARES LAGOS','2025-10-15 03:00:38','2025-12-29 17:54:35'),(26,'1000250399','Ngso2025++','JULIETH ALEXANDRA CASTIBLANCO RINCON','2025-10-15 03:00:50','2025-10-15 03:00:50'),(27,'1103111733','Ngso2025*','ISLENA PAOLA ACOSTA SALGADO','2025-10-15 03:01:12','2025-12-17 03:06:17'),(28,'1001275619','Ngso2025+*','ANGIE LORENA HERNANDEZ CASTIBLANCO','2025-10-15 03:01:22','2025-10-15 03:01:22'),(30,'1138074284','Ngso2026*','NATALIA INES NISPERUZA SANCHEZ','2025-10-15 03:01:49','2025-10-15 03:11:35'),(31,'1072190152','Ngso2024+','JEIMY ANDREA RODRIGUEZ CAÑON','2025-10-17 00:00:48','2025-10-17 00:19:52'),(32,'1033685482','Ngso2026+++','VALERY BRILLIT RINCON LINARES','2025-10-17 00:01:28','2025-12-26 21:12:28'),(33,'1001116838','Ngso2027','JOHAN CAMILO AVILA BOHORQUEZ','2025-10-17 00:01:55','2025-12-26 21:05:05'),(34,'1000727404','Ngso2025*-','JUAN MANUEL BERMUDEZ CORREA','2025-10-17 00:02:21','2025-10-17 00:21:12'),(35,'52756032','Naranjo321*','DIANA ROCIO NARANJO HERNANDEZ','2025-10-17 00:02:56','2025-10-17 00:21:33'),(36,'1024593276','Yepes2025','CAROL TATIANA YEPEZ BETANCOURTH','2025-10-17 00:03:46','2025-10-17 00:21:50'),(37,'1016106935','Ngso2025***','CRISTIAN DAVID DIAZ MELO','2025-10-17 00:04:11','2025-12-26 21:08:59'),(38,'1018441492','Ngso2025*+','ANGELA PATRICIA DIAZ FERNANDEZ','2025-10-17 00:04:33','2025-12-26 20:53:55'),(39,'1019073284','Ngso2025++','LUISA FERNANDA BELTRAN GUESCOT','2025-10-17 00:04:58','2025-10-17 00:22:49'),(40,'1000732395','Ngso2025.*','MARIA XIMENA ROSAS MATAPI','2025-10-17 00:05:19','2025-12-26 20:46:02'),(41,'1000218098','Colombia23**','MARIA FERNANDA DUARTE MAPE','2025-10-17 00:06:26','2025-10-17 00:23:25'),(42,'1001286177','Ngso2025','MICHEL VANESA MUÑOZ GUTIERREZ','2025-10-17 00:06:48','2025-10-17 00:23:43'),(43,'1018439309','Ngso2025*+','GINNA ALEJANDRA PEREZ CIFUENTES','2025-10-17 00:07:07','2025-10-17 00:24:01'),(44,'52837437','Ngso2024*','IRMA ROSA DIAZ BARRETO','2025-10-17 00:07:25','2025-10-17 00:24:18'),(45,'1032677388','NgsO2025*','NICOL DALLAN DOMINGUEZ CARRASCO','2025-10-17 00:07:44','2025-10-17 00:24:38'),(46,'1000338067','Ngso2025++','MARIA CAMILA MILLAN CEDENO','2025-10-17 00:08:01','2025-10-17 00:24:59'),(47,'1002525594','Ngso2026*','SANDI MARCELA BURGOS PINEDA','2025-10-17 00:08:22','2025-12-26 21:00:56'),(48,'1013689282','Ngso2025','HARVY ANYINZAN TRUJILLO CAMARGO','2025-10-17 00:08:41','2025-10-17 00:25:35'),(49,'1012434013','Ngso2025.','MARIA ALEJANDRA ACOSTA BLANCO','2025-10-17 00:09:01','2025-12-26 21:06:49'),(50,'52889704','NGso2025*','SANDRA MILENA HERNANDEZ CORREDOR','2025-10-17 00:09:23','2025-12-04 23:33:41'),(51,'1002208098','Chicago21','MARELEIMYS JUDITH CARO BOLAÑO','2025-10-17 00:09:41','2025-10-17 00:26:29'),(52,'1057014128','Ngso2025*','ANDRES FELIPE ARGUELLO ORJUELA','2025-10-17 00:10:03','2025-12-26 21:26:00'),(53,'1023973889','Ngso2025+','JOHAN STEVEN PINEDA ROMERO','2025-10-17 00:10:21','2025-12-11 18:58:52'),(54,'1000225422','Ngso2026','LAURA DANIELA ZAMBRANO SOLIS','2025-10-17 00:10:38','2025-12-26 21:20:19'),(55,'1233891770','Ngso2025','LAURA BEATRIZ MOREIRA GARCES','2025-10-17 00:10:59','2025-10-17 00:27:46'),(56,'1000774584','Milucita02','RAFAEL STEVEN ZABALA NORIEGA','2025-10-17 00:11:19','2025-12-26 20:41:04'),(57,'1014278390','Ngso2026*','AMMY VIVIANA CASAS ROZO','2025-10-17 00:11:43','2025-12-26 20:43:20'),(58,'1000626370','Ngso2025','LUISA FERNANDA LIZCANO GARAVITO','2025-10-17 00:12:02','2025-10-17 00:28:42'),(59,'1097332999','Ngso2025','YERFIN DISNEY ARDILA OTALORA','2025-10-17 00:12:18','2025-10-17 00:28:58'),(61,'1000625517','Ngso2025','PAULA ALEJANDRA NIÑO PEÑA','2025-10-17 00:12:47','2025-10-17 00:29:49'),(63,'1025527753','Ngso2024*+','GABRIELA MORENO HERNANDEZ','2025-11-13 02:07:44','2025-12-16 17:40:29'),(64,'1014284618','Ngso2027','YERITSON ADRIAN VEGA ACERO','2025-12-04 23:51:59','2025-12-04 23:51:59');
/*!40000 ALTER TABLE `huellas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `iniciars`
--

DROP TABLE IF EXISTS `iniciars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `iniciars` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre_usuario` varchar(255) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `usuario_id` bigint(20) unsigned DEFAULT NULL,
  `token_sesion` varchar(100) DEFAULT NULL,
  `ultimo_acceso` timestamp NULL DEFAULT NULL,
  `estado_sesion` enum('activa','cerrada','expirada') NOT NULL DEFAULT 'activa',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token_sesion` (`token_sesion`),
  KEY `idx_ultimo_acceso` (`ultimo_acceso`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `iniciars`
--

LOCK TABLES `iniciars` WRITE;
/*!40000 ALTER TABLE `iniciars` DISABLE KEYS */;
INSERT INTO `iniciars` VALUES (1,'Soporte','$2y$12$ce7WbFAZSjXqzyxHulqR7ukKYbyaYQ3/JVSdKwT5dXalbSAs0/oHO',NULL,'IhAUHKr3RBXISMKyNElbVeDWqB4LvaFhqnNtjCeB5JALNeWax2Gaqen2V3pU','2025-12-29 20:22:23','activa','2025-10-15 01:23:53','2025-12-29 20:22:23');
/*!40000 ALTER TABLE `iniciars` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (3,'2025_09_16_152015_create_equipo_usuarios_table',1),(4,'2025_09_16_152039_create_huellas_table',1),(5,'2025_09_17_153357_create_personal_access_tokens_table',1),(6,'2025_09_17_195034_create_usuarios_table',1),(7,'2025_09_18_193352_create_sessions_table',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sesiones`
--

DROP TABLE IF EXISTS `sesiones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sesiones` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre_usuario` varchar(255) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `usuario_id` bigint(20) unsigned DEFAULT NULL,
  `token_sesion` varchar(100) DEFAULT NULL,
  `ultimo_acceso` timestamp NULL DEFAULT NULL,
  `estado_sesion` enum('activa','cerrada','expirada') NOT NULL DEFAULT 'activa',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token_sesion` (`token_sesion`),
  KEY `idx_ultimo_acceso` (`ultimo_acceso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sesiones`
--

LOCK TABLES `sesiones` WRITE;
/*!40000 ALTER TABLE `sesiones` DISABLE KEYS */;
/*!40000 ALTER TABLE `sesiones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('0OMN0dsgzNLVWrnmeGthi5bwBoc2FKuAP9geHEmH',NULL,'127.0.0.1','PostmanRuntime/7.49.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQlZYanRqdEtEejBIOFZJaHJWMFRmVE1lb0FldEppeFZLVEZCN3RRQyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1764853147),('3ivRMM2ZUjEgXtXsvEKzpvTJlIjR6pYAK7ufG9qr',NULL,'127.0.0.1','PostmanRuntime/7.48.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWnMzamlqdDlSZ0xocTVjWmZ3bjVsSU82Wmloa1RuOUlURlRkV2FyaSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1759501726),('Agq8ula8LdE4xdVjVMMbBOA3vVdNbVL86EJ7RDXa',NULL,'127.0.0.1','PostmanRuntime/7.49.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoibHR6aWhMbjQ0TklDYWxMclR1SEw0ZUtZbW5rVTZEc0FHMTEyVElDQiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1764189318),('Eg2vmngwWDUnNKGOLpzoTmtkdcCECiSXCRqYFlqe',NULL,'127.0.0.1','PostmanRuntime/7.49.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoieXdRRXBQWWJQVmlTNDZqZUJYcHRjMncyRWh2RGU5dGY3VTl1dWhVdyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1764800947),('IXuXhkbLLwff40kvz0kHeoRiKdYD1O5vTf6AlLK7',NULL,'127.0.0.1','PostmanRuntime/7.48.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoicWlsN1pobFNLbkVKMWdBekZPa2laOWxOZXZpeU5Qdnk4NmNVVEl4YSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1759419156),('M47y7sZil6AEdLjifXJKt8Wnwfow9f72gDqWgLbU',NULL,'127.0.0.1','PostmanRuntime/7.48.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQTNwRmlOYk9NOHdpR21Nc3liNTJobUQ3NWp1bEhuV29vSm04QzRQRiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1759354630),('W9mDkwp82RAWXrorcTJsqVIMS3nleIXM34cwSufY',NULL,'127.0.0.1','PostmanRuntime/7.48.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMTBPYkR2aGJtZkpac2dsUThIRHFZdzB2Q3NyM1d0bXM1VGVqaHBuQSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1760967372);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombres` varchar(255) NOT NULL,
  `apellidos` varchar(255) NOT NULL,
  `cedula` varchar(255) NOT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `cartera` varchar(255) DEFAULT NULL,
  `numero_equipo` varchar(255) DEFAULT NULL,
  `equipo_usuario` bigint(20) unsigned DEFAULT NULL,
  `huella` bigint(20) unsigned DEFAULT NULL,
  `best` bigint(20) unsigned DEFAULT NULL,
  `correo` varchar(255) NOT NULL,
  `almuerzo` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `no_diadema` varchar(255) NOT NULL DEFAULT '000',
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuarios_cedula_unique` (`cedula`),
  UNIQUE KEY `usuarios_correo_unique` (`correo`),
  KEY `usuarios_equipo_fk` (`equipo_usuario`),
  KEY `huella_fk` (`huella`),
  KEY `best_fk` (`best`),
  CONSTRAINT `best_fk` FOREIGN KEY (`best`) REFERENCES `usuarios_bests` (`id`) ON DELETE SET NULL,
  CONSTRAINT `huella_fk` FOREIGN KEY (`huella`) REFERENCES `huellas` (`id`) ON DELETE SET NULL,
  CONSTRAINT `usuarios_equipo_fk` FOREIGN KEY (`equipo_usuario`) REFERENCES `equipo_usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (36,'Karen Margarita','Castellar Iriarte','1129508557','3204831770','DESOCUPADOS 2022-2023','callcenter09',NULL,22,21,'ellibertador60@ngsoabogados.com',1,'2025-10-17 01:32:17','2025-12-05 18:27:39','109'),(37,'Luis Armando','Leon Cañon','1001216235','3013951870','CASTIGO','callcenter01',35,23,16,'ellibertador25@ngsoabogados.com',2,'2025-10-17 01:50:06','2025-12-15 18:01:47','088'),(39,'Luz Adriana','Linares Lagos','1033684323','3044463534','DESOCUPADOS','callcenter35',88,25,23,'ellibertador58@ngsoabogados.com',2,'2025-10-17 01:53:12','2025-12-26 21:21:57','063'),(40,'Julieth Alexandra','Castiblanco Rincon','1000250399','3044463534','DESOCUPADOS','callcenter33',36,26,24,'ellibertador36@ngsoabogados.com',2,'2025-10-17 01:54:18','2025-12-26 21:24:31','104'),(41,'Islena Paola','Acosta Salgado','1103111733','3332676589','DESOCUPADOS','callcenter32',39,27,25,'ellibertador32@ngsoabogados.com',2,'2025-10-17 01:55:30','2025-12-05 18:32:02','Sin marcar'),(42,'Jeimy Andrea','Rodríguez Cañón','1072190152','3163275581','LIDER','callcenter31',66,31,52,'lidercartera2@ngsoabogados.com',2,'2025-10-17 01:57:34','2025-12-05 18:32:48','000'),(43,'Angie Lorena','Hernandez Castiblanco','1001275619','3105763468','DESISTIDOS','callcenter21',34,28,42,'ellibertador1@ngsoabogados.com',2,'2025-10-17 01:58:43','2025-12-05 18:33:36','000'),(44,'Yeritson Adrian','Vega Acero','1014284618','3154970039','DESISTIDOS','callcenter24',42,64,40,'ellibertador67@ngsoabogados.com',2,'2025-10-17 01:59:44','2025-12-05 18:34:22','114'),(45,'Natalia Ines','Nisperuza Sanchez','1138074284','3204840614','DESISTIDOS','callcenter22',48,30,41,'ellibertador47@ngsoabogados.com',2,'2025-10-17 02:00:44','2025-12-05 18:35:07','101'),(46,'Valery Brillit','Rincon Linares','1033685482','3013364721','DESISTIDOS','callcenter23',57,32,50,'ellibertador5@ngsoabogados.com',1,'2025-10-17 02:02:14','2025-12-26 21:12:29','073'),(47,'Johan Camilo','Avila Bohorquez','1001116838','3024627109','DESISTIDOS','callcenter14',NULL,33,36,'ellibertador45@ngsoabogados.com',3,'2025-10-17 02:04:30','2025-12-26 21:33:45','054'),(48,'Juan Manuel','Bermudez Correa','1000727404','3204853696','DESISTIDOS','callcenter12',37,34,35,'ellibertador17@ngsoabogados.com',3,'2025-10-17 02:06:29','2025-12-26 20:59:26','068'),(49,'Diana Rocio','Naranjo Hernandez','52756032','3015588649','DESISTIDOS','callcenter11',43,35,34,'ellibertador46@ngsoabogados.com',3,'2025-10-17 02:07:32','2025-12-26 20:58:11','058'),(50,'Carol Tatiana','Yepez Betancourth','1024593276','3022887255','DESISTIDOS','callcenter16',68,36,48,'ellibertador70@ngsoabogados.com',3,'2025-10-17 02:08:49','2025-12-26 21:10:16','097'),(51,'Angela Patricia','Diaz Fernandez','1018441492','3013951870','CASTIGO','callcenter00',50,38,14,'ellibertador4@ngsoabogados.com',3,'2025-10-17 02:10:54','2025-12-05 18:39:14','093'),(52,'Luisa Fernanda','Beltran Guescot','1019073284','3013951870','CASTIGO','callcenter54',61,39,15,'ellibertador28@ngsoabogados.com',3,'2025-10-17 02:12:04','2025-12-26 21:31:14','036'),(53,'Maria Fernanda','Duarte Mape','1000218098','3006879078','DESISTIDOS','Callcenter29',45,41,37,'ellibertador52@ngsoabogados.com',2,'2025-10-17 02:15:05','2025-12-26 21:13:57','000'),(54,'Michel Vanesa','Muños Gutierrez','1001286177','3204853696','DESISTIDOS','Callcenter28',87,42,45,'ellibertador22@ngsoabogados.com',2,'2025-10-17 02:16:33','2025-12-05 18:41:26','Sin marcar'),(55,'Nicol Dallan','Dominguez Carrasco','1032677388','300 6879078','DESISTIDOS','Callcenter27',53,45,38,'ellibertador14@ngsoabogados.com',2,'2025-10-17 02:18:48','2025-12-26 21:15:12','Sin marcar'),(56,'Maria Camila','Millan Cedeno','1000338067','0','DESISTIDOS','Callcenter00',58,46,47,'ellibertador35@ngsoabogados.com',3,'2025-10-17 02:20:22','2025-12-26 20:56:00','096'),(57,'Harvy Anyinzan','Trujillo Camargo','1013689282','0','DESISTIDOS','Callcenter8',32,48,39,'ellibertador10@ngsoabogados.com',3,'2025-10-17 02:21:53','2025-12-26 21:03:21','106'),(58,'Maria Alejandra','Acosta Blanco','1012434013','3015588649','DESISTIDOS','Callcenter17',38,49,46,'ellibertador49@ngsoabogados.com',3,'2025-10-17 02:23:01','2025-12-26 21:06:49','103'),(59,'Sandra Milena','Hernandez Corredor','52889704','3022886876','DESOCUPADOS','Callcenter00',51,50,20,'ellibertador40@ngsoabogados.com',3,'2025-10-17 02:24:21','2025-12-05 18:44:54','071'),(60,'Andres Felipe','Arguello Orjuela','1057014128','3204831770','DESOCUPADOS 2022-2023','Callcenter44',46,52,26,'ellibertador2@ngsoabogados.com',2,'2025-10-17 02:26:08','2025-12-05 19:04:31','Sin marcar'),(62,'Rafael Steven','Zabala Noriega','1000774584','3022886876','DESOCUPADOS','Callcenter59',90,56,27,'ellibertador11@ngsoabogados.com',2,'2025-10-17 02:29:02','2025-12-05 18:47:23','110'),(63,'Laura Rocio','Rodríguez Mora','1022443548','3227985812','DESOCUPADOS 2022-2023','Callcenter45',56,61,29,'ellibertador31@ngsoabogados.com',2,'2025-10-17 02:31:13','2025-12-05 18:48:22','Sin marcar'),(64,'Sandi Marcela','Burgos Pineda','1002525594','3024627109','DESISTIDOS','Callcenter9',44,47,51,'ellibertador20@ngsoabogados.com',2,'2025-10-17 02:38:59','2025-12-26 21:00:57','113'),(67,'Mareleimys Judith','Caro Bolaño','1002208098','3044463534','DESOCUPADOS','Callcenter00',73,51,22,'ellibertador022@ngsoabogados.com',2,'2025-10-17 02:49:30','2025-12-18 20:29:25','000'),(68,'Irma Rosa','Diaz Barreto','52837437','3015588649','DESISTIDOS','Callcenter00',67,44,44,'ellibertador48@ngsoabogados.com',2,'2025-10-17 02:59:07','2025-12-05 18:50:16','000'),(69,'Ginna Alejandra','Perez Cifuentes','1018439309','3013364721','DESISTIDOS','Callcenter00',74,43,43,'ellibertador3@ngsoabogados.com',2,'2025-10-17 03:02:40','2025-12-05 18:51:07','000'),(70,'Edison Esneyder','Montaño Abril','1070306099','3013951870','LIDER','callcenter26',64,NULL,NULL,'directorestrategia@ngsoabogados.com',2,'2025-10-17 03:18:03','2025-12-04 23:39:05','000'),(71,'Yerfin Disney','Ardila Otalora','1097332997','3013053846','LIDER','callcenter43',65,59,NULL,'lidercartera@ngsoabogados.com',2,'2025-10-17 03:19:44','2025-10-17 03:19:44','000'),(72,'Cristian David','Diaz Melo','1016106935','3204853696','DESISTIDOS','callcenter19',62,37,49,'ellibertador68@ngsoabogados.com',2,'2025-10-17 17:41:48','2025-12-26 21:36:08','055'),(73,'Laura Ximena','Alarcon Parra','1018454151','3013951870','CASTIGO','callcenter007',NULL,40,17,'ellibertador56@ngsoabogados.com',2,'2025-10-17 17:43:19','2025-12-27 00:34:00','Sin marcar'),(74,'Laura Daniela','Zambrano Solis','1000225422','3332676661','DESOCUPADOS','Callcenter00',60,54,28,'ellibertador8@ngsoabogados.com',1,'2025-10-17 17:45:07','2025-12-05 18:57:13','090'),(75,'Laura Liliana','Cative Rojas','1019152466','3022886876','DESOCUPADOS','Callcenter47',85,57,30,'ellibertador19@ngsoabogados.com',2,'2025-10-17 17:46:53','2025-12-27 00:29:39','112'),(76,'Juan Daniel','Cadena Sarmiento','1001193173','3332676661','DESOCUPADOS 2022-2023','Callcenter59',89,63,31,'ellibertador12@ngsoabogados.com',1,'2025-10-17 17:48:40','2025-12-26 21:36:55','Sin marcar'),(85,'Daniela','Martinez Suarez','1020811040','3022886876','DESOCUPADOS','callcenter55',76,24,32,'ellibertador7@ngsoabogados.com',1,'2025-12-05 00:35:39','2025-12-05 18:59:19','Sin marcar'),(86,'Tania Lizeth','Galindo Benavides','1019137477','333 2676661','DESOCUPADOS 2022-2023','Callcenter59',55,58,33,'ellibertador6@ngsoabogados.com',2,'2025-12-05 00:37:36','2025-12-05 18:59:56','110'),(87,'Erika','Zubieta Mendez','1020760952','3213415709','SUPERNUMERARIO','Callcenter47',91,NULL,54,'asesor2@exialegal.com',4,'2025-12-05 00:38:38','2025-12-05 19:00:35','000'),(88,'Sandra Milena','Rodriguez','000','3213415709','SUPERNUMERARIO','000',NULL,NULL,53,'asesor1@exialegal.com',2,'2025-12-05 00:39:43','2025-12-05 19:01:30','000'),(89,'Lizeth Tatiana','Gonzalez','1016019319','000','CASTIGO','Callcenter006',84,53,18,'000@ngso.com',2,'2025-12-11 19:04:22','2025-12-11 19:14:47','111');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios_bests`
--

DROP TABLE IF EXISTS `usuarios_bests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios_bests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre_usuario` varchar(100) NOT NULL,
  `extension` varchar(5) NOT NULL,
  `usuario` varchar(100) NOT NULL,
  `clave` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios_bests`
--

LOCK TABLES `usuarios_bests` WRITE;
/*!40000 ALTER TABLE `usuarios_bests` DISABLE KEYS */;
INSERT INTO `usuarios_bests` VALUES (14,'AngelaDiaz','275','Ángela Patricia Díaz Fernández','T6u8CO2wQk','2025-12-05 18:08:13','2025-12-26 20:53:55'),(15,'LuisaBeltran','276','Luisa Fernanda Beltrán Quescot','NtpkiP8n9f','2025-12-05 18:08:50','2025-12-26 20:45:03'),(16,'LuisLeon','282','Luis Armando León','FrVagp8W2d','2025-12-05 18:09:36','2025-12-26 20:48:43'),(17,'LauraAlarcon','352','Laura Ximena Alarcón Parra','jEG8Y4kUSv','2025-12-05 18:09:56','2025-12-26 20:46:02'),(18,'Deiby Alexander Quevedo Gonzales','353','DeibyQuevedo','ZbqO9bMehk','2025-12-05 18:10:42','2025-12-11 19:05:42'),(19,'Maria Paula Guzman Santisteban','361','MariaGuzman','******','2025-12-05 18:11:11','2025-12-05 18:11:11'),(20,'SandraHernandez','279','Sandra Milena Hernández Redon','rSEKseRLj8','2025-12-05 18:11:35','2025-12-26 21:17:06'),(21,'Karen Margarita Castellar iriarte','283','KarenCastellar','******','2025-12-05 18:11:59','2025-12-05 18:11:59'),(22,'Mareleimys Judith Caro Bolaño','286','MareleimysCaro','******','2025-12-05 18:12:15','2025-12-05 18:12:15'),(23,'LuzLinares','287','Luz Adriana Linares Lagos','M7iY4iHOz1','2025-12-05 18:12:44','2025-12-26 21:21:56'),(24,'JuliethCastiblanco','288','Julieth Alexandra Castiblanco Rincón','3iflJjGA5h','2025-12-05 18:13:13','2025-12-26 21:24:30'),(25,'Islena Paola Acosta Salgado','289','IslenaAcosta','******','2025-12-05 18:13:43','2025-12-05 18:13:43'),(26,'AndresArguello','327','Andrés Felipe Arguello','FEA27I8YrH','2025-12-05 18:14:11','2025-12-26 21:26:00'),(27,'StevenNoriega','328','Steven Zabala Noriega','9j6yKJpzhj','2025-12-05 18:14:27','2025-12-26 20:41:04'),(28,'LauraDaniela','330','Laura Daniela Zambrano Solis','4W5tdofX7C','2025-12-05 18:14:45','2025-12-26 21:20:19'),(29,'Laura Rocío Rodríguez Mora','347','LauraRodriguez','******','2025-12-05 18:15:03','2025-12-05 18:15:03'),(30,'LauraCative','349','Laura Liliana Cative Rojas','YwG8bojGIp','2025-12-05 18:15:24','2025-12-26 20:43:20'),(31,'JuanCadena','355','Juan Daniel Cadena Sarmiento','IcqZ2MGhE1','2025-12-05 18:15:46','2025-12-26 21:22:47'),(32,'DanielaSuarez','357','Daniela Martínez Suárez','BWFz0EmDqp','2025-12-05 18:16:20','2025-12-26 21:18:41'),(33,'Tania Lizeth Galindo Benavides','359','TANIAGALINDO','******','2025-12-05 18:16:38','2025-12-05 18:16:38'),(34,'DianaNaranjo','291','Diana Rocio Naranjo','5Khgxf1iVb','2025-12-05 18:17:11','2025-12-26 20:58:10'),(35,'JuanBermudez','292','Juan Manuel Bermeo Díaz Correa','eawqKQv6ZL','2025-12-05 18:17:40','2025-12-26 20:59:26'),(36,'JohanAvila','293','Johan Camilo Ávila Bohórquez','QP6J5Ab1ik','2025-12-05 18:17:59','2025-12-26 21:05:05'),(37,'MarianDuarte','295','Marian Fernanda Duarte Amor','SuOR571rvc','2025-12-05 18:18:24','2025-12-26 21:13:57'),(38,'NicolDominguez','296','Nicol Dallan Doñamaguez Carrasco','rbB0fYNOkF','2025-12-05 18:18:44','2025-12-26 21:15:11'),(39,'HarvyTrujillo','299','Harvy Anvinzan Trujillo Camargo','F0faLZ9KBQ','2025-12-05 18:19:53','2025-12-26 21:03:20'),(40,'Yeritson Adrián Vega Acero','303','YeritsonVega','******','2025-12-05 18:20:10','2025-12-05 18:20:10'),(41,'Natalia Inés Nisperuza Sánchez','305','NataliaNisperuza','******','2025-12-05 18:21:10','2025-12-05 18:21:10'),(42,'Angie Lorena Hernández Castiblanco','306','AngieHernandez','1Apx6To4ZJ','2025-12-05 18:21:29','2025-12-11 01:31:28'),(43,'Ginna Alejandra Pérez Cifuentes','308','GinnaCuesta','******','2025-12-05 18:21:47','2025-12-05 18:21:47'),(44,'Irma Rosa Díaz Barreto','309','IrmaDiaz','******','2025-12-05 18:22:16','2025-12-05 18:22:16'),(45,'Michel Vanesa Muñoz Gutiérrez','310','MichelMunoz','******','2025-12-05 18:22:43','2025-12-05 18:22:43'),(46,'MariaAcosta','311','María Alejandra Acosta Blanco','ZP1Y2spQfK','2025-12-05 18:22:59','2025-12-26 21:06:49'),(47,'MariaMillan','318','María Camila Millán Cedeño','qMKTuHwmM9','2025-12-05 18:23:18','2025-12-26 20:55:59'),(48,'CarolBetancourth','320','Carol Tatiana Yépez Betancourth','M6gw4iX84x','2025-12-05 18:24:14','2025-12-26 21:10:15'),(49,'CristianMelo','10000','Cristian David Díaz Melo','xpLGtz3i3p','2025-12-05 18:24:39','2025-12-26 21:42:12'),(50,'ValeryLinares','322','Valery Brillit Rincón Linares','h8ZpdPJK7u','2025-12-05 18:25:00','2025-12-26 21:12:29'),(51,'SandiPineda','323','Sandy Marcela Burros Pineda','O6mg0Vyv2x','2025-12-05 18:25:20','2025-12-26 21:00:56'),(52,'Jeimy Andrea Rodríguez Cañon','340','AndreaRodriguez','******','2025-12-05 18:25:44','2025-12-05 18:25:44'),(53,'Sandra Milena Rodríguez','331','SandraM','******','2025-12-05 18:25:59','2025-12-05 18:25:59'),(54,'Erika Zubieta Méndez','362','ZubietaMendez','******','2025-12-05 18:26:24','2025-12-05 18:26:24');
/*!40000 ALTER TABLE `usuarios_bests` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-12-29 13:30:45
