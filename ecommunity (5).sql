-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 13, 2016 at 05:09 PM
-- Server version: 10.1.9-MariaDB
-- PHP Version: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecommunity`
--

-- --------------------------------------------------------

--
-- Table structure for table `comment_vote`
--

CREATE TABLE `comment_vote` (
  `cv_id` int(11) NOT NULL,
  `u_email` varchar(100) NOT NULL,
  `p_id` int(100) NOT NULL,
  `c_id` varchar(100) NOT NULL,
  `c_vote` int(1) NOT NULL DEFAULT '-1',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comment_vote`
--

INSERT INTO `comment_vote` (`cv_id`, `u_email`, `p_id`, `c_id`, `c_vote`, `timestamp`) VALUES
(1, 'qwe', 1, '5707ced8e4b0c4265cb19bcf', 0, '2016-04-08 15:33:38'),
(2, 'asd', 1, '5707ced8e4b0c4265cb19bcf', 0, '2016-04-08 15:34:37'),
(3, 'asd', 1, '5707ced1e4b0a37fb1a545e9', 1, '2016-04-08 15:35:09'),
(4, 'asd', 1, '5707ce97e4b0c4265cb1999d', 1, '2016-04-08 15:35:21'),
(5, 'asd', 1, '5707ce9fe4b0a37fb1a541c7', 1, '2016-04-08 15:35:36'),
(6, 'qwe', 1, '5707ce9fe4b0a37fb1a541c7', 1, '2016-04-08 15:35:59'),
(7, 'qwe', 1, '5707cec0e4b0c4265cb19af9', 0, '2016-04-08 15:37:05'),
(8, 'asd', 1, '5707cec0e4b0c4265cb19af9', 0, '2016-04-08 15:38:45'),
(9, 'asd', 1, '5707c587e4b0a37fb1a4d120', 1, '2016-04-08 15:40:02'),
(10, 'qwe', 1, '5707c587e4b0a37fb1a4d120', 1, '2016-04-08 15:40:11');

-- --------------------------------------------------------

--
-- Table structure for table `constituency_master`
--

