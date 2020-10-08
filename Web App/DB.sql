-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 18, 2020 at 05:58 PM
-- Server version: 10.3.16-MariaDB
-- PHP Version: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `id7253588_car_park`
--

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `ID` int(11) NOT NULL,
  `Time` datetime DEFAULT current_timestamp(),
  `Longitude` float NOT NULL,
  `Latitude` float NOT NULL,
  `Speed` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '---',
  `Route` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`ID`, `Time`, `Longitude`, `Latitude`, `Speed`, `Route`) VALUES
(1, '2019-11-15 14:45:59', 81.0443, 6.86576, '10', 2),
(2, '2019-11-05 10:54:47', 79.8775, 6.87048, '100', 1),
(3, '2019-11-18 09:11:23', 25, 25, '2', 2);

--
-- Triggers `location`
--
DELIMITER $$
CREATE TRIGGER `Location` AFTER UPDATE ON `location` FOR EACH ROW BEGIN INSERT INTO log_location(TimeStamp,ID,Longitude,Latitude) values (TIMEDIFF(NEW.Time,OLD.Time),NEW.ID,NEW.Longitude,NEW.Latitude); END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `log_location`
--

CREATE TABLE `log_location` (
  `TimeStamp` time NOT NULL,
  `ID` int(11) NOT NULL,
  `Longitude` float NOT NULL,
  `Latitude` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `log_location`
--

INSERT INTO `log_location` (`TimeStamp`, `ID`, `Longitude`, `Latitude`) VALUES
('00:01:00', 1, 81.0443, 6.86576),
('24:00:00', 1, 81.0443, 6.86576),
('192:00:00', 1, 81.0443, 6.86576),
('321:14:29', 3, 25, 25),
('00:00:39', 3, 25, 25),
('00:04:09', 3, 25, 25),
('00:12:51', 3, 25, 25),
('00:00:49', 3, 25, 25),
('00:00:32', 3, 25, 25);

-- --------------------------------------------------------

--
-- Table structure for table `log_request`
--

CREATE TABLE `log_request` (
  `TimeStamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  `UserID` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `Longitude` float NOT NULL,
  `Latitude` float NOT NULL,
  `ReqMode` varchar(2) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `log_request`
--

INSERT INTO `log_request` (`TimeStamp`, `UserID`, `Longitude`, `Latitude`, `ReqMode`) VALUES
('2019-11-06 17:20:00', 'Lasith95', 79.8775, 6.87051, '1'),
('2019-11-06 17:20:27', 'Lasith95', 79.9053, 6.8608, '1'),
('2019-11-06 17:52:13', 'Lasith95', 79.9053, 6.8608, '1'),
('2020-05-18 19:49:08', 'Lasith95', 80.0088, 6.90161, '1'),
('2020-05-18 19:49:37', 'Lasith95', 80.0088, 6.90161, '2'),
('2020-05-18 20:00:34', 'Lasith95', 79.8841, 6.86786, '1'),
('2020-05-18 20:00:46', 'Lasith95', 79.8841, 6.86786, '1'),
('2020-05-18 20:10:22', 'Lasith95', 79.8879, 6.87605, '1'),
('2020-05-18 20:10:40', 'Lasith95', 79.8879, 6.87605, '1'),
('2020-05-18 20:11:09', 'Lasith95', 79.8848, 6.87332, '1'),
('2020-05-18 20:11:20', 'Lasith95', 79.8848, 6.87332, '2'),
('2020-05-18 20:15:09', 'Lasith95', 79.8879, 6.87605, '2'),
('2020-05-18 20:15:57', 'Lasith95', 79.8879, 6.87605, '4');

-- --------------------------------------------------------

--
-- Table structure for table `log_sms`
--

CREATE TABLE `log_sms` (
  `TimeStamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `UserID` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `Longitude` float NOT NULL,
  `Latitude` float NOT NULL,
  `Distance` float NOT NULL,
  `Phone` int(15) NOT NULL,
  `ReqMode` int(2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `log_sms`
--

INSERT INTO `log_sms` (`TimeStamp`, `UserID`, `Longitude`, `Latitude`, `Distance`, `Phone`, `ReqMode`) VALUES
('2019-11-06 05:22:13', 'Lasith95', 79.9053, 6.8608, 1000, 717378271, 1),
('2020-05-18 14:30:46', 'Lasith95', 79.8841, 6.86786, 4000, 717378271, 1),
('2020-05-18 14:40:40', 'Lasith95', 79.8879, 6.87605, 3000, 717378271, 1),
('2020-05-18 14:41:09', 'Lasith95', 79.8848, 6.87332, 4000, 717378271, 1),
('2020-05-18 14:41:20', 'Lasith95', 79.8848, 6.87332, 4000, 717378271, 2),
('2020-05-18 14:45:09', 'Lasith95', 79.8879, 6.87605, 3000, 717378271, 2),
('2020-05-18 14:45:57', 'Lasith95', 79.8879, 6.87605, 3000, 717378271, 4);

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `UserID` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `Feedback` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `report`
--

INSERT INTO `report` (`UserID`, `Feedback`) VALUES
('1', 'faaef'),
('Lasith95', 'Test');

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `Time` datetime NOT NULL DEFAULT current_timestamp(),
  `UserID` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `Longitude` float NOT NULL,
  `Latitude` float NOT NULL,
  `ReqMode` varchar(2) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `request`
--

INSERT INTO `request` (`Time`, `UserID`, `Longitude`, `Latitude`, `ReqMode`) VALUES
('2020-05-18 20:15:57', 'Lasith95', 79.8879, 6.87605, '4');

--
-- Triggers `request`
--
DELIMITER $$
CREATE TRIGGER `Request` AFTER INSERT ON `request` FOR EACH ROW BEGIN INSERT INTO log_request(TimeStamp,UserID,Longitude,Latitude,ReqMode) 
values (NEW.Time,NEW.UserID,NEW.Longitude,NEW.Latitude,NEW.ReqMode);END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `sms`
--

CREATE TABLE `sms` (
  `UserID` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `Longitude` float NOT NULL,
  `Latitude` float NOT NULL,
  `G_Distance` float NOT NULL,
  `Radius` float NOT NULL,
  `phone` int(15) NOT NULL,
  `ReqMode` int(11) NOT NULL DEFAULT 4
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sms`
--

INSERT INTO `sms` (`UserID`, `Longitude`, `Latitude`, `G_Distance`, `Radius`, `phone`, `ReqMode`) VALUES
('Lasith95', 79.8879, 6.87605, 0.0009, 3000, 717378271, 4);

--
-- Triggers `sms`
--
DELIMITER $$
CREATE TRIGGER `SMS` AFTER INSERT ON `sms` FOR EACH ROW BEGIN INSERT INTO log_sms(TimeStamp,UserID,Longitude,Latitude,ReqMode,Distance,Phone) 
values (now(),NEW.UserID,NEW.Longitude,NEW.Latitude,NEW.ReqMode,NEW.Radius,NEW.phone);END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `NIC` varchar(15) NOT NULL,
  `First` varchar(20) NOT NULL,
  `Last` varchar(20) NOT NULL,
  `Password` varchar(15) NOT NULL,
  `Email` varchar(40) NOT NULL,
  `Cont_No` int(10) NOT NULL,
  `Address` text NOT NULL,
  `Index_No` varchar(30) DEFAULT NULL,
  `UserID` varchar(15) DEFAULT NULL,
  `User_Type` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`NIC`, `First`, `Last`, `Password`, `Email`, `Cont_No`, `Address`, `Index_No`, `UserID`, `User_Type`) VALUES
('703456789v', 'Kasun', 'Wickramarathna', '1234567', 'kasunw@sltc.ac.lk', 719563271, 'Meepe', '30', 'kasunw', 'Staff'),
('783121425V', 'Udesh', 'Oruthota', 'testudesh', 'udesho@sltc.ac.lk', 713351730, 'Kottawa', '010', 'udesh', 'Staff'),
('803422206v', 'Imran', 'Uvais', '0772266015', 'imranu@sltc.ac.lk', 772266015, '', '035', 'imranuvais', 'Staff'),
('861093026v', 'Priyashantha', 'Tennakoon', '1986418', 'priyashanthat@sltc.ac.lk', 772258868, 'Meepe, Padukka', '58', 'tpriyashan', 'Staff'),
('896860682v', 'Chathuri', 'Saranga', 'Chasara@89', 'chathrir@sltc.ac.lk', 711577690, 'Meepe, padukaka', '', 'Chathurir', 'Staff'),
('927120062V', 'Anupama', 'Thabrew', 'kastdd92', 'anupamat@sltc.ac.lk', 779500475, 'Kottawa', '', 'kasthabrew', 'Staff'),
('941001505v', 'Hassaan', 'Hydher', 'shuttle123', 'hassaanh@sltc.ac.lk', 711252965, 'Meepe padukka', '61', 'H_Hassaan', 'Staff'),
('941390463v', 'Shanika', 'Uminda', '12345678', 'shanika199@gmail.com', 712347073, '44,balanagala,katugasthota', 'bsc/ee/2016/02/0014', 'umi', 'Student'),
('942021094v', 'Uchitha', 'Dharmarathne', '123', 'nileshanadharmarathne@gmail.com', 702083455, 'no 6i.', 'BSc/ee/10', 'Uchi', 'Student'),
('952021075v', 'Lasitha', 'Jayawardana', '19360', 'unlimitdragon@gmail.com', 717378271, 'No 12, Elotuwa, Hatharaliyadda.', 'Bsc-ee-2016-02-0097', 'Lasith95', NULL),
('963331819v', 'Malindu', 'Kumarasiri', '19961128Aa', 'mdilshanka@gmail.com', 717891902, 'no:52,villuwa road, puttalam', 'Bsc-ee-2016-02-0073', 'Malindudk', 'Student'),
('987580038v', 'Sandulika', 'Sadamini', '19961128', 'hnmalindudk@gmail.com', 768666826, 'No:52,villuwa road,puttalam', '873', 'Boobu', 'Student');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`UserID`);

--
-- Indexes for table `sms`
--
ALTER TABLE `sms`
  ADD PRIMARY KEY (`UserID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD UNIQUE KEY `NIC_2` (`NIC`),
  ADD UNIQUE KEY `NIC` (`Email`) USING BTREE,
  ADD UNIQUE KEY `UserID` (`UserID`) USING BTREE;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sms`
--
ALTER TABLE `sms`
  ADD CONSTRAINT `sms_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `request` (`UserID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
