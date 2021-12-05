-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 05, 2021 at 05:01 PM
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
-- Database: `lmsdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `ID` varchar(10) NOT NULL,
  `EMAIL` varchar(30) NOT NULL,
  `PASSWORD` varchar(42) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `UNIQUE` (`EMAIL`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`ID`, `EMAIL`, `PASSWORD`) VALUES
('admin001', 'admin@uohyd.ac.in', 'f351b297db4552eb708cdbf82b809531');

-- --------------------------------------------------------

--
-- Table structure for table `book`
--

DROP TABLE IF EXISTS `book`;
CREATE TABLE IF NOT EXISTS `book` (
  `ISBN` char(13) NOT NULL,
  `TITLE` varchar(100) NOT NULL,
  `AUTHOR` varchar(100) NOT NULL,
  `CATEGORY` varchar(30) NOT NULL,
  `PRICE` int(4) UNSIGNED NOT NULL,
  `COPIES` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`ISBN`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `book`
--

INSERT INTO `book` (`ISBN`, `TITLE`, `AUTHOR`, `CATEGORY`, `PRICE`, `COPIES`) VALUES
('9781453300923', 'Elementary Algebra', 'John Redden', 'Mathematics', 249, 8),
('9781484228951', 'Demystifying Internet of Things Security', 'Sunil Cheruvu, Anil Kumar, Ned Smith, David M. Wheeler', 'Technology', 805, 12),
('9782616052277', 'X-Men: God Loves, Man Kills', 'Chris', 'Comics', 299, 25);

-- --------------------------------------------------------

--
-- Table structure for table `book_issue_log`
--

DROP TABLE IF EXISTS `book_issue_log`;
CREATE TABLE IF NOT EXISTS `book_issue_log` (
  `issue_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(10) NOT NULL,
  `book_isbn` char(13) NOT NULL,
  `due_date` date NOT NULL,
  PRIMARY KEY (`issue_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `book_issue_log`
--

INSERT INTO `book_issue_log` (`issue_id`, `user_id`, `book_isbn`, `due_date`) VALUES
(3, '19MCME08', '9781453300923', '2021-12-12');

--
-- Triggers `book_issue_log`
--
DROP TRIGGER IF EXISTS `issue_book_delete_request`;
DELIMITER $$
CREATE TRIGGER `issue_book_delete_request` BEFORE INSERT ON `book_issue_log` FOR EACH ROW DELETE FROM pending_book_requests WHERE user_id = NEW.user_id AND book_isbn = NEW.book_isbn
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `issue_book_due_date`;
DELIMITER $$
CREATE TRIGGER `issue_book_due_date` BEFORE INSERT ON `book_issue_log` FOR EACH ROW SET NEW.due_date = DATE_ADD(CURRENT_DATE, INTERVAL 7 DAY)
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `issue_book_update_balance`;
DELIMITER $$
CREATE TRIGGER `issue_book_update_balance` BEFORE INSERT ON `book_issue_log` FOR EACH ROW UPDATE user SET balance = balance - (SELECT price FROM book WHERE isbn = NEW.book_isbn) WHERE id = NEW.user_id
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `issue_book_update_copies`;
DELIMITER $$
CREATE TRIGGER `issue_book_update_copies` BEFORE INSERT ON `book_issue_log` FOR EACH ROW UPDATE book SET copies = copies - 1 WHERE isbn = NEW.book_isbn
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `return_book_update_copies`;
DELIMITER $$
CREATE TRIGGER `return_book_update_copies` BEFORE DELETE ON `book_issue_log` FOR EACH ROW UPDATE book SET copies = copies + 1 WHERE isbn = OLD.book_isbn
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `return_book_update_user_balance`;
DELIMITER $$
CREATE TRIGGER `return_book_update_user_balance` BEFORE DELETE ON `book_issue_log` FOR EACH ROW UPDATE USER SET balance = balance + (SELECT price FROM book WHERE isbn = OLD.book_isbn) WHERE id = OLD.user_id
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `pending_book_requests`
--

DROP TABLE IF EXISTS `pending_book_requests`;
CREATE TABLE IF NOT EXISTS `pending_book_requests` (
  `request_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(10) NOT NULL,
  `book_isbn` char(13) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`request_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pending_registrations`
--

DROP TABLE IF EXISTS `pending_registrations`;
CREATE TABLE IF NOT EXISTS `pending_registrations` (
  `ID` varchar(10) NOT NULL,
  `NAME` varchar(30) NOT NULL,
  `EMAIL` varchar(30) NOT NULL,
  `PASSWORD` varchar(42) NOT NULL,
  `TYPE` varchar(15) NOT NULL,
  `BALANCE` int(11) NOT NULL,
  `TIME` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `UNIQUE` (`EMAIL`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pending_registrations`
--

INSERT INTO `pending_registrations` (`ID`, `NAME`, `EMAIL`, `PASSWORD`, `TYPE`, `BALANCE`, `TIME`) VALUES
('19MCME12', 'Meher Chaitanya', 'meherchaitanya18802@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'Student', 600, '2021-12-05 09:57:02');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `ID` varchar(10) NOT NULL,
  `NAME` varchar(30) NOT NULL,
  `EMAIL` varchar(30) NOT NULL,
  `PASSWORD` varchar(42) NOT NULL,
  `TYPE` varchar(10) NOT NULL,
  `BALANCE` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `UNIQUE` (`EMAIL`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`ID`, `NAME`, `EMAIL`, `PASSWORD`, `TYPE`, `BALANCE`) VALUES
('19MCME16', 'Shobhit Kumar', '19mcme16@uohyd.ac.in', '81dc9bdb52d04dc20036dbd8313ed055', 'Student', 1051),
('19MCME04', 'S. Yogesh', '19mcme04@uohyd.ac.in', '81dc9bdb52d04dc20036dbd8313ed055', 'Student', 1200),
('19MCME08', 'Joydip B', '19mcme08@uohyd.ac.in', '81dc9bdb52d04dc20036dbd8313ed055', 'Student', 1251);

--
-- Triggers `user`
--
DROP TRIGGER IF EXISTS `add_member`;
DELIMITER $$
CREATE TRIGGER `add_member` AFTER INSERT ON `user` FOR EACH ROW DELETE FROM pending_registrations WHERE id = NEW.id
$$
DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
