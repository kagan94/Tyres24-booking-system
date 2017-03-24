-- phpMyAdmin SQL Dump
-- version 4.0.10.10
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Час створення: Бер 23 2017 р., 05:39
-- Версія сервера: 5.6.26
-- Версія PHP: 5.6.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База даних: `bookings`
--

-- --------------------------------------------------------

--
-- Структура таблиці `booking`
--

CREATE TABLE IF NOT EXISTS `booking` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vehicleType` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `startDatetime` datetime NOT NULL,
  `endDatetime` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `details` varchar(2000) COLLATE utf8_unicode_ci NOT NULL,
  `datetimeAdded` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `datetimeModified` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `garageID` int(10) DEFAULT NULL,
  `lineID` int(10) DEFAULT NULL,
  `tyresQty` int(1) DEFAULT NULL,
  `occupySlots` int(1) DEFAULT NULL COMMENT 'Can be 0,1, or 2 (for truck)',
  `licencePlateNumber` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phoneNumber` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `needTyreStorage` int(1) DEFAULT NULL,
  `status` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'can be ''booked'', ''cancelled'', or ''removed''',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=55 ;

--
-- Дамп даних таблиці `booking`
--

INSERT INTO `booking` (`id`, `vehicleType`, `startDatetime`, `endDatetime`, `details`, `datetimeAdded`, `datetimeModified`, `garageID`, `lineID`, `tyresQty`, `occupySlots`, `licencePlateNumber`, `phoneNumber`, `needTyreStorage`, `status`) VALUES
(1, 'car', '2017-03-26 13:30:00', '2017-03-26 14:00:00', 'fdfsdfsd', '2017-03-23 01:43:11', '2017-03-23 01:43:11', 7, 18, 4, 0, 'AA12312', '312312312', 1, 'booked'),
(10, 'van', '2017-03-23 13:30:00', '2017-03-23 02:35:15', '312', '2017-03-23 02:35:15', '2017-03-23 02:35:15', 7, 0, 5, 5, 'AA12312', '312312312', 0, 'booked'),
(11, 'truck', '1111-11-11 00:00:00', '2017-03-23 02:35:15', '', '2017-03-23 02:35:15', '2017-03-23 02:35:15', 7, 0, 2, 5, 'AA12312', '312312312', 0, 'removed'),
(12, 'car', '2017-03-05 13:30:00', '2017-03-23 02:35:15', 'dsa', '2017-03-23 02:35:15', '2017-03-23 02:35:15', 7, 0, 312, 5, 'AA12312', '312312312', 0, 'cancelled'),
(13, 'car', '2017-03-05 13:30:00', '2017-03-23 02:35:15', 'das', '2017-03-23 02:35:15', '2017-03-23 02:35:15', 7, 0, 0, 5, 'AA12312', '312312312', 0, 'cancelled'),
(14, 'car', '2017-03-23 13:30:00', '2017-03-23 02:35:15', 'das111', '2017-03-23 02:35:15', '2017-03-23 02:35:15', 7, 0, 0, 2, 'AA12312', '312312312', 0, 'removed'),
(15, 'car', '2017-03-23 13:30:00', '2017-03-23 02:35:15', '231', '2017-03-23 02:35:15', '2017-03-23 02:35:15', 7, 0, 231, 2, 'AA12312', '312312312', 0, 'removed'),
(16, 'car', '2017-03-23 13:30:00', '2017-03-23 02:35:15', '', '2017-03-23 02:35:15', '2017-03-23 02:35:15', 8, 0, 0, 2, 'AA12312', '312312312', 0, 'booked'),
(17, 'car', '2017-03-23 13:30:00', '2017-03-23 02:35:15', '', '2017-03-23 02:35:15', '2017-03-23 02:35:15', 8, 0, 0, 3, 'AA12312', '312312312', 0, 'booked'),
(18, 'car', '2017-03-23 13:30:00', '2017-03-23 02:15:48', '2321', '2017-03-23 02:15:48', '2017-03-23 02:15:48', 8, 0, 312, 3, '123123', '312312', 0, 'booked'),
(19, 'truck', '2017-02-18 11:45:00', '2017-03-23 02:15:48', 'test', '2017-03-23 02:15:48', '2017-03-23 02:15:48', 8, 19, 312308, 3, '11111', '38097', 1, 'booked'),
(20, 'truck', '2017-02-24 23:30:00', '2017-03-23 02:15:48', '', '2017-03-23 02:15:48', '2017-03-23 02:15:48', 8, 23, 0, 3, 'AA9312AA', '333123', 1, 'booked'),
(47, 'car', '2017-03-22 15:30:00', '2017-03-23 02:14:11', '', '2017-03-23 02:14:11', '2017-03-23 02:14:11', 9, 0, 0, 4, 'AA12312', '321132', 0, 'booked'),
(48, 'car', '2017-03-22 16:00:00', '2017-03-23 02:14:14', '', '2017-03-23 02:14:14', '2017-03-23 02:14:14', 9, 0, 3, 4, 'AA12312', '321132', 0, 'booked'),
(49, 'truck', '2017-03-24 17:30:00', '2017-03-23 02:15:53', '', '2017-03-23 02:15:53', '2017-03-23 02:15:53', 8, 24, 0, 1, '4412312', '11111', 0, 'booked'),
(50, 'car', '2017-03-22 12:30:00', '2017-03-23 02:15:53', '', '2017-03-23 02:15:53', '2017-03-23 02:15:53', 9, 1, 2, 1, 'AA12312', '312312312', 0, 'booked'),
(51, 'car', '2017-03-23 13:30:00', '2017-03-23 02:15:53', '', '2017-03-23 02:15:53', '2017-03-23 02:15:53', 9, 24, 0, 1, 'AAAA', '312312', 0, 'booked'),
(52, 'car', '2017-03-23 22:39:00', '2017-03-23 02:15:53', '', '2017-03-23 02:15:53', '2017-03-23 02:15:53', 9, 1, 1, 1, '3312SA', '321312312', 0, 'booked'),
(53, 'car', '2017-03-24 02:17:00', '2017-03-24 02:47:00', '', '2017-03-23 02:17:30', NULL, 8, 1, 4, 1, 'SADSA', '31231231', 1, 'booked'),
(54, 'car', '2017-03-24 03:00:00', '2017-03-24 03:30:00', '', '2017-03-23 02:22:41', NULL, 7, 2, 5, 2, 'ASSD', '12312312', 1, 'booked');

