-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2+deb7u1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 22, 2014 at 12:43 AM
-- Server version: 5.5.40
-- PHP Version: 5.4.35-0+deb7u2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `pihome`
--

-- --------------------------------------------------------

--
-- Table structure for table `apscheduler_jobs`
--

CREATE TABLE IF NOT EXISTS `apscheduler_jobs` (
  `id` varchar(191) NOT NULL,
  `next_run_time` double DEFAULT NULL,
  `job_state` blob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ix_apscheduler_jobs_next_run_time` (`next_run_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Table structure for table `pi_admin`
--

CREATE TABLE IF NOT EXISTS `pi_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `pass` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `pi_admin`
--

INSERT INTO `pi_admin` (`id`, `user`, `pass`) VALUES
(1, 'admin', 'pihome');

-- --------------------------------------------------------

--
-- Table structure for table `pi_devices`
--

CREATE TABLE IF NOT EXISTS `pi_devices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `room_id` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `device` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `flags` varchar(55) COLLATE latin1_german1_ci NOT NULL,
  `code` varchar(55) COLLATE latin1_german1_ci NOT NULL DEFAULT '00000',
  `type` enum('simpleSwitch','delaySwitch') COLLATE latin1_german1_ci NOT NULL,
  `status` varchar(55) COLLATE latin1_german1_ci NOT NULL DEFAULT '0',
  `sort` varchar(55) COLLATE latin1_german1_ci NOT NULL DEFAULT '0',
  `enabled` varchar(55) COLLATE latin1_german1_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `device` (`device`),
  UNIQUE KEY `letter` (`flags`,`code`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=11 ;


-- --------------------------------------------------------

--
-- Table structure for table `pi_rooms`
--

CREATE TABLE IF NOT EXISTS `pi_rooms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `room` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=3 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
