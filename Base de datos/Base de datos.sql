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
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (7,'0001_01_01_000000_create_users_table',1),(8,'0001_01_01_000001_create_cache_table',1),(9,'0001_01_01_000002_create_jobs_table',1),(10,'2024_07_08_160000_add_nombre_usuario_huella_to_usuarios_table',2);
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
INSERT INTO `sessions` VALUES ('fY4zf3PIH4MQIliGocDlyfVmFq2nsRoeZr10UX2n',NULL,'127.0.0.1','PostmanRuntime/7.44.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTjdGc01QMUFCOVVscjM5Q201UUpLWmtxaFJzcFA2U2ZsSHpaSEZPVSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1752165603),('svDOfJBEqoZV19Nyq0C3tFQLWr7UhEtdOGYmFzOG',NULL,'127.0.0.1','PostmanRuntime/7.44.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMUQ4UWRxeDg5TG1HVFRkdHp4cWp6M0tNcmZwQ3FjRU1RMnV1V0ZWdCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1752249898);
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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombres` varchar(30) DEFAULT NULL,
  `apellidos` varchar(30) DEFAULT NULL,
  `cedula` int(11) DEFAULT NULL,
  `telefono` varchar(10) DEFAULT NULL,
  `cartera` varchar(30) DEFAULT NULL,
  `numero_equipo` varchar(20) DEFAULT NULL,
  `usuario_equipo` varchar(20) DEFAULT NULL,
  `clave_equipo` varchar(20) DEFAULT NULL,
  `usuario_huella` varchar(20) DEFAULT NULL,
  `clave_huella` varchar(20) DEFAULT NULL,
  `correo` varchar(40) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `nombre_usuario_huella` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'Diana Alexandra','Hernandez',1012335175,'3013053846','DESOCUPADOS','callcenter43','supervisor cartera','NGSO2025*+','1012335175','Ngso2025*','lidercartera@ngsoabogados.com','2025-07-11 16:25:29','nn',NULL),(2,'Paula Alejandra','Niño Peña',1000625517,'3022886876','DESOCUPADOS 2022-2023','callcente45','ellibertador10','Ngso2025**','1000130043','Ngso2025*+','ellobertador42@ngsoabogados.com','2025-07-11 16:18:29','KAROL STEFANY GASCA DIAZ',NULL),(3,'Andres Felipe','Vargas Ramirez',1014235551,'3022886876','DESOCUPADOS','callcente44','ellibertador35','Ngso*+++','1000620841','Ngso2025*','ellibertador11@ngsoabogados.com','2025-07-11 13:19:14','DENNIS ALEJANDRA MARTINEZ ESCOBAR',NULL),(4,'Karen Margarita','Castellar Iriarte',1129508557,'3022886876','DESOCUPADOS 2022-2023','callcenter59','ellibertador60','NGSO2024*+','1014190282','Ngso2025','ellibertador60@ngsoabogados.com','2025-07-11 16:00:16','BRAYAN STEVEN MOLANO CUBILLOS',NULL),(5,'Luis Armando','Leon Cañon',1001216235,'3054119718','DESOCUPADOS','callcenter47','ellibertador49','Colombia2025','1001216235','Ngso2025','ellibertador9@ngsoabogados.com','2025-07-11 13:18:56','LUIS ARMANDO LEON CAÑON',NULL),(6,'Nancy Alejandra','Gonzales',52879254,'3054119718','DESOCUPADOS','callcenter37','ellibertador5','Ngso2025/-','52879254','Ngso2025*','ellibertador18@ngsoabogados.com','2025-07-11 13:20:25','NANCY ALEJANDRA GONZALEZ',NULL),(7,'Luz Adriana','Linares Lagos',1033684323,'3044463534','DESOCUPADOS','callcenter35','ellibertador24','Ngso2025**','1033684323','Colombia12','elibertador58@ngsoabogados.com','2025-07-11 13:20:13','LUZ ADRIANA LINARES LAGOS',NULL),(8,'Juliana Andrea','Cortes Roncancion',1000004978,'3022886876','DESOCUPADOS','callcenter38','ellibertsdor11','Ngso2025*/','100004978','Ngso2025*','ellibertador31@ngsoabogados.com','2025-07-11 13:20:02','JULIANA ANDREA CORTES',NULL),(9,'Julieth Alexandra','Castiblanco Rincon',1000250399,'3044463534','DESOCUPADOS','callcenter33','ellibertador16','Ngso2025*-','1000250399','Ngso2025*+','ellibertador36@ngsoabogados.com','2025-07-11 13:19:52','JULIETH ALEXANDRA CASTIBLANCO RINCON',NULL),(10,'Islena Paola','Acosta Salgado',1103111733,'3044463534','DESOCUPADOS','callcenter32','ellibertador22','NGSO2025*+','6479682','Carmona123456*','ellibertador22@ngsoabogados.com','2025-07-11 13:19:37','CAMILA  FERNANDEZ CARMONA',NULL),(11,'Jeimy Andrea','Rodríguez Cañón',1072190152,'0000000000','Desistidos-Nuevos-Vigente','callcenter31','lidercartera','NHSO2025+-','1072190152','Ngso2024+','lidercartera2@ngsoabogados.com','2025-07-11 15:39:48','No asignado',NULL),(12,'Angie Lorena','Hernandez Castiblanco',1001275619,'3105763468','DESISTIDOS','callcenter21','ellibertador27','ngso2025+*','1001275619','Ngso2025+*','ellibertador1@ngsoabogados.com','2025-07-11 13:21:10','ANGIE LORENA HERNANDEZ CASTIBLANCO',NULL),(13,'Yeritson Adrian','Vega Acero',1014284618,'3154970039','DESISTIDOS','callcenter24','ellibertador3','NGSO2023*','1014284618','Ngso2025-','elliberador67@ngsoabogados.com','2025-07-11 13:20:59','YERITSON ADRIAN VEGA ACERO',NULL),(14,'Danery Yibeth','Martinez Martinez',1015449218,'3105763468','DESISTIDOS','callcenter25','ellibertador12','Ngso2025**','1015449218','Ngso2025*+','ellibertador12@ngsoabogados.com','2025-07-11 13:20:49','DANERY YIBETH MARTINEZ MARTINEZ',NULL),(15,'Natalia Ines','Nisperuza Sanchez',1138074284,'3204840614','DESISTIDOS','callcenter22','ellibertador46','ngso2025++','1138074284','Ngso2025*','elliberador47@ngsoabogados.com','2025-07-11 13:20:40','NATALIA INES NISPERUZA SANCHEZ',NULL),(16,'Valery Brillith','Rincon Linares',1033685482,'3154970039','DESISTIDOS','callcenter23','ellibertador7','NGSO2026/','1033685482','Ngso2025//','ellibertador5@ngsoabogados.com','2025-07-11 13:22:16','VALERY BRILLIT RINCON LINARES',NULL),(17,'Gabriela','Moreno Hernandez',1025527753,'3204840614','DESISTIDOS','callcenter5','ellibertador33','Ngso2024**','1025527753','Ngso2025','ellibertador30@ngsoabogados.com','2025-07-11 13:22:06','GABRIELA  MORENO HERNANDEZ',NULL),(18,'Johan Camilo','Avila Bohorquez',1001116838,'3013364721','DESISTIDOS','callcenter14','ellibertador54','NGSO2024*+','1001116838','Ngso2024*+','ellibertador45@ngsoabogados.com','2025-07-11 13:21:55','JOHAN CAMILO AVILA BOHORQUEZ',NULL),(19,'Juan Manuel','Bermudez Correa',1000727404,'3022887255','DESISTIDOS','callcenter12','ellibertador17','Ngso2025*-+','1000727404','Ngso2025*-','ellibertador17@ngsoabogados.com','2025-07-11 13:21:45','JUAN MANUEL BERMUDEZ CORREA',NULL),(20,'Diana Rocio','Naranjo Hernandez',52756032,'3022887285','DESISTIDOS','callcenter11','ellibertador32','Ngso.2025***','52756032','Naranjo321*','ellibertador46@ngsoabogados.com','2025-07-11 15:16:01','DIANA ROCIO NARANJO HERNANDEZ',NULL),(21,'Carol Tatiana','Yepes Betancourth',1024593276,'3022887285','DESISTIDOS','callcenter16','ellibertador13','Ngso2025*','1024593276','Yepes2025','ellibertador70@ngsoabogados.com','2025-07-11 13:22:35','CAROL TATIANA YEPEZ BETANCOURTH',NULL),(22,'Cristian David','Diaz Melo',1016106935,'3204853696','DESISTIDOS','callcenter19','ellibertador47','Ngso2024*','1016106935','Ngso2025***','ellibertador68@ngsoabogados.com','2025-07-11 13:22:47','CRISTIAN DAVID DIAZ MELO',NULL),(23,'Diego Alexander','Vargas Pinzon',1020721831,'3013951870','CASTIGO','callcenter1','ellibertador25','NGSO2025*-','1020721831','Colombia1+','ellibertado29@ngsoabogados.com','2025-07-11 13:22:59','DIEGO ALEXANDER VARGAS PINZON',NULL),(24,'Angela Patricia','Diaz Fernandez',1018441492,'3013951870','CASTIGO','NN','ellibertador50','NGSO2025*+','1027401193','Ngso2025','ellibertador4@ngsoabogados.com','2025-07-11 13:23:10','MARIA CAMILA CARRILLO AVILA',NULL),(25,'Luisa Fernanda','Beltran Guescot',1019073284,'3013951870','CASTIGO','callcenter54','ellibertador36','NGSO2025++','1019073284','Ngso2025++','ellibertador28@ngsoabogados.com','2025-07-11 13:23:21','LUISA FERNANDA BELTRAN GUESCOT',NULL),(26,'Maria Ximena','Rosas Matapi',1000732395,'3013951870','CASTIGO','callcenter5','ellibertador15','Ngso2024*','1000732395','Ngso2025','elinerrador56@ngsoabogados.com','2025-07-11 13:33:02','MARIA XIMENA ROSAS MATAPI',NULL),(27,'Edison Esneyder','Montaño Abril',1070306099,'3013951870','TODAS','callcenter26','coord_prejuridico2','Ngso2025-*','1070306099','Ngso2025+','directoresrrategia@ngsoabogados.com','2025-07-11 15:40:01','No asignado',NULL),(29,'Andrea Carolina','Forero Obando',0,'0','CASTIGO','0','0','0','0','0','n@n','2025-07-11 14:32:40','LAURA ALEXANDRA CARO CRISTANCHO','2025-07-11 14:32:40'),(30,'Maria Fernanda','Duarte Mape',0,'0','DESISTIDOS','0','0','0','0','0','n@n','2025-07-11 15:05:07','MARIA FERNANDA DUARTE MAPE','2025-07-11 15:05:07'),(31,'Michel Vanesa','Muños Gutierrez',0,'0','DESISTIDOS','0','0','0','0','0','n@n','2025-07-11 15:06:48','JHAYLIN  SULVARAN RENGIFO','2025-07-11 15:06:48'),(32,'Ginna Alejandra','Perez Cifuentes',0,'0','DESISTIDOS','0','0','0','0','0','n@n','2025-07-11 15:08:05','GINNA ALEJANDRA PEREZ CIFUENTES','2025-07-11 15:08:05'),(33,'Irma Rosa','Diaz Barreto',0,'0','DESISTIDOS','0','0','0','0','0','n@n','2025-07-11 15:09:19','IRMA ROSA DIAZ BARRETO','2025-07-11 15:09:19'),(34,'Nicoll Dallan','Dominguez Carrasco',0,'0','DESISTIDOS','0','0','0','0','0','n@n','2025-07-11 15:10:53','NICOL DALLAN DOMINGUEZ CARRASCO','2025-07-11 15:10:53'),(35,'Maria Camila','Millan Cedeno',0,'0','DESISTIDOS','0','0','0','0','0','n@n','2025-07-11 15:11:52','MARIA CAMILA MILLAN CEDENO','2025-07-11 15:11:52'),(36,'Sandi Marcela','Burgos Pineda',0,'0','DESISTIDOS','0','0','0','0','0','n@n','2025-07-11 15:13:00','SANDI MARCELA BURGOS PINEDA','2025-07-11 15:13:00'),(37,'Harvy Anyinzan','Trujillo Camargo',0,'0','DESISTIDOS','0','0','0','0','0','n@n','2025-07-11 15:14:02','YURI VANESSA PANQUEVA ARAGON','2025-07-11 15:14:02'),(38,'Maria Alejandra','Acosta Blanco',0,'0','DESISTIDOS','0','0','0','0','0','N@N','2025-07-11 15:15:34','MARIA ALEJANDRA ACOSTA BLANCO','2025-07-11 15:15:34'),(39,'Sandra milena','Hernandez Corredor',0,'0','DESOCUPADOS','0','0','0','0','0','n@n','2025-07-11 15:17:34','SARA NATALIA MALDONADO MOYANO','2025-07-11 15:17:34'),(40,'Mareleimys Judith','Caro Bolaño',0,'0','DESOCUPADOS','0','0','0','0','0','n@n','2025-07-11 15:18:47','MARELEIMYS JUDITH CARO BOLAÑO','2025-07-11 15:18:47'),(41,'Silena Maria','Suarez Sanchez',0,'0','SUPERNUMERARIO','0','0','0','0','0','n@n','2025-07-11 15:22:23','LUISA FERNANDA LIZCANO GARAVITO','2025-07-11 15:22:23'),(42,'Andres Felipe','Arguello orjuela',0,'0','DESOCUPADOS 2022-2023','0','0','0','0','0','n@n','2025-07-11 16:16:36','EIMY MARIANA RODRIGUEZ MOSQUERA','2025-07-11 15:23:48');
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

-- Dump completed on 2025-07-11 14:24:40