-- --------------------------------------------------------

--
-- Структура таблиці `garage`
--

CREATE TABLE IF NOT EXISTS `garage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(15) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `hasTyreStorage` int(1) DEFAULT NULL COMMENT '0 - no, 1 - yes',
  `tyreSlots` int(3) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Дамп даних таблиці `garage`
--

INSERT INTO `garage` (`id`, `name`, `address`, `hasTyreStorage`, `tyreSlots`, `status`) VALUES
(1, '122', 'dasdas', 1, 200, 'removed'),
(2, '22', '11', 1, 51, 'removed'),
(3, 'test', 'test', 0, 0, 'removed'),
(4, 'AAa', 'Aa', 0, 0, 'removed'),
(5, 'Nõmme', 'test1', 1, 200, 'created'),
(6, 'Nõmme', 'test1', 1, 0, 'removed'),
(7, 'Mustamäe', 'test2', 1, 200, 'created'),
(8, 'Lasnamäe', '1', 0, 0, 'created'),
(9, 'Cars only', '1', 0, 0, 'removed');

-- --------------------------------------------------------

--
-- Структура таблиці `line`
--

CREATE TABLE IF NOT EXISTS `line` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `garageID` int(10) DEFAULT NULL,
  `uniqueID` int(2) NOT NULL,
  `canServeVansAndTrucks` int(1) DEFAULT NULL COMMENT '0 - no, 1 -yes',
  `status` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`,`uniqueID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

--
-- Дамп даних таблиці `line`
--

INSERT INTO `line` (`id`, `garageID`, `uniqueID`, `canServeVansAndTrucks`, `status`) VALUES
(1, 3, 0, 1, 'created'),
(5, 3, 5, 1, 'created'),
(6, 3, 1, 1, 'created'),
(7, 3, 1, 1, 'created'),
(8, 3, 5, 1, 'created'),
(9, 3, 1, 0, 'created'),
(10, 3, 2, 0, 'created'),
(11, 3, 3, 1, 'created'),
(12, 4, 55, 0, 'created'),
(13, 4, 7, 1, 'removed'),
(14, 3, 1, 1, 'created'),
(15, 5, 1, 0, 'created'),
(16, 5, 2, 1, 'created'),
(17, 7, 1, 0, 'created'),
(18, 7, 2, 1, 'created'),
(19, 8, 231, 0, 'removed'),
(20, 8, 0, 1, 'removed'),
(21, 8, 2, 1, 'removed'),
(22, 8, 1, 1, 'removed'),
(23, 8, 1, 0, 'created'),
(24, 9, 1, 0, 'created');

-- --------------------------------------------------------

--
-- Структура таблиці `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(20) DEFAULT NULL,
  `password` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп даних таблиці `user`
--

INSERT INTO `user` (`id`, `login`, `password`) VALUES
(1, 'test', 'test'),
(2, 'admin', 'admin');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
