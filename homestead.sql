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

/*Table structure for table `cache` */

DROP TABLE IF EXISTS `cache`;

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `cache` */

/*Table structure for table `cache_locks` */

DROP TABLE IF EXISTS `cache_locks`;

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `cache_locks` */

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`migration`,`batch`) values 
(1,'2014_10_12_000000_create_users_table',1),
(2,'2014_10_12_100000_create_password_resets_table',1),
(3,'2018_12_10_125521_create_opd_table',1),
(4,'2018_12_10_125521_create_outlets_table',1),
(5,'2024_06_02_094420_create_cache_table',1);

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `opd` */

insert  into `opd`(`id_opd`,`nama_opd`,`sub_opd`,`upt`,`creator_id`,`created_at`,`updated_at`) values 
(1,'SEKRETARIAT DAERAH','BAGIAN UMUM','BAGIAN UMUM',1,'2024-06-02 16:58:34','2024-06-02 16:58:36'),
(4,'SEKRETARIAT DPRD','SEKRETARIAT DPRD','SEKRETARIAT DPRD',1,'2024-06-02 19:06:06','2024-06-02 19:06:09');

/*Table structure for table `outlets` */

DROP TABLE IF EXISTS `outlets`;

CREATE TABLE `outlets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_pemda` varchar(50) DEFAULT NULL,
  `name` varchar(60) NOT NULL,
  `kode_barang` varchar(20) DEFAULT NULL,
  `register` char(2) DEFAULT NULL,
  `luas` int(11) DEFAULT NULL,
  `tahun_pengadaan` char(4) DEFAULT NULL,
  `penggunaan` varchar(255) DEFAULT NULL,
  `harga` bigint(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `nomor_sertifikat` varchar(255) DEFAULT NULL,
  `tanggal_sertifikat` date DEFAULT NULL,
  `hak` char(20) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `latitude` varchar(15) DEFAULT NULL,
  `longitude` varchar(15) DEFAULT NULL,
  `polygon` longtext DEFAULT NULL,
  `id_opd` int(11) DEFAULT NULL,
  `creator_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `outlets_creator_id_foreign` (`creator_id`),
  CONSTRAINT `outlets_creator_id_foreign` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `outlets` */

insert  into `outlets`(`id`,`id_pemda`,`name`,`kode_barang`,`register`,`luas`,`tahun_pengadaan`,`penggunaan`,`harga`,`address`,`keterangan`,`nomor_sertifikat`,`tanggal_sertifikat`,`hak`,`file_path`,`latitude`,`longitude`,`polygon`,`id_opd`,`creator_id`,`created_at`,`updated_at`) values 
(1,'DATA 1','DATA OPD 1','123','1',400,'2024','Data',4000000,'DATA OPD 1','DATA OPD 1','1','2024-06-06','DATA OPD 1',NULL,NULL,NULL,'[[{\"lat\":-7.631708750707142,\"lng\":112.90307164192201},{\"lat\":-7.632197905324885,\"lng\":112.9037368297577},{\"lat\":-7.632708326938087,\"lng\":112.90298581123352}]]',1,1,'2024-06-06 07:15:39','2024-06-06 07:15:39'),
(2,'DATA 2','DATA OPD 2','123','1',400,'2024','DATA OPD 2',4000000,'DATA OPD 2','DATA OPD 2','1','2024-06-06','DATA OPD 2',NULL,NULL,NULL,'[[{\"lat\":-7.633622830804462,\"lng\":112.90086150169373},{\"lat\":-7.633984378305071,\"lng\":112.9014837741852},{\"lat\":-7.634792542200722,\"lng\":112.90086150169373},{\"lat\":-7.634601135100366,\"lng\":112.90034651756288}]]',4,1,'2024-06-06 07:16:59','2024-06-06 07:16:59'),
(3,'DATA 3','DATA OPD 3','123','1',400,'2024','DATA OPD 3',4000000,'DATA OPD 3','DATA OPD 1','1','2024-06-06','DATA OPD 3',NULL,NULL,NULL,'[[{\"lat\":-7.63013494509735,\"lng\":112.90109753608705},{\"lat\":-7.630411424880585,\"lng\":112.90169835090639},{\"lat\":-7.630177480460248,\"lng\":112.90189146995546},{\"lat\":-7.62990100052577,\"lng\":112.90133357048036}]]',4,1,'2024-06-06 07:18:24','2024-06-06 07:18:24');

/*Table structure for table `password_resets` */

DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `password_resets` */

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`email`,`email_verified_at`,`password`,`remember_token`,`created_at`,`updated_at`) values 
(1,'Admin Demo','admin@mail.com',NULL,'$2y$10$4Y0a5DJmtLwEUcLQY/dvG.ox3soHVNaOm3NHgoGr9tWJ77zIvc3di',NULL,'2024-06-02 09:51:08','2024-06-02 09:51:08');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
