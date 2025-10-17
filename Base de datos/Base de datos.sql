-- MySQL dump 10.13  Distrib 8.0.42, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: gestiones
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
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `equipo_usuarios`
--

LOCK TABLES `equipo_usuarios` WRITE;
/*!40000 ALTER TABLE `equipo_usuarios` DISABLE KEYS */;
INSERT INTO `equipo_usuarios` VALUES (32,'ellibertador10','Ngso2025+','2025-10-16 23:45:16','2025-10-16 23:45:16'),(33,'ellibertador11','NGSO2025***','2025-10-16 23:45:40','2025-10-16 23:45:40'),(34,'ellibertador13','Ngso2025*','2025-10-16 23:45:53','2025-10-16 23:45:53'),(35,'ellibertador15','Ngso2024*','2025-10-16 23:46:08','2025-10-16 23:46:08'),(36,'ellibertador16','NGSO2025--','2025-10-16 23:46:25','2025-10-16 23:46:25'),(37,'ellibertador17','Ngso2028','2025-10-16 23:46:39','2025-10-16 23:46:39'),(38,'ellibertador20','Aa15072025.*','2025-10-16 23:46:53','2025-10-16 23:46:53'),(39,'ellibertador22','NGSO2025*+','2025-10-16 23:47:11','2025-10-16 23:47:11'),(40,'ellibertador24','Ngso2025*+','2025-10-16 23:47:31','2025-10-16 23:47:31'),(41,'ellibertador27','ngso2025**','2025-10-16 23:48:03','2025-10-16 23:48:03'),(42,'ellibertador3','Ngso2025*-+','2025-10-16 23:48:16','2025-10-16 23:48:16'),(43,'ellibertador32','Ngso.2025***','2025-10-16 23:49:04','2025-10-16 23:49:04'),(44,'ellibertador33','Ngso2024**','2025-10-16 23:49:24','2025-10-16 23:49:24'),(45,'ellibertador34','Elibertador34**','2025-10-16 23:49:35','2025-10-16 23:49:35'),(46,'ellibertador35','Ngso2025*','2025-10-16 23:49:53','2025-10-16 23:49:53'),(47,'ellibertador36','NGSO2025++','2025-10-16 23:50:04','2025-10-16 23:50:04'),(48,'ellibertador46','ngso2026//','2025-10-16 23:50:32','2025-10-16 23:50:32'),(49,'ellibertador5','Ngso2025/*','2025-10-16 23:50:44','2025-10-16 23:50:44'),(50,'ellibertador50','NGSO2025+*','2025-10-16 23:50:56','2025-10-16 23:50:56'),(51,'ellibertador52','Ngso2024*+','2025-10-16 23:51:07','2025-10-16 23:51:07'),(52,'ellibertador54','NGSO2024*+','2025-10-16 23:51:17','2025-10-16 23:51:17'),(53,'ellibertador57','NGSo2025','2025-10-16 23:51:31','2025-10-16 23:51:31'),(54,'ellibertador6','Ngso2023**','2025-10-16 23:51:42','2025-10-16 23:51:42'),(55,'ellibertador60','NGSO2024*+','2025-10-16 23:51:52','2025-10-16 23:51:52'),(56,'ellibertador61','NGSO2024*+','2025-10-16 23:52:13','2025-10-16 23:52:13'),(57,'ellibertador7','Ngso2026--','2025-10-16 23:52:26','2025-10-16 23:52:26'),(58,'ellibertados38','NGSO2025***','2025-10-16 23:52:51','2025-10-16 23:52:51'),(59,'ellibertador19','Ngso2022*','2025-10-16 23:53:15','2025-10-16 23:54:22'),(60,'ellibertador47','Ngso2024*','2025-10-16 23:53:26','2025-10-16 23:54:40'),(61,'ellibertador58','NGSO2024*+','2025-10-16 23:53:37','2025-10-16 23:55:04'),(62,'ellibertador62','NGSO2024*+','2025-10-16 23:53:49','2025-10-16 23:55:29'),(63,'coord_prejuridico1','Calidad123**','2025-10-17 00:16:50','2025-10-17 03:24:59'),(64,'coord_prejuridico2','Ngso2025-*','2025-10-17 00:17:11','2025-10-17 03:23:39'),(65,'supervisor cartera','NGSO2025*+','2025-10-17 00:17:28','2025-10-17 03:22:57'),(66,'lidercartera','NGSO2025+-','2025-10-17 01:56:53','2025-10-17 03:24:11'),(67,'ellibertador37','Ngso2025.*','2025-10-17 02:40:42','2025-10-17 02:40:42'),(68,'ellibertador25','Ngso2025.*','2025-10-17 02:41:10','2025-10-17 02:41:10'),(69,'ellibertador28','Ngso2025.*','2025-10-17 02:41:38','2025-10-17 02:41:38'),(70,'ellibertador2','Ngso2025.*','2025-10-17 02:42:20','2025-10-17 02:42:20'),(71,'ellibertador12','Ngso2025.*','2025-10-17 02:42:42','2025-10-17 02:42:42'),(72,'ellibertador49','Ngso2025.*','2025-10-17 02:43:08','2025-10-17 02:43:08'),(73,'ellibertador30','Ngso2026*','2025-10-17 02:46:24','2025-10-17 02:46:24'),(74,'ellibertador55','NGSO2024*+','2025-10-17 02:59:42','2025-10-17 02:59:42');
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
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `huellas`
--

