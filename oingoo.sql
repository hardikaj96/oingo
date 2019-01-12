-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 13, 2018 at 03:39 AM
-- Server version: 10.1.35-MariaDB
-- PHP Version: 7.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `oingoo`
--

DELIMITER $$
--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `check_if_visible` (`fv` VARCHAR(45), `nv` VARCHAR(45), `fu` VARCHAR(45), `nu` VARCHAR(45)) RETURNS VARCHAR(10) CHARSET utf8 begin
if (fv = 'private' and nu = fu)
	then return '1';
ELSE
    IF(nv = 'private' and nu = fu)
    	then return '1';
ELSE
    IF(fv = 'public' and nv = 'public')
    	then return '1'; 
ELSE
	if(((fv = "friend" or fv = "public") and (nv = "public" or nv = "friend")) AND
      	 nu in (select a.uid2 
                from friend a, friend b 
                where a.uid1 = fu and b.uid2 = a.uid1 and b.uid1 = a.uid2)) 
	then return '1';
else 
	return '0';
end if;
end if;
end if;
end if;
end$$

CREATE DEFINER=`root`@`localhost` FUNCTION `check_schedule` (`cur` TIMESTAMP, `type` VARCHAR(30), `starttime` TIME, `endtime` TIME, `startdate` DATE, `enddate` DATE) RETURNS VARCHAR(10) CHARSET utf8 begin
if ((date(cur) not BETWEEN startdate and enddate) or (time(cur) not between starttime and endtime))
	then return '0';
ELSE
	if(type = 'fix' and date(cur) = startdate)
	then return '1';
ELSE
    IF (type = 'daily')
    then return '1'; 
ELSE
    IF(type = 'weekly'and (dayofweek(cur) = dayofweek(startdate)))
    then return '1';
ELSE
    IF(type = 'monthly' and (day(cur) = day(startdate)))
    then return '1';
    
else 
	return '0';
end if;
end if;
end if;
end if;
end if;
end$$

CREATE DEFINER=`root`@`localhost` FUNCTION `getDistance` (`lat1` VARCHAR(200), `lng1` VARCHAR(200), `lat2` VARCHAR(200), `lng2` VARCHAR(200)) RETURNS VARCHAR(10) CHARSET utf8 begin
declare distance varchar(10);

set distance = (select (6371 * acos( 
                cos( radians(lat2) ) 
              * cos( radians( lat1 ) ) 
              * cos( radians( lng1 ) - radians(lng2) ) 
              + sin( radians(lat2) ) 
              * sin( radians( lat1 ) )
                ) ) as distance); 

if(distance is null)
then
 return '';
else 
return distance;
end if;
end$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `cid` int(11) NOT NULL COMMENT 'a unique id for each comment',
  `nid` int(11) DEFAULT NULL COMMENT 'nid to identify the note that this comment is for',
  `id` int(11) UNSIGNED DEFAULT NULL COMMENT 'the uid to identify the creater of this comment',
  `commentcontent` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL COMMENT 'the content of the comment',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'the datetime when a user makes a comment'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`cid`, `nid`, `id`, `commentcontent`, `created_at`) VALUES
(1, 3, 2, 'will visit', '2018-12-12 13:19:18'),
(2, 2, 2, 'Good COurse', '2018-12-12 15:10:35');

-- --------------------------------------------------------

--
-- Table structure for table `filter`
--