CREATE TABLE `constituency_master` (
  `con_id` int(11) NOT NULL,
  `con_name` varchar(100) NOT NULL,
  `no_of_posts` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `post_keywords`
--

CREATE TABLE `post_keywords` (
  `k_id` int(11) NOT NULL,
  `p_id` int(100) NOT NULL,
  `keyword` varchar(100) NOT NULL,
  `metaphone` varchar(100) NOT NULL,
  `relevance` varchar(100) NOT NULL,
  `con_name` varchar(100) NOT NULL,
  `category` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `post_keywords`
--

INSERT INTO `post_keywords` (`k_id`, `p_id`, `keyword`, `metaphone`, `relevance`, `con_name`, `category`) VALUES
(1, 4, 'sv', 'SF', '0.986269', 'mumbai_south', 'infra'),
(2, 4, 'road', 'RT', '0.986269', 'mumbai_south', 'infra'),
(3, 4, 'roads', 'RTS', '0.787797', 'mumbai_south', 'infra'),
(4, 4, 'west', 'WST', '0.595053', 'mumbai_south', 'infra'),
(5, 5, 'andheri', 'ANTHR', '0.92545', 'mumbai_south', 'politics'),
(6, 5, 'corruption', 'KRPXN', '0.785616', 'mumbai_south', 'politics'),
(7, 5, 'curruption', 'KRPXN', '0.665072', 'mumbai_south', 'politics'),
(11, 6, 'sv', 'SF', '0.984243', 'mumbai_south', 'infra'),
(12, 6, 'road', 'RT', '0.984243', 'mumbai_south', 'infra'),
(13, 6, 'roads', 'RTS', '0.787797', 'mumbai_south', 'infra'),
(14, 6, 'west', 'WST', '0.594372', 'mumbai_south', 'infra');

-- --------------------------------------------------------

--
-- Table structure for table `post_master`
--

CREATE TABLE `post_master` (
  `p_id` int(11) NOT NULL,
  `u_email` varchar(100) NOT NULL,
  `u_name` varchar(100) DEFAULT NULL,
  `con_name` varchar(100) NOT NULL,
  `state_name` varchar(3) NOT NULL,
  `p_title` varchar(500) NOT NULL,
  `p_desc` varchar(2000) NOT NULL DEFAULT 'No Description Available',
  `p_lat` varchar(100) NOT NULL,
  `p_lon` varchar(100) NOT NULL,
  `upvotes` int(100) DEFAULT NULL,
  `downvotes` int(100) DEFAULT NULL,
  `no_of_comments` int(100) NOT NULL DEFAULT '0',
  `category` varchar(100) DEFAULT NULL,
  `p_type` int(1) NOT NULL,
  `needVolun` int(1) NOT NULL DEFAULT '0',
  `timestamp` datetime(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `post_master`
--

INSERT INTO `post_master` (`p_id`, `u_email`, `u_name`, `con_name`, `state_name`, `p_title`, `p_desc`, `p_lat`, `p_lon`, `upvotes`, `downvotes`, `no_of_comments`, `category`, `p_type`, `needVolun`, `timestamp`) VALUES
(1, 'qwe', NULL, 'mumbai_south', '', 'qwe', 'qweqwe', '19.121', '72.84', 15, 2, 26, NULL, 1, 0, '2016-02-14 21:08:34.000000'),
(2, 'qwe', 'qwe', 'mumbai_south', 'MH', 'qwe', 'qweqwe', '19.124584', '72.845480', 7, 19, 32, NULL, 1, 1, '2016-02-15 20:15:52.000000'),
(3, 'qwe', 'qwe', 'mumbai_south', 'MH', 'qwe', 'qweqwe', '19.125', '72.8455', 55, 1, 3, NULL, 1, 1, '2016-02-15 20:15:55.000000'),
(4, 'qwe', 'qwe', 'mumbai_south', 'MH', 'roads are very dirty.', 'road near sv road andheri west is very dirty. please clean it.', '19.126115', '72.839032', 23, 0, 10, NULL, 1, 1, '2016-02-19 18:42:54.000000'),
(5, 'qwe', 'qwe', 'mumbai_south', '', 'lot of corruption in andheri', 'lot of curruption in happening in andheri', '19.121', '72.12', 11, 55, 4, NULL, 1, 0, '2016-02-20 05:21:12.000000'),
(6, 'qwe', 'qwe', 'mumbai_south', 'MH', 'roads are very dirty', 'road near sv road andheri west is very dirty. please clean them', '19.117597', '72.845286', 66, 22, 40, NULL, 1, 1, '2016-02-20 18:26:32.000000');

-- --------------------------------------------------------

--
-- Table structure for table `post_vote`
--

CREATE TABLE `post_vote` (
  `pv_id` int(11) NOT NULL,
  `u_email` varchar(100) NOT NULL,
  `p_id` int(100) NOT NULL,
  `p_vote` int(2) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `post_vote`
--

INSERT INTO `post_vote` (`pv_id`, `u_email`, `p_id`, `p_vote`, `timestamp`) VALUES
(1, 'qwe', 1, 1, '2016-04-11 05:12:15'),
(2, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(3, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(4, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(5, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(6, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(7, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(8, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(9, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(10, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(11, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(12, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(13, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(14, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(15, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(16, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(17, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(18, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(19, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(20, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(21, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(22, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(23, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(24, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(25, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(26, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(27, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(28, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(29, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(30, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(31, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(32, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(33, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(34, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(35, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(36, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(37, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(38, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(39, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(40, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(41, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(42, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(43, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(44, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(45, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(46, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(47, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(48, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(49, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(50, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(51, 'qwe', 1, 1, '2016-04-13 05:12:15'),
(52, 'qwe', 2, 1, '2016-04-11 05:12:36'),
(53, 'qwe', 2, 1, '2016-04-13 05:12:36'),
(54, 'qwe', 2, 1, '2016-04-13 05:12:36'),
(55, 'qwe', 2, 1, '2016-04-13 05:12:36'),
(56, 'qwe', 2, 1, '2016-04-13 05:12:36'),
(57, 'qwe', 2, 1, '2016-04-13 05:12:36'),
(58, 'qwe', 2, 1, '2016-04-13 05:12:36'),
(59, 'qwe', 2, 1, '2016-04-13 05:12:36'),
(60, 'qwe', 2, 1, '2016-04-13 05:12:36'),
(61, 'qwe', 2, 1, '2016-04-13 05:12:36'),
(62, 'qwe', 2, 1, '2016-04-13 05:12:36'),
(63, 'qwe', 2, 1, '2016-04-13 05:12:36'),
(64, 'qwe', 2, 1, '2016-04-13 05:12:36'),
(65, 'qwe', 2, 1, '2016-04-13 05:12:36'),
(66, 'qwe', 2, 1, '2016-04-13 05:12:36'),
(67, 'qwe', 2, 1, '2016-04-13 05:12:36'),
(68, 'qwe', 2, 1, '2016-04-13 05:12:36'),
(69, 'qwe', 2, 1, '2016-04-13 05:12:36'),
(70, 'qwe', 2, 1, '2016-04-13 05:12:36'),
(71, 'qwe', 2, 1, '2016-04-13 05:12:36'),
(72, 'qwe', 2, 1, '2016-04-13 05:12:36'),
(73, 'qwe', 2, 1, '2016-04-13 05:12:36'),
(74, 'qwe', 2, 1, '2016-04-13 05:12:36'),
(75, 'qwe', 2, 1, '2016-04-13 05:12:36'),
(76, 'qwe', 2, 1, '2016-04-13 05:12:36'),
(77, 'qwe', 2, 1, '2016-04-13 05:12:36'),
(78, 'qwe', 2, 1, '2016-04-13 05:12:36'),
(79, 'qwe', 2, 1, '2016-04-13 05:12:36'),
(80, 'qwe', 2, 1, '2016-04-13 05:12:36'),
(81, 'qwe', 2, 1, '2016-04-13 05:12:36'),
(82, 'qwe', 2, 1, '2016-04-13 05:12:36'),
(83, 'qwe', 2, 1, '2016-04-13 05:12:36'),
(84, 'qwe', 2, 1, '2016-04-13 05:12:36'),
(85, 'qwe', 2, 1, '2016-04-13 05:12:36'),
(86, 'qwe', 2, 1, '2016-04-13 05:12:36'),
(87, 'qwe', 2, 1, '2016-04-13 05:12:36'),
(88, 'qwe', 2, 1, '2016-04-13 05:12:36'),
(89, 'qwe', 2, 1, '2016-04-13 05:12:36'),
(90, 'qwe', 2, 1, '2016-04-13 05:12:36'),
(91, 'qwe', 2, 1, '2016-04-13 05:12:36'),
(92, 'qwe', 2, 1, '2016-04-13 05:12:36'),
(93, 'qwe', 2, 1, '2016-04-13 05:12:36'),
(94, 'qwe', 2, 1, '2016-04-13 05:12:36'),
(95, 'qwe', 2, 1, '2016-04-13 05:12:36'),
(96, 'qwe', 2, 1, '2016-04-13 05:12:36'),
(97, 'qwe', 2, 1, '2016-04-13 05:12:36'),
(98, 'qwe', 2, 1, '2016-04-13 05:12:36'),
(99, 'qwe', 2, 1, '2016-04-13 05:12:36'),
(100, 'qwe', 2, 1, '2016-04-13 05:12:36'),
(101, 'qwe', 2, 1, '2016-04-13 05:12:36');

-- --------------------------------------------------------

--
-- Table structure for table `user_master`
--

CREATE TABLE `user_master` (
  `u_id` int(11) NOT NULL,
  `u_email` varchar(100) NOT NULL,
  `u_pass` varchar(100) NOT NULL,
  `u_name` varchar(100) NOT NULL,
  `u_type` int(1) NOT NULL DEFAULT '1',
  `home_constituency` varchar(100) DEFAULT NULL,
  `u_phone` varchar(15) DEFAULT NULL,
  `isVolun` int(1) NOT NULL DEFAULT '0',
  `u_lat` varchar(100) NOT NULL,
  `u_lon` varchar(100) NOT NULL,
  `v_lat` varchar(100) NOT NULL,
  `v_lon` varchar(100) NOT NULL,
  `state_name` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_master`
--

INSERT INTO `user_master` (`u_id`, `u_email`, `u_pass`, `u_name`, `u_type`, `home_constituency`, `u_phone`, `isVolun`, `u_lat`, `u_lon`, `v_lat`, `v_lon`, `state_name`) VALUES
(5, 'andherian', 'andherian', 'andherian', 1, NULL, NULL, 1, '19.130820', '72.836949', '19.130820', '72.836949', 'MH'),
(2, 'asd', 'asd', 'asd', 1, 'mumbai_south', NULL, 1, '19.130820', '72.836949', '19.130820', '72.836949', 'MH'),
(4, 'bandrian', 'bandrian', 'bandrian', 1, 'mumbai_south', NULL, 1, '19.058147', '72.833092', '19.058147', '72.833092', 'MH'),
(3, 'mama', 'mama', 'mama', 1, 'mumbai_south', NULL, 1, '19.108712', '72.841157', '19.108712', '72.841157', 'MH'),
(1, 'qwe', 'qwe', 'qwe', 1, NULL, '123', 0, '', '', '19.128361', '72.845085', 'MH');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comment_vote`
--
ALTER TABLE `comment_vote`
  ADD PRIMARY KEY (`cv_id`);

--
-- Indexes for table `constituency_master`
--
ALTER TABLE `constituency_master`
  ADD PRIMARY KEY (`con_name`),
  ADD UNIQUE KEY `con_id` (`con_id`);

--
-- Indexes for table `post_keywords`
--
ALTER TABLE `post_keywords`
  ADD PRIMARY KEY (`k_id`);

--
-- Indexes for table `post_master`
--
ALTER TABLE `post_master`
  ADD PRIMARY KEY (`p_id`);

--
-- Indexes for table `post_vote`
--
ALTER TABLE `post_vote`
  ADD PRIMARY KEY (`pv_id`);

--
-- Indexes for table `user_master`
--
ALTER TABLE `user_master`
  ADD PRIMARY KEY (`u_email`),
  ADD UNIQUE KEY `u_id` (`u_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comment_vote`
--
ALTER TABLE `comment_vote`
  MODIFY `cv_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `constituency_master`
--
ALTER TABLE `constituency_master`
  MODIFY `con_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `post_keywords`
--
ALTER TABLE `post_keywords`
  MODIFY `k_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `post_master`
--
ALTER TABLE `post_master`
  MODIFY `p_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `post_vote`
--
ALTER TABLE `post_vote`
  MODIFY `pv_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;
--
-- AUTO_INCREMENT for table `user_master`
--
ALTER TABLE `user_master`
  MODIFY `u_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
