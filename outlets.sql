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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `outlets` */

insert  into `outlets`(`id`,`id_pemda`,`name`,`kode_barang`,`register`,`luas`,`tahun_pengadaan`,`penggunaan`,`harga`,`address`,`keterangan`,`nomor_sertifikat`,`tanggal_sertifikat`,`hak`,`latitude`,`longitude`,`polygon`,`id_opd`,`creator_id`,`created_at`,`updated_at`) values 
(2,'01010010011000001','Tanah Bangunan Kantor Pemerintah','1.3.1.01.001.004.001','1',4098,'2002','Bangunan Kantor',1320000000,'Jl. Balaikota 11 Pasuruan','Bangunan Kantor','8','1987-01-21','Hak Pakai',NULL,NULL,'[[{\"lat\":-7.642016269888311,\"lng\":112.9119846224785},{\"lat\":-7.642069437613087,\"lng\":112.91209727525712},{\"lat\":-7.6423140090618435,\"lng\":112.91196316480638},{\"lat\":-7.642266158137229,\"lng\":112.91184514760972}]]',1,1,'2024-06-02 09:56:28','2024-06-02 09:56:28'),
(3,'04010010011000002','Tanah Kaveling (dst)','1.3.1.01.001.001.005','1',3209,'2014','Rumah Dinas Sekretaris Daerah',222592000,'Jl. Balaikota Pasuruan','Rumah Dinas Sekretaris Daerah','18','2001-07-11','Hak Pakai',NULL,NULL,'[[{\"lat\":-7.641058086663595,\"lng\":112.91187465190887},{\"lat\":-7.64114315521094,\"lng\":112.91202485561372},{\"lat\":-7.6415153299063485,\"lng\":112.9117673635483},{\"lat\":-7.641408994312183,\"lng\":112.91162788867952}]]',4,1,'2024-06-03 07:12:59','2024-06-03 07:12:59'),
(4,'04010010011000003','Tanah Bangunan Kantor Pemerintah','1.3.1.01.001.004.001','1',5460,'2002','Kantor Walikota Pasuruan',1556100000,'Jl. Pahlawan No. 28 - Pekuncen','Kantor Walikota Pasuruan','5','1987-01-21','Hak Pakai',NULL,NULL,'[[{\"lat\":-7.647726445697462,\"lng\":112.90842264890672},{\"lat\":-7.647667961974889,\"lng\":112.90832072496416},{\"lat\":-7.647572261320683,\"lng\":112.90837973356248},{\"lat\":-7.647317059471178,\"lng\":112.90850847959521},{\"lat\":-7.647375543241841,\"lng\":112.90864259004594}]]',1,1,'2024-06-03 14:09:18','2024-06-03 14:09:18'),
(5,'04010010011000004','Tanah Bangunan Kantor Pemerintah','1.3.1.01.001.004.001','3',568,'2006','Mapolsek Bugul Kidul',598000000,'Jl. Otto Iskandar Dinata - Blandongan','Mapolsek Bugul Kidul','19','2021-08-06','Hak Pakai',NULL,NULL,'[[{\"lat\":-7.666095254324631,\"lng\":112.92382389307024},{\"lat\":-7.666196267290867,\"lng\":112.92394727468493},{\"lat\":-7.666286647292955,\"lng\":112.92388826608659},{\"lat\":-7.666175001405229,\"lng\":112.92374879121783}]]',1,1,'2024-06-04 20:39:18','2024-06-04 20:39:18');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
