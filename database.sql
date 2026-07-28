-- MySQL dump 10.13  Distrib 8.0.42, for Win64 (x86_64)
--
-- Host: localhost    Database: my_store
-- ------------------------------------------------------
-- Server version	8.0.42

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
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Sinh Nhật','Trao gửi lời chúc tuổi mới rực rỡ, tươi vui và hạnh phúc.','2025-12-18 07:46:02'),(2,'Lễ Cưới','Biểu tượng của tình yêu vĩnh cửu và hạnh phúc viên mãn.','2025-12-18 07:46:02'),(3,'Kỉ Niệm','Hâm nóng cảm xúc và lưu giữ những khoảnh khắc ngọt ngào.','2025-12-18 07:46:02'),(4,'Khai Trương','Mang ý nghĩa đại cát đại lợi, thu hút tài lộc và may mắn.','2025-12-18 07:46:02');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `category` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` VALUES (1,'Khai Trương','Kệ hoa sang trọng chúc mừng hồng phát'),(2,'Lễ Cưới','Hoa cầm tay cô dâu và trang trí tiệc cưới'),(3,'Sinh Nhật','Bó hoa tươi thắm chúc mừng tuổi mới'),(4,'Khai Trương','Vòng hoa trang trọng thành kính phân ưu'),(5,'Kỉ Niệm','Các loại cây, hoa trồng chậu trang trí'),(6,' Khai Trương','Mô tả hoa khai trương'),(7,'Kỉ Niệm','Kệ hoa sang trọng');
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `user_id` int NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` tinyint DEFAULT '5',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `parent_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` VALUES (1,4,2,'Hoa đẹp',5,'2025-12-18 04:51:33',NULL),(4,13,2,'Hoa rất đẹp',5,'2025-12-18 23:27:27',NULL),(5,12,3,'Qúa lãng mạn',5,'2025-12-19 06:04:33',NULL),(6,4,1,'Cảm ơn bạn đã mua sản phẩm bên shop chúng tớ ! &lt;3',5,'2025-12-20 16:38:32',1);
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'info',
  `is_read` tinyint DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_details`
--

DROP TABLE IF EXISTS `order_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_details` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_details`
--

LOCK TABLES `order_details` WRITE;
/*!40000 ALTER TABLE `order_details` DISABLE KEYS */;
INSERT INTO `order_details` VALUES (1,1,5,1,500000.00),(57,47,2,1,350000.00),(58,48,6,1,550000.00),(59,48,4,1,500000.00),(60,49,7,1,450000.00),(61,50,8,1,1200000.00),(62,51,11,1,600000.00),(63,52,8,1,1200000.00),(64,53,10,1,850000.00),(65,54,13,1,950000.00),(66,54,12,1,1500000.00),(67,55,10,1,850000.00),(68,56,11,1,600000.00),(69,56,7,1,450000.00),(70,57,13,1,950000.00),(71,58,11,1,600000.00),(72,58,10,2,850000.00);
/*!40000 ALTER TABLE `order_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `price` int NOT NULL,
  `quantity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `customer_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `message_card` text COLLATE utf8mb4_unicode_ci,
  `delivery_date` datetime DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `total_price` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,2,'Nguyễn Đức Duy','0915136743','122/28/13 Bùi Đình Túy',NULL,NULL,500000.00,'cancelled','2025-12-17 20:11:57',0),(47,4,'Bùi Thị Trang','0862938679','khu hòa binh',NULL,'2025-12-31 23:50:00',350000.00,'completed','2025-12-19 16:50:38',0),(48,4,'Bùi Thị Trang','0915136743','122/28/13 Bùi Đình Túy','hello em','2025-12-22 23:53:00',1050000.00,'completed','2025-12-19 16:53:49',0),(49,4,'Bùi Thị Trang','0915136743','122/28/13 Bùi Đình Túy',NULL,'2025-12-30 00:02:00',450000.00,'completed','2025-12-19 17:02:12',0),(50,4,'Bùi Thị Trang','0915136743','122/28/13 Bùi Đình Túy',NULL,'2025-12-22 00:04:00',1200000.00,'completed','2025-12-19 17:04:18',0),(51,2,'Nguyễn Đức Duy','0915136743','122/28/13 Bùi Đình Túy',NULL,'2025-12-21 00:09:00',600000.00,'completed','2025-12-19 17:09:46',600000),(52,2,'Nguyễn Đức Duy','0915136743','122/28/13 Bùi Đình Túy','Chúc mừng','2025-12-23 02:22:00',1200000.00,'completed','2025-12-20 19:22:52',1200000),(53,4,'Bùi Thị Trang','0915136743','122/28/13 Bùi Đình Túy',NULL,'2025-12-24 02:08:00',850000.00,'completed','2025-12-22 19:09:14',850000),(54,2,'Nguyễn Đức Duy','0915136743','122/28/13 Bùi Đình Túy',NULL,'2025-12-26 13:05:00',2450000.00,'completed','2025-12-25 06:06:05',2450000),(55,4,'Bùi Thị Trang','0234556897','Bình Chánh','Hi','2025-12-26 17:24:00',850000.00,'processing','2025-12-25 07:24:50',850000),(56,2,'Nguyễn Đức Duy','0915136743','122/28/13 Bùi Đình Túy',NULL,'2026-01-02 07:13:00',1050000.00,'completed','2025-12-28 00:13:31',1050000),(57,2,'Nguyễn Đức Duy','0915136743','122/28/13 Bùi Đình Túy','abc','2026-01-08 11:05:00',950000.00,'completed','2026-01-02 04:05:55',950000),(58,2,'Nguyễn Đức Duy','0915136743','122/28/13 Bùi Đình Túy','Chúc mừng kỉ niệm','2026-01-13 11:37:00',2300000.00,'completed','2026-01-02 04:39:06',2300000);
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  `stock` int DEFAULT '10',
  PRIMARY KEY (`id`),
  KEY `fk_product_category` (`category_id`),
  CONSTRAINT `fk_product_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product`
