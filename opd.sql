/*
SQLyog Ultimate v13.1.1 (64 bit)
MySQL - 10.4.32-MariaDB : Database - homestead
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`homestead` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;

USE `homestead`;

/*Table structure for table `opd` */

DROP TABLE IF EXISTS `opd`;

CREATE TABLE `opd` (
  `id_opd` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama_opd` varchar(255) NOT NULL,
  `sub_opd` varchar(255) NOT NULL,
  `upt` varchar(255) NOT NULL,
  `creator_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_opd`),
  KEY `opd_creator_id_foreign` (`creator_id`),
  CONSTRAINT `opd_creator_id_foreign` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `opd` */

insert  into `opd`(`id_opd`,`nama_opd`,`sub_opd`,`upt`,`creator_id`,`created_at`,`updated_at`) values 
(1,'SEKRETARIAT DAERAH','BAGIAN UMUM','BAGIAN UMUM',1,'2024-06-02 16:58:34','2024-06-02 16:58:36'),
(4,'SEKRETARIAT DPRD','SEKRETARIAT DPRD','SEKRETARIAT DPRD',1,'2024-06-02 19:06:06','2024-06-02 19:06:09');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