CREATE TABLE `filter` (
  `fid` int(11) NOT NULL,
  `uid` int(11) UNSIGNED DEFAULT NULL,
  `vstatus` varchar(45) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `tagid` int(11) DEFAULT NULL,
  `stateid` int(11) DEFAULT NULL,
  `latitude` decimal(10,6) DEFAULT NULL,
  `longitude` decimal(10,6) DEFAULT NULL,
  `radius` int(11) DEFAULT NULL,
  `scheduleid` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `filter`
--

INSERT INTO `filter` (`fid`, `uid`, `vstatus`, `tagid`, `stateid`, `latitude`, `longitude`, `radius`, `scheduleid`, `created_at`) VALUES
(1, 1, 'public', 1, 1, '40.691718', '-73.989787', 10, 2, '2018-12-12 09:26:00'),
(2, 2, 'public', 1, 1, '40.691718', '-73.989787', 10, 4, '2018-12-12 09:46:19'),
(3, 1, 'private', 2, 1, '40.729500', '-73.997300', 10, 2, '2018-11-30 06:10:51'),
(4, 2, 'friend', 1, 2, '40.729500', '-73.997300', 2, 3, '2018-11-30 02:03:45'),
(5, 1, 'public', NULL, 6, '40.729500', '-73.997300', 10, 1, '2018-11-30 06:10:51'),
(6, 2, 'public', 15, 2, '40.729500', '-73.997300', 8, 3, '2018-11-30 01:50:22'),
(7, 3, 'public', 11, 3, '40.729500', '-73.997300', 9, 3, '2018-11-30 06:10:51'),
(8, 4, 'public', 4, 5, '40.729500', '-73.997300', 11, 3, '2018-11-30 06:10:51'),
(9, 5, 'public', 1, 3, '40.729500', '-73.997300', 7, 3, '2018-11-30 06:10:51'),
(10, 1, 'private', 1, 6, '40.733600', '-74.063000', 4, 1, '2018-11-30 06:10:51'),
(11, 2, 'public', 2, 2, '40.778900', '-74.023700', 5, 3, '2018-11-30 02:03:45'),
(12, 3, 'friend', 3, 5, '40.789200', '-74.014000', 3, 3, '2018-11-30 06:10:51'),
(13, 3, 'public', 4, 3, '40.672000', '-74.112200', 5, 4, '2018-11-30 06:10:51'),
(14, 1, 'private', 3, 1, '40.740100', '-74.170000', 5, 5, '2018-11-30 06:10:51'),
(15, 4, 'friend', 6, 2, '40.740100', '-74.170000', 5, 6, '2018-11-30 01:51:17'),
(16, 4, 'friend', 7, 2, '40.740100', '-74.170000', 3, 7, '2018-11-28 18:37:28'),
(17, 2, 'public', 8, 3, '40.740100', '-74.170000', 4, 3, '2018-11-30 02:03:45'),
(18, 3, 'public', 9, 2, '40.742600', '-74.060400', 6, 3, '2018-11-28 18:37:28'),
(19, 3, 'private', 10, 1, '40.661400', '-73.971200', 7, 4, '2018-11-28 18:37:28'),
(20, 2, 'public', 1, 1, '40.694762', '-73.985571', 10, 15, '2018-12-12 18:23:17'),
(21, 2, 'public', 2, 1, '40.708461', '-73.993574', 20, 16, '2018-12-12 18:24:13'),
(22, 2, 'public', 3, 1, '40.708461', '-73.993574', 20, 17, '2018-12-12 18:24:24'),
(23, 3, 'public', 1, 1, '40.710043', '-73.990567', 20, 20, '2018-12-12 18:27:53'),
(24, 1, 'public', 1, 1, '40.728331', '-73.995479', 50, 21, '2018-12-12 18:40:47'),
(25, 1, 'public', 2, 1, '40.728331', '-73.995479', 50, 22, '2018-12-12 18:40:53'),
(26, 1, 'public', 3, 1, '40.728331', '-73.995479', 50, 23, '2018-12-12 18:40:58'),
(27, 1, 'public', 3, 2, '40.728331', '-73.995479', 50, 24, '2018-12-12 18:41:02'),
(28, 1, 'public', 1, 2, '40.728331', '-73.995479', 50, 25, '2018-12-12 18:41:05'),
(29, 1, 'public', 2, 2, '40.728331', '-73.995479', 50, 26, '2018-12-12 18:41:09'),
(30, 1, 'public', 3, 2, '40.728331', '-73.995479', 50, 27, '2018-12-12 18:41:15'),
(31, 1, 'public', 1, 4, '40.728331', '-73.995479', 50, 28, '2018-12-12 18:41:21'),
(32, 1, 'public', 1, 1, '40.728331', '-73.995479', 10, 31, '2018-12-12 18:45:08'),
(33, 1, 'public', 2, 1, '40.728331', '-73.995479', 10, 32, '2018-12-12 18:45:12'),
(34, 1, 'public', 3, 1, '40.728331', '-73.995479', 10, 33, '2018-12-12 18:45:18'),
(35, 1, 'public', 1, 1, '40.728331', '-73.995479', 50, 34, '2018-12-12 18:48:22');

-- --------------------------------------------------------

--
-- Table structure for table `friend`
--

CREATE TABLE `friend` (
  `frid` int(11) NOT NULL,
  `uid1` int(10) UNSIGNED NOT NULL,
  `uid2` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `friend`
--

INSERT INTO `friend` (`frid`, `uid1`, `uid2`) VALUES
(1, 2, 1),
(2, 2, 1),
(3, 2, 1),
(4, 1, 2),
(5, 1, 3),
(6, 1, 5),
(7, 1, 4),
(8, 1, 6),
(9, 6, 1),
(10, 3, 1),
(11, 3, 2),
(12, 3, 5);

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT 'the user''s uid',
  `current` datetime NOT NULL DEFAULT '2018-12-12 03:03:03',
  `curr_latitude` decimal(10,6) DEFAULT NULL COMMENT 'the location of the user',
  `curr_longitude` decimal(10,6) DEFAULT NULL COMMENT 'the location of the user'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`uid`, `current`, `curr_latitude`, `curr_longitude`) VALUES
(1, '2018-11-26 14:28:01', '40.708461', '-73.993574'),
(2, '2018-11-26 14:29:59', '40.694466', '-73.986411'),
(3, '2018-11-26 14:28:25', '40.708461', '-73.993574'),
(4, '2018-11-26 14:29:50', '40.708461', '-73.993574'),
(5, '2018-11-26 14:30:50', '40.708461', '-73.993574'),
(6, '2018-11-26 14:35:05', '40.708461', '-73.993574'),
(7, '2018-12-11 12:00:59', '39.974366', '-75.156007');

-- --------------------------------------------------------

--
-- Table structure for table `note`
--

CREATE TABLE `note` (
  `nid` int(11) NOT NULL,
  `uid` int(11) UNSIGNED NOT NULL,
  `title` varchar(45) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_bin,
  `latitude` decimal(10,6) DEFAULT NULL,
  `longitude` decimal(10,6) DEFAULT NULL,
  `radius_of_interest` float DEFAULT NULL,
  `visible_to` varchar(45) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `able_to_comment` int(11) DEFAULT '0',
  `scheduleid` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `note`
--

INSERT INTO `note` (`nid`, `uid`, `title`, `content`, `latitude`, `longitude`, `radius_of_interest`, `visible_to`, `able_to_comment`, `scheduleid`, `created_at`) VALUES
(1, 2, 'Food', 'There is a Thai restaurant over here in Brooklyn', '40.694200', '-73.986200', 9, 'friend', 1, 3, '2018-11-26 17:30:17'),
(2, 2, 'Subject', 'Database Course is very hard. A database is an organized collection of data, generally stored and accessed electronically from a computer system. Where databases are more complex they are often developed using frmal design and modeling techniques.', '40.694200', '-73.986200', 10, 'public', 1, 3, '2018-11-26 17:30:17'),
(3, 2, 'Views', 'I am at greenwich village. It  has an awesome view and great environment.', '40.730800', '-73.998200', 10, 'public', 1, 3, '2018-11-27 05:34:42'),
(4, 2, 'food', 'Free Dinner at Chipotle', '40.694200', '-73.986200', 12, 'public', 0, 3, '2018-11-17 15:00:00'),
(5, 3, 'shop', '80% off sale in Newport Mall', '40.778900', '-74.023700', 10, 'public', 1, 3, '2018-11-17 15:00:00'),
(6, 3, 'work', 'Work is going to be hectic nowadays', '40.672000', '-74.112200', 13, 'public', 0, 1, '2018-11-22 06:35:52'),
(7, 2, 'Food', 'good restaurant at 5th and broadway', '40.672000', '-74.112200', 12, 'public', 0, 5, '2018-11-25 04:08:00'),
(8, 5, 'FB', 'looking for new friends to meet up!', '40.672000', '-74.112200', 15, 'friend', 1, 5, '2018-11-25 04:08:00'),
(9, 2, 'FB', 'show friends only', '40.740100', '-74.170000', 20, 'public', 1, 5, '2018-11-26 05:50:00'),
(10, 4, 'Shop', '80% off sale in Newport Mall', '40.778900', '-74.023700', 5, 'public', 1, 3, '2018-11-29 04:22:31'),
(11, 4, 'Food', 'get free dinner at jasper', '40.778800', '-74.023800', 9, 'public', 1, 3, '2018-11-29 04:22:31'),
(12, 2, 'Job', 'great offers in Morgan Stanley', '40.778700', '0.000000', 8, 'public', 1, 3, '2018-11-29 04:22:31'),
(13, 2, 'Shop', 'Book laptops at 60% off from bookstore', '40.778600', '-74.023500', 7, 'public', 1, 3, '2018-11-29 04:22:31'),
(14, 3, 'SHop', 'Buy mobile accessories cheaper on road', '40.778500', '-74.023400', 8, 'public', 1, 3, '2018-11-29 04:22:31'),
(15, 3, 'Health', 'Fruits are healthier', '40.778400', '-74.023300', 6, 'public', 1, 3, '2018-11-29 04:22:31'),
(16, 3, 'SHop', 'get toiletries from Manhattan Mall', '40.778300', '-74.023200', 9, 'public', 1, 3, '2018-11-29 04:22:31'),
(17, 3, 'Politics', 'Increase chance of winning the lottery for NYU Elections', '40.778200', '-74.023100', 10, 'public', 1, 3, '2018-11-29 04:22:31'),
(18, 2, 'PIZZA', 'Grab a free pizza with coke at jasper', '40.694200', '-73.986200', 10, 'public', 1, 3, '2018-11-26 22:30:17'),
(19, 2, 'Exam Pressure', 'Finals coming up', '0.000000', '0.000000', 0, 'public', 1, 14, '2018-12-12 18:22:34'),
(20, 3, 'Stress', 'Help needed', '40.710043', '-73.990567', 10, 'public', 1, 18, '2018-12-12 18:26:46'),
(21, 3, 'Party', 'there is a party at KImmel', '40.710043', '-73.990567', 10, 'public', 1, 19, '2018-12-12 18:27:19'),
(22, 1, 'Dibner', 'Study here', '40.728331', '-73.995479', 10, 'public', 1, 29, '2018-12-12 18:43:59'),
(23, 1, 'CSE', 'Attend Classes here at 2 metrotech', '40.728331', '-73.995479', 10, 'public', 1, 30, '2018-12-12 18:44:30'),
(24, 7, 'Project Demo', 'Project isgoinng', '40.676571', '-74.006649', 10, 'public', 0, 35, '2018-12-12 20:07:36');

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `sid` int(11) NOT NULL,
  `stdate` date DEFAULT NULL,
  `enddate` date DEFAULT NULL,
  `vstart` time DEFAULT NULL,
  `vend` time DEFAULT NULL,
  `type` varchar(45) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`sid`, `stdate`, `enddate`, `vstart`, `vend`, `type`) VALUES
(1, '2017-12-31', '2019-12-31', '00:00:59', '23:59:59', 'daily'),
(2, '2017-12-31', '2019-12-31', '00:00:59', '23:59:59', 'daily'),
(3, '2017-12-31', '2018-12-31', '00:00:59', '23:59:59', 'daily'),
(4, '2017-12-31', '2019-12-31', '00:00:59', '23:59:59', 'daily'),
(5, '2017-01-31', '2018-12-31', '00:59:59', '23:59:59', 'daily'),
(6, '2018-11-25', '2019-02-01', '17:00:00', '19:00:00', 'daily'),
(7, '2019-01-09', '2019-02-01', '10:00:00', '13:00:00', 'weekly'),
(8, '2018-11-26', '2019-02-01', '09:00:00', '19:00:00', 'daily'),
(9, '2018-11-25', '2019-02-01', '13:01:00', '17:25:00', 'fix'),
(10, '2018-11-24', '2019-02-01', '10:08:00', '21:25:00', 'daily'),
(11, '2018-11-18', '2019-02-01', '10:00:00', '17:25:00', 'fix'),
(12, '2018-10-25', '2019-02-01', '05:00:00', '17:25:00', 'monthly'),
(13, '2018-11-11', '2019-02-01', '11:00:00', '17:25:00', 'weekly'),
(14, '2017-12-31', '2020-12-31', '10:58:59', '23:59:59', 'daily'),
(15, '2017-12-02', '2018-12-31', '11:59:59', '23:59:59', 'daily'),
(16, '2017-12-31', '2020-11-30', '00:59:59', '23:59:59', 'daily'),
(17, '2017-12-31', '2020-11-30', '00:59:59', '23:59:59', 'daily'),
(18, '2017-12-31', '2019-12-31', '00:59:59', '23:59:59', 'daily'),
(19, '2016-12-30', '2019-12-31', '00:59:59', '23:59:59', 'daily'),
(20, '2017-12-31', '2019-12-31', '00:00:59', '23:59:59', 'daily'),
(21, '2017-12-31', '2019-12-31', '00:00:59', '23:59:59', 'daily'),
(22, '2017-12-31', '2019-12-31', '00:00:59', '23:59:59', 'daily'),
(23, '2017-12-31', '2019-12-31', '00:00:59', '23:59:59', 'daily'),
(24, '2017-12-31', '2019-12-31', '00:00:59', '23:59:59', 'daily'),
(25, '2017-12-31', '2019-12-31', '00:00:59', '23:59:59', 'daily'),
(26, '2017-12-31', '2019-12-31', '00:00:59', '23:59:59', 'daily'),
(27, '2017-12-31', '2019-12-31', '00:00:59', '23:59:59', 'daily'),
(28, '2017-12-31', '2019-12-31', '00:00:59', '23:59:59', 'daily'),
(29, '2018-12-12', '2019-12-12', '11:59:59', '23:59:59', 'daily'),
(30, '2018-12-12', '2019-12-12', '11:59:59', '23:59:59', 'daily'),
(31, '2018-01-01', '2018-12-31', '00:00:59', '23:59:59', 'daily'),
(32, '2018-01-01', '2018-12-31', '00:00:59', '23:59:59', 'daily'),
(33, '2018-01-01', '2018-12-31', '00:00:59', '23:59:59', 'daily'),
(34, '2017-12-31', '2019-12-31', '00:00:59', '23:59:59', 'daily'),
(35, '2018-12-12', '2019-02-12', '12:00:59', '20:00:59', 'daily');

-- --------------------------------------------------------

--
-- Table structure for table `state`
--

CREATE TABLE `state` (
  `stateid` int(11) NOT NULL,
  `sname` varchar(45) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `state`
--

INSERT INTO `state` (`stateid`, `sname`) VALUES
(1, 'Available'),
(2, 'work'),
(3, 'lunch'),
(4, 'happy'),
(5, 'sad'),
(6, 'angry');

-- --------------------------------------------------------

--
-- Table structure for table `tagging`
--

CREATE TABLE `tagging` (
  `nid` int(11) NOT NULL COMMENT 'the nid of the note',
  `tagid` int(11) NOT NULL COMMENT 'the id of the tag. like #sunny,#new york or other stuff'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='every note can have multiple tags, this table is used to deal with the multiplicity relationship';

--
-- Dumping data for table `tagging`
--

INSERT INTO `tagging` (`nid`, `tagid`) VALUES
(1, 2),
(1, 4),
(1, 9),
(2, 2),
(2, 3),
(2, 8),
(3, 1),
(3, 7),
(4, 2),
(4, 6),
(5, 3),
(5, 5),
(6, 2),
(6, 4),
(7, 3),
(7, 5),
(8, 6),
(9, 7),
(10, 2),
(10, 3),
(10, 8),
(19, 1),
(19, 2),
(19, 3),
(19, 4),
(20, 1),
(20, 2),
(20, 3),
(20, 4),
(21, 1),
(21, 2),
(21, 3),
(21, 5),
(22, 1),
(22, 2),
(22, 3),
(23, 1),
(23, 2),
(23, 3),
(24, 2),
(24, 3),
(24, 7),
(24, 9);

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `tagid` int(11) NOT NULL,
  `tagname` varchar(45) COLLATE utf8_bin NOT NULL,
  `suggested` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`tagid`, `tagname`, `suggested`) VALUES
(1, 'work', 1),
(2, 'me', 1),
(3, 'tourism', 1),
(4, 'transportation', 1),
(5, 'dog', 1),
(6, 'food', 1),
(7, 'sunny', 1),
(8, 'florida', 1),
(9, 'road trip', 1),
(10, 'scenery', 1),
(11, 'world', 1),
(12, 'new york', 1),
(13, 'windy', 1),
(14, 'cat', 1),
(15, 'happy', 1),
(16, 'mouse', 1),
(17, 'sad', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state_id` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `state_id`, `created_at`, `updated_at`) VALUES
(1, 'Hardik', 'hardik@oingo.com', 'ebc4706e4b03a7b90127a364ee9de4ba730a406d', 2, '2018-12-12 09:23:22', '2018-12-12 17:52:05'),
(2, 'Parth', 'parth@oingo.com', 'e735b5ba8bc040b4eff790fe211210f520f27a73', 1, '2018-12-12 09:35:51', '2018-12-12 19:13:21'),
(3, 'Keval', 'keval@oingo.com', 'cbe8b07b65d1603ee748e9f06fd75197b1e6dba9', 1, '2018-12-12 17:59:26', '2018-12-12 17:59:26'),
(4, 'Maulik', 'maulik@oingo.com', '3c4736615b2a7a8ea2a62f27e40db7b4675c2ce7', 1, '2018-12-12 17:59:47', '2018-12-12 17:59:47'),
(5, 'Avi', 'avi@oingo.com', '8f920f22884d6fea9df883843c4a8095a2e5ac6f', 1, '2018-12-12 18:00:00', '2018-12-12 18:00:00'),
(6, 'Kavin', 'kavin@oingo.com', 'd52364f767ce89d4800b4e2dab25ff68d756805a', 1, '2018-12-12 18:00:18', '2018-12-12 18:00:18'),
(7, 'botao', 'botao@oingo.com', '69d7a9db81ebaf426625686fdf08f75feb0acf3b', 3, '2018-12-12 20:05:15', '2018-12-12 20:08:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`cid`),
  ADD KEY `cn` (`nid`),
  ADD KEY `cu` (`id`);

--
-- Indexes for table `filter`
--
ALTER TABLE `filter`
  ADD PRIMARY KEY (`fid`),
  ADD KEY `filter_user` (`uid`),
  ADD KEY `filter_state` (`stateid`),
  ADD KEY `filter_sc` (`scheduleid`),
  ADD KEY `filter_ta` (`tagid`);

--
-- Indexes for table `friend`
--
ALTER TABLE `friend`
  ADD PRIMARY KEY (`frid`),
  ADD KEY `fd_u1` (`uid1`),
  ADD KEY `fd_u2` (`uid2`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD UNIQUE KEY `uid` (`uid`);

--
-- Indexes for table `note`
--
ALTER TABLE `note`
  ADD PRIMARY KEY (`nid`),
  ADD KEY `note_user` (`uid`),
  ADD KEY `note_sc` (`scheduleid`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`sid`);

--
-- Indexes for table `state`
--
ALTER TABLE `state`
  ADD PRIMARY KEY (`stateid`);

--
-- Indexes for table `tagging`
--
ALTER TABLE `tagging`
  ADD PRIMARY KEY (`nid`,`tagid`),
  ADD KEY `fk_nt` (`tagid`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`tagid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `us_s` (`state_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `cid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'a unique id for each comment', AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `filter`
--
ALTER TABLE `filter`
  MODIFY `fid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `friend`
--
ALTER TABLE `friend`
  MODIFY `frid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `note`
--
ALTER TABLE `note`
  MODIFY `nid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `sid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `cn` FOREIGN KEY (`nid`) REFERENCES `note` (`nid`),
  ADD CONSTRAINT `cu` FOREIGN KEY (`id`) REFERENCES `users` (`id`);

--
-- Constraints for table `filter`
--
ALTER TABLE `filter`
  ADD CONSTRAINT `filter_sc` FOREIGN KEY (`scheduleid`) REFERENCES `schedule` (`sid`),
  ADD CONSTRAINT `filter_state` FOREIGN KEY (`stateid`) REFERENCES `state` (`stateid`),
  ADD CONSTRAINT `filter_ta` FOREIGN KEY (`tagid`) REFERENCES `tags` (`tagid`),
  ADD CONSTRAINT `filter_user` FOREIGN KEY (`uid`) REFERENCES `users` (`id`);

--
-- Constraints for table `friend`
--
ALTER TABLE `friend`
  ADD CONSTRAINT `fd_u1` FOREIGN KEY (`uid1`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fd_u2` FOREIGN KEY (`uid2`) REFERENCES `users` (`id`);

--
-- Constraints for table `location`
--
ALTER TABLE `location`
  ADD CONSTRAINT `loc_us` FOREIGN KEY (`uid`) REFERENCES `users` (`id`);

--
-- Constraints for table `note`
--
ALTER TABLE `note`
  ADD CONSTRAINT `note_sc` FOREIGN KEY (`scheduleid`) REFERENCES `schedule` (`sid`),
  ADD CONSTRAINT `note_user` FOREIGN KEY (`uid`) REFERENCES `users` (`id`);

--
-- Constraints for table `tagging`
--
ALTER TABLE `tagging`
  ADD CONSTRAINT `fk_nt` FOREIGN KEY (`tagid`) REFERENCES `tags` (`tagid`),
  ADD CONSTRAINT `fk_nt1` FOREIGN KEY (`nid`) REFERENCES `note` (`nid`),
  ADD CONSTRAINT `fk_tn` FOREIGN KEY (`nid`) REFERENCES `note` (`nid`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `us_s` FOREIGN KEY (`state_id`) REFERENCES `state` (`stateid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