--

LOCK TABLES `product` WRITE;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` VALUES (4,'Bó Hoa Hồng Đỏ','Hoa hồng tình yêu',500000.00,'uploads/1766008076_hoahongdo.jpg',1,10),(6,'Bó Hoa Nắng Hồng','Hoa hồng pink mix cùng cẩm tú cầu',550000.00,'uploads/1766044254_hoananghong.jpg',5,10),(7,'Giỏ Hoa Tuổi Thần Tiên','Tone màu pastel nhẹ nhàng',450000.00,'uploads/1766048864_hoathantien.jpg',6,10),(8,'Bó Hoa Cầm Tay Pure Love','Hoa linh lan trắng tinh khôi',1200000.00,'uploads/1766048830_purelove.jpg',2,10),(9,'Cổng Hoa Hạnh Phúc','Thiết kế hoa tươi trang trí tiệc cưới',5000000.00,'uploads/1766048804_hoahanhphuc.jpg',1,10),(10,'Hộp Hoa Mãi Bên Nhau','Hoa hồng đỏ vĩnh cửu',850000.00,'uploads/1766048781_hoamaibennhau.jpg',3,10),(11,'Bó Hoa Ghi Dấu Kỉ Niệm','Mix các loại hoa nhập khẩu',600000.00,'uploads/1766048744_hoaghidau.jpg',3,10),(12,'Lệ Kệ Hoa Phát Tài','Hoa lan hồ điệp và thiên điểu',1500000.00,'uploads/1766048706_hoaphattai.jpg',4,10),(13,'Giỏ Hoa Khởi Đầu Mới','Tone màu vàng rực rỡ may mắn',950000.00,'uploads/1766048671_hoakhoidaumoi.jpg',4,10);
/*!40000 ALTER TABLE `product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expiry` datetime DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','Administrator','admin@gmail.com','$2y$10$cad2jlPwZ8J0ErDfZpjRPO9D4yTUeHMu29DbvEHnB82CwjBsvwaDy','admin','2025-12-17 19:49:14','c95eab45e12b939df497c92a00e27a73b53c7dd67f8c22476cd905644919e092','2025-12-19 22:06:57','0915136743','122/28/13 Bùi Đình Túy'),(2,'ducduy','Nguyễn Đức Duy','ducduy@gmail.com','$2y$10$UACFZYRGhzxISlJw/nYtHOBrstbR4wAk5SK/JG/x6a4CEaUu2Z8MC','user','2025-12-17 19:55:07','882f3e856fceaa7f73d68025b44e752cea092a381a41f1462f950ef9dc535695','2026-01-02 11:40:43','0915136743','122/28/13 Bùi Đình Túy'),(3,'dat123','Trọng Đạt','dat123@gmail.com','$2y$10$Q0FEPp1hIedJMo0KZ1NNtuybUxEudkACzysdrRXQ9mlAAsVN2d8me','user','2025-12-18 17:37:13',NULL,NULL,NULL,NULL),(4,'buitr','Bùi Thị Trang','buitr@gmail.com','$2y$10$XobVXy5wor4YS/B1YE/7h.plmCaY36NW2lhFJ7KJSneOjcLLlhbJO','user','2025-12-19 15:04:03',NULL,NULL,'0123456789','122/28/13 Bùi Đình Túy');
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

-- Dump completed on 2026-01-02 14:48:11