LOCK TABLES `huellas` WRITE;
/*!40000 ALTER TABLE `huellas` DISABLE KEYS */;
INSERT INTO `huellas` VALUES (22,'1129508557','Ngso2025','KAREN MARGARITA CASTELLAR IRIARTE','2025-10-15 02:59:46','2025-10-15 02:59:46'),(23,'1001216235','Ngso2025','LUIS ARMANDO LEON CAÑON','2025-10-15 03:00:05','2025-10-15 03:00:05'),(24,'52879254','Ngso2025/*','NANCY ALEJANDRA GONZALEZ','2025-10-15 03:00:24','2025-10-15 03:00:24'),(25,'1033684323','Colombia24','LUZ ADRIANA LINARES LAGOS','2025-10-15 03:00:38','2025-10-15 03:00:38'),(26,'1000250399','Ngso2025++','JULIETH ALEXANDRA CASTIBLANCO RINCON','2025-10-15 03:00:50','2025-10-15 03:00:50'),(27,'1103111733','Ngso2025','ISLENA PAOLA ACOSTA SALGADO','2025-10-15 03:01:12','2025-10-15 03:01:12'),(28,'1001275619','Ngso2025+*','ANGIE LORENA HERNANDEZ CASTIBLANCO','2025-10-15 03:01:22','2025-10-15 03:01:22'),(29,'1014284618','Ngso2027','YERITSON ADRIAN VEGA ACERO','2025-10-15 03:01:34','2025-10-15 03:01:34'),(30,'1138074284','Ngso2026*','NATALIA INES NISPERUZA SANCHEZ','2025-10-15 03:01:49','2025-10-15 03:11:35'),(31,'1072190152','Ngso2024+','JEIMY ANDREA RODRIGUEZ CAÑON','2025-10-17 00:00:48','2025-10-17 00:19:52'),(32,'1033685482','Ngso2026++','VALERY BRILLIT RINCON LINARES','2025-10-17 00:01:28','2025-10-17 00:20:15'),(33,'1001116838','Ngso2026','JOHAN CAMILO AVILA BOHORQUEZ','2025-10-17 00:01:55','2025-10-17 00:20:50'),(34,'1000727404','Ngso2025*-','JUAN MANUEL BERMUDEZ CORREA','2025-10-17 00:02:21','2025-10-17 00:21:12'),(35,'52756032','Naranjo321*','DIANA ROCIO NARANJO HERNANDEZ','2025-10-17 00:02:56','2025-10-17 00:21:33'),(36,'1024593276','Yepes2025','CAROL TATIANA YEPEZ BETANCOURTH','2025-10-17 00:03:46','2025-10-17 00:21:50'),(37,'1016106935','Ngso2025**','CRISTIAN DAVID DIAZ MELO','2025-10-17 00:04:11','2025-10-17 00:22:08'),(38,'1018441492','Ngso2025','ANGELA PATRICIA DIAZ FERNANDEZ','2025-10-17 00:04:33','2025-10-17 00:22:30'),(39,'1019073284','Ngso2025++','LUISA FERNANDA BELTRAN GUESCOT','2025-10-17 00:04:58','2025-10-17 00:22:49'),(40,'1000732395','Ngso2025+','MARIA XIMENA ROSAS MATAPI','2025-10-17 00:05:19','2025-10-17 00:23:07'),(41,'1000218098','Colombia23**','MARIA FERNANDA DUARTE MAPE','2025-10-17 00:06:26','2025-10-17 00:23:25'),(42,'1001286177','Ngso2025','MICHEL VANESA MUÑOZ GUTIERREZ','2025-10-17 00:06:48','2025-10-17 00:23:43'),(43,'1018439309','Ngso2025*+','GINNA ALEJANDRA PEREZ CIFUENTES','2025-10-17 00:07:07','2025-10-17 00:24:01'),(44,'52837437','Ngso2024*','IRMA ROSA DIAZ BARRETO','2025-10-17 00:07:25','2025-10-17 00:24:18'),(45,'1032677388','NgsO2025*','NICOL DALLAN DOMINGUEZ CARRASCO','2025-10-17 00:07:44','2025-10-17 00:24:38'),(46,'1000338067','Ngso2025++','MARIA CAMILA MILLAN CEDENO','2025-10-17 00:08:01','2025-10-17 00:24:59'),(47,'1002525594','Ngso2025*','SANDI MARCELA BURGOS PINEDA','2025-10-17 00:08:22','2025-10-17 00:25:16'),(48,'1013689282','Ngso2025','HARVY ANYINZAN TRUJILLO CAMARGO','2025-10-17 00:08:41','2025-10-17 00:25:35'),(49,'1012434013','Ngso2025*','MARIA ALEJANDRA ACOSTA BLANCO','2025-10-17 00:09:01','2025-10-17 00:25:53'),(50,'52889704','Ngso2025','SANDRA MILENA HERNANDEZ CORREDOR','2025-10-17 00:09:23','2025-10-17 00:26:10'),(51,'1002208098','Chicago21','MARELEIMYS JUDITH CARO BOLAÑO','2025-10-17 00:09:41','2025-10-17 00:26:29'),(52,'1057014128','Ngso2025','ANDRES FELIPE ARGUELLO ORJUELA','2025-10-17 00:10:03','2025-10-17 00:26:49'),(53,'1023973889','Ngso2025','JOHAN STEVEN PINEDA ROMERO','2025-10-17 00:10:21','2025-10-17 00:27:07'),(54,'1000225422','Ngso2025','LAURA DANIELA ZAMBRANO SOLIS','2025-10-17 00:10:38','2025-10-17 00:27:27'),(55,'1233891770','Ngso2025','LAURA BEATRIZ MOREIRA GARCES','2025-10-17 00:10:59','2025-10-17 00:27:46'),(56,'1000774584','Ngso2025','RAFAEL STEVEN ZABALA NORIEGA','2025-10-17 00:11:19','2025-10-17 00:28:08'),(57,'1014278390','Ngso2025','AMMY VIVIANA CASAS ROZO','2025-10-17 00:11:43','2025-10-17 00:28:24'),(58,'1000626370','Ngso2025','LUISA FERNANDA LIZCANO GARAVITO','2025-10-17 00:12:02','2025-10-17 00:28:42'),(59,'1097332999','Ngso2025','YERFIN DISNEY ARDILA OTALORA','2025-10-17 00:12:18','2025-10-17 00:28:58'),(61,'1000625517','Ngso2025','PAULA ALEJANDRA NIÑO PEÑA','2025-10-17 00:12:47','2025-10-17 00:29:49'),(62,'N/A','N/A','N/A','2025-10-17 03:16:47','2025-10-17 03:16:47');
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
INSERT INTO `iniciars` VALUES (1,'Soporte','$2y$12$ce7WbFAZSjXqzyxHulqR7ukKYbyaYQ3/JVSdKwT5dXalbSAs0/oHO',NULL,'QSAzJoWQLQiGRAqWvlqy9NrIeCkUx1FZiV5HYLwyFEXurgVfIL4CjDVAlwf6','2025-10-17 20:06:01','activa','2025-10-15 01:23:53','2025-10-17 20:06:01');
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
INSERT INTO `sessions` VALUES ('3ivRMM2ZUjEgXtXsvEKzpvTJlIjR6pYAK7ufG9qr',NULL,'127.0.0.1','PostmanRuntime/7.48.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWnMzamlqdDlSZ0xocTVjWmZ3bjVsSU82Wmloa1RuOUlURlRkV2FyaSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1759501726),('IXuXhkbLLwff40kvz0kHeoRiKdYD1O5vTf6AlLK7',NULL,'127.0.0.1','PostmanRuntime/7.48.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoicWlsN1pobFNLbkVKMWdBekZPa2laOWxOZXZpeU5Qdnk4NmNVVEl4YSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1759419156),('M47y7sZil6AEdLjifXJKt8Wnwfow9f72gDqWgLbU',NULL,'127.0.0.1','PostmanRuntime/7.48.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQTNwRmlOYk9NOHdpR21Nc3liNTJobUQ3NWp1bEhuV29vSm04QzRQRiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1759354630);
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
  `correo` varchar(255) NOT NULL,
  `usuario_bestvoiper` varchar(255) DEFAULT NULL,
  `extension` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuarios_cedula_unique` (`cedula`),
  UNIQUE KEY `usuarios_correo_unique` (`correo`),
  KEY `usuarios_equipo_usuario_foreign` (`equipo_usuario`),
  KEY `usuarios_huella_foreign` (`huella`),
  CONSTRAINT `usuarios_equipo_usuario_foreign` FOREIGN KEY (`equipo_usuario`) REFERENCES `equipo_usuarios` (`id`),
  CONSTRAINT `usuarios_huella_foreign` FOREIGN KEY (`huella`) REFERENCES `huellas` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (36,'Karen Margarita','Castellar Iriarte','1129508557','3204831770','DESOCUPADOS 2022-2023','callcenter09',41,22,'ellibertador60@ngsoabogados.com','KAREN MARGARITA CASTELLAR IRIARTE','283','2025-10-17 01:32:17','2025-10-17 17:51:02'),(37,'Luis Armando','Leon Cañon','1001216235','3013951870','CASTIGO','callcenter01',35,23,'ellibertador25@ngsoabogados.com','LUIS ARMANDO LEON CAÑON','282','2025-10-17 01:50:06','2025-10-17 01:50:06'),(38,'Nancy Alejandra','Gonzalez','52879254','3022886876','DESOCUPADOS','callcenter55',49,24,'ellibertador18@ngsoabogados.com','NANCY ALEJANDRA GONZALES','280','2025-10-17 01:51:47','2025-10-17 01:51:47'),(39,'Luz Adriana','Linares Lagos','1033684323','3332676589','DESOCUPADOS','callcenter35',40,25,'ellibertador58@ngsoabogados.com','LUZ ADRIANA LINARES LAGOS','287','2025-10-17 01:53:12','2025-10-17 01:53:12'),(40,'Julieth Alexandra','Castiblanco Rincon','1000250399','3332676589','DESOCUPADOS','callcenter33',36,26,'ellibertador36@ngsoabogados.com','JULIETH ALEXANDRA CASTIBLANCO RINCÓN','288','2025-10-17 01:54:18','2025-10-17 01:54:18'),(41,'Islena Paola','Acosta Salgado','1103111733','3332676589','DESOCUPADOS','callcenter32',39,27,'ellibertador32@ngsoabogados.com','ISLENA PAOLA ACOSTA SALGADO','289','2025-10-17 01:55:30','2025-10-17 01:55:30'),(42,'Jeimy Andrea','Rodríguez Cañón','1072190152','3163275581','LIDER','callcenter31',66,31,'lidercartera2@ngsoabogados.com','N/A','000','2025-10-17 01:57:34','2025-10-17 02:37:56'),(43,'Angie Lorena','Hernandez Castiblanco','1001275619','3105763468','DESISTIDOS','callcenter21',34,28,'ellibertador1@ngsoabogados.com','ANGIE LORENA HERNÁNDEZ CASTIBLANCO','306','2025-10-17 01:58:43','2025-10-17 17:50:19'),(44,'Yeritson Adrian','Vega Acero','1014284618','3154970039','DESISTIDOS','callcenter24',37,29,'ellibertador67@ngsoabogados.com','YERITSON ADRIÁN VEGA ACERO','303','2025-10-17 01:59:44','2025-10-17 01:59:44'),(45,'Natalia Ines','Nisperuza Sanchez','1138074284','3204840614','DESISTIDOS','callcenter22',48,30,'ellibertador47@ngsoabogados.com','NATALIA INÉS NISPERUZA SÁNCHEZ','305','2025-10-17 02:00:44','2025-10-17 02:00:44'),(46,'Valery Brillit','Rincon Linares','1033685482','3154970039','DESISTIDOS','callcenter23',57,32,'ellibertador5@ngsoabogados.com','VALERY BRILLIT RINCON LINARES','322','2025-10-17 02:02:14','2025-10-17 19:21:32'),(47,'Johan Camilo','Avila Bohorquez','1001116838','3013364721','DESISTIDOS','callcenter14',52,33,'ellibertador45@ngsoabogados.com','JOHAN CAMILO ÁVILA BOHÓRQUEZ','293','2025-10-17 02:04:30','2025-10-17 02:04:30'),(48,'Juan Manuel','Bermudez Correa','1000727404','3022887255','DESISTIDOS','callcenter12',42,34,'ellibertador17@ngsoabogados.com','JUAN MANUEL BERMÚDEZ CORREA','292','2025-10-17 02:06:29','2025-10-17 02:06:29'),(49,'Diana Rocio','Naranjo Hernandez','52756032','3204840614','DESISTIDOS','callcenter11',43,35,'ellibertador46@ngsoabogados.com','DIANA ROCÍO DIANA ROCÍO','291','2025-10-17 02:07:32','2025-10-17 02:07:32'),(50,'Carol Tatiana','Yepez Betancourth','1024593276','3022887285','DESISTIDOS','callcenter16',68,36,'ellibertador70@ngsoabogados.com','CAROL TATIANA YEPEZ BETANCOURTH','320','2025-10-17 02:08:49','2025-10-17 19:26:02'),(51,'Angela Patricia','Diaz Fernandez','1018441492','3013951870','CASTIGO','callcenter00',50,38,'ellibertador4@ngsoabogados.com','ANGELA PATRICIA DÍAZ FERNÁNDEZ','275','2025-10-17 02:10:54','2025-10-17 02:10:54'),(52,'Luisa Fernanda','Beltran Guescot','1019073284','3013951870','CASTIGO','callcenter54',47,39,'ellibertador28@ngsoabogados.com','LUISA FERNANDA BELTRÁN GUESCOT','276','2025-10-17 02:12:04','2025-10-17 02:12:04'),(53,'Maria Fernanda','Duarte Mape','1000218098','3105763468','DESISTIDOS','Callcenter29',45,41,'ellibertador52@ngsoabogados.com','MARIA FERNANDA DUARTE MAPE','295','2025-10-17 02:15:05','2025-10-17 02:15:05'),(54,'Michel Vanesa','Muños Gutierrez','1001286177','3204853696','DESISTIDOS','Callcenter28',54,42,'ellibertador22@ngsoabogados.com','MICHEL VANESA MUÑOZ GUTIÉRREZ','310','2025-10-17 02:16:33','2025-10-17 02:16:33'),(55,'Nicol Dallan','Dominguez Carrasco','1032677388','3105763468','DESISTIDOS','Callcenter27',53,45,'ellibertador14@ngsoabogados.com','NICOL DALLAN DOMÍNGUEZ CARRASCO','296','2025-10-17 02:18:48','2025-10-17 19:23:46'),(56,'Maria Camila','Millan Cedeno','1000338067','3204840614','DESISTIDOS','Callcenter00',58,46,'ellibertador35@ngsoabogados.com','MARIA CAMILA MILLAN CEDENO','318','2025-10-17 02:20:22','2025-10-17 02:20:22'),(57,'Harvy Anyinzan','Trujillo Camargo','1013689282','3105763468','DESISTIDOS','Callcenter8',32,48,'ellibertador10@ngsoabogados.com','HARVY ANYINZAN TRUJILLO CAMARGO','299','2025-10-17 02:21:53','2025-10-17 02:21:53'),(58,'Maria Alejandra','Acosta Blanco','1012434013','3204840614','DESISTIDOS','Callcenter17',38,49,'ellibertador49@ngsoabogados.com','MARIA ALEJANDRA ACOSTA BLANCO','311','2025-10-17 02:23:01','2025-10-17 02:23:01'),(59,'Sandra milena','Hernandez Corredor','52889704','3022886876','DESOCUPADOS','Callcenter00',51,50,'ellibertador40@ngsoabogados.com','SANDRA MILENA HERNÁNDEZ REDON','279','2025-10-17 02:24:21','2025-10-17 02:24:21'),(60,'Andres Felipe','Arguello Orjuela','1057014128','3204831770','DESOCUPADOS 2022-2023','Callcenter44',46,52,'ellibertador2@ngsoabogados.com','ANDRES FELIPE ARGUELLO','327','2025-10-17 02:26:08','2025-10-17 02:26:08'),(61,'Deiby Alexander','Quevedo Gonzalez','1024524648','3013951870','CASTIGO','Callcenter01',33,53,'ellibertador16@ngsoabogados.com','DEIBY ALEXANDER QUEVEDO GONZALEZ','353','2025-10-17 02:27:21','2025-10-17 02:27:21'),(62,'Rafael Steven','Zabala Noriega','1000774584','3022886876','DESOCUPADOS','Callcenter59',55,56,'ellibertador11@ngsoabogados.com','STEVEN ZABALA NORIEGA','328','2025-10-17 02:29:02','2025-10-17 02:29:02'),(63,'Laura Rocio','Rodríguez Mora','1022443548','3227985812','DESOCUPADOS 2022-2023','Callcenter45',56,61,'ellibertador31@ngsoabogados.com','Laura Rocio Rodríguez Mora','355','2025-10-17 02:31:13','2025-10-17 02:31:13'),(64,'Sandi Marcela','Burgos Pineda','1002525594','3154970039','DESISTIDOS','Callcenter9',44,47,'ellibertador20@ngsoabogados.com','SANDI MARCELA BURGOS PINEDA','323','2025-10-17 02:38:59','2025-10-17 02:38:59'),(67,'Mareleimys Judith','Caro Bolaño','1002208098','3044463534','DESOCUPADOS','Callcenter00',73,51,'ellibertador322@ngsoabogados.com','MARELEIMYS JUDITH CARO BOLAÑO','286','2025-10-17 02:49:30','2025-10-17 02:49:30'),(68,'Irma Rosa','Diaz Barreto','52837437','3015588649','DESISTIDOS','Callcenter00',67,44,'ellibertador48@ngsoabogados.com','IRMA ROSA DIAZ BARRETO','309','2025-10-17 02:59:07','2025-10-17 02:59:07'),(69,'Ginna Alejandra','Perez Cifuentes','1018439309','3013364721','DESISTIDOS','Callcenter00',74,43,'ellibertador3@ngsoabogados.com','GINNA ALEJANDRA PEREZ CIFUENTES','308','2025-10-17 03:02:40','2025-10-17 03:02:40'),(70,'Edison Esneyder','Montaño Abril','1070306099','3013951870','LIDER','callcenter26',64,62,'directorestrategia@ngsoabogados.com','N/A','000','2025-10-17 03:18:03','2025-10-17 03:18:03'),(71,'Yerfin Disney','Ardila Otalora','1097332997','3013053846','LIDER','callcenter43',65,59,'lidercartera@ngsoabogados.com','N/A','000','2025-10-17 03:19:44','2025-10-17 03:19:44'),(72,'Cristian David','Diaz Melo','1016106935','3204853696','DESISTIDOS','callcenter19',59,37,'ellibertador68@ngsoabogados.com','CRISTIAN DAVID DIAZ MELO','321','2025-10-17 17:41:48','2025-10-17 17:41:48'),(73,'LAURA XIMENA','ALARCON PARRA','1018454151','3013951870','CASTIGO','callcenter007',71,40,'ellibertador56@ngsoabogados.com','LAURA XIMENA ALARCON PARRA','352','2025-10-17 17:43:19','2025-10-17 17:43:19'),(74,'Laura Daniela','Zambrano Solis','1000225422','3332676661','DESOCUPADOS','Callcenter00',60,54,'ellibertador8@ngsoabogados.com','LAURA DANIELA ZAMBRANO SOLIS','330','2025-10-17 17:45:07','2025-10-17 17:45:07'),(75,'Laura Liliana','Cative Rojas','1019152466','3022886876','DESOCUPADOS','Callcenter47',61,57,'ellibertador19@ngsoabogados.com','LAURA LILIANA CATIVE ROJAS','349','2025-10-17 17:46:53','2025-10-17 17:46:53'),(76,'Juan Daniel','Cadena Sarmiento','1001193173','3332676661','DESOCUPADOS 2022-2023','Callcenter59',62,58,'ellibertador12@ngsoabogados.com','JUAN DANIEL CADENA SARMIENTO','355','2025-10-17 17:48:40','2025-10-17 17:48:40');
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

-- Dump completed on 2025-10-17 11:50:54
