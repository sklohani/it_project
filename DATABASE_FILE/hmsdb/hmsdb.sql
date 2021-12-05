-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 05, 2021 at 05:02 PM
-- Server version: 5.7.31
-- PHP Version: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hmsdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` varchar(10) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` varchar(42) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `email`, `password`) VALUES
('admin001', 'admin@uohyd.ac.in', 'f351b297db4552eb708cdbf82b809531');

-- --------------------------------------------------------

--
-- Table structure for table `hostel_room`
--

DROP TABLE IF EXISTS `hostel_room`;
CREATE TABLE IF NOT EXISTS `hostel_room` (
  `hostel_name` varchar(15) NOT NULL,
  `room_no` varchar(5) NOT NULL,
  `available` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hostel_room`
--

INSERT INTO `hostel_room` (`hostel_name`, `room_no`, `available`) VALUES
('A', '101', 0),
('A', '102', 0),
('A', '103', 0),
('A', '104', 0),
('B', '101', 1),
('B', '102', 0),
('B', '103', 0),
('B', '104', 0);

-- --------------------------------------------------------

--
-- Table structure for table `pending_boarding`
--

DROP TABLE IF EXISTS `pending_boarding`;
CREATE TABLE IF NOT EXISTS `pending_boarding` (
  `id` varchar(10) NOT NULL,
  `name` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` varchar(42) NOT NULL,
  `type` varchar(15) NOT NULL,
  `to_date` date NOT NULL,
  `hostel` varchar(15) NOT NULL,
  `balance` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pending_boarding`
--

INSERT INTO `pending_boarding` (`id`, `name`, `email`, `password`, `type`, `to_date`, `hostel`, `balance`, `time`) VALUES
('19MCME04', 'S. Yogesh', '19mcme04@uohyd.ac.in', '81dc9bdb52d04dc20036dbd8313ed055', 'Student', '2022-01-29', 'A', 15000, '2021-12-05 16:57:29');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` varchar(10) NOT NULL,
  `name` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` varchar(42) NOT NULL,
  `type` varchar(15) NOT NULL,
  `from_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `to_date` date NOT NULL,
  `hostel` varchar(15) NOT NULL,
  `room` varchar(5) NOT NULL,
  `balance` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `password`, `type`, `from_date`, `to_date`, `hostel`, `room`, `balance`) VALUES
('19MCME08', 'Joydip B', '19mcme08@uohyd.ac.in', '81dc9bdb52d04dc20036dbd8313ed055', 'Student', '2021-12-05 14:49:34', '2021-12-25', 'B', '101', 11000);

--
-- Triggers `user`
--
DROP TRIGGER IF EXISTS `add_boarder`;
DELIMITER $$
CREATE TRIGGER `add_boarder` AFTER INSERT ON `user` FOR EACH ROW DELETE FROM pending_boarding WHERE id = NEW.id
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `leave_room`;
DELIMITER $$
CREATE TRIGGER `leave_room` BEFORE DELETE ON `user` FOR EACH ROW UPDATE hostel_room SET available = 0 WHERE hostel_name = OLD.hostel AND room_no = OLD.room
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `update_hostel`;
DELIMITER $$
CREATE TRIGGER `update_hostel` BEFORE INSERT ON `user` FOR EACH ROW UPDATE hostel_room SET available = 1 WHERE hostel_name = NEW.hostel AND room_no = NEW.room
$$
DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
