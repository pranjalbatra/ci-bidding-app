-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 09, 2020 at 05:31 PM
-- Server version: 10.1.19-MariaDB
-- PHP Version: 7.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bidding-app`
--

-- --------------------------------------------------------

--
-- Table structure for table `bids`
--

CREATE TABLE `bids` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `start_time` bigint(20) NOT NULL,
  `end_time` bigint(20) NOT NULL,
  `creator_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bids`
--

INSERT INTO `bids` (`id`, `title`, `start_time`, `end_time`, `creator_id`) VALUES
(1, 'test bid', 1589031837, 1589125560, 1),
(2, 'bid 2', 1589039280, 1589125680, 1);

-- --------------------------------------------------------

--
-- Table structure for table `bid_amounts`
--

CREATE TABLE `bid_amounts` (
  `id` int(11) NOT NULL,
  `bid_id` int(11) NOT NULL,
  `bidder_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bid_amounts`
--

INSERT INTO `bid_amounts` (`id`, `bid_id`, `bidder_id`, `amount`) VALUES
(1, 1, 3, 299),
(2, 1, 4, 269);

-- --------------------------------------------------------

--
-- Table structure for table `bid_invites`
--

CREATE TABLE `bid_invites` (
  `id` int(11) NOT NULL,
  `bid_id` int(11) NOT NULL,
  `bidder_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0 = pending, 1 = accepted, 2 = rejected'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bid_invites`
--

INSERT INTO `bid_invites` (`id`, `bid_id`, `bidder_id`, `status`) VALUES
(1, 1, 3, 1),
(2, 1, 4, 1),
(3, 2, 3, 1),
(4, 2, 4, 0);

-- --------------------------------------------------------

--
-- Table structure for table `bid_items`
--

CREATE TABLE `bid_items` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `bid_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bid_items`
--

INSERT INTO `bid_items` (`id`, `title`, `description`, `bid_id`) VALUES
(1, '1', ' 11a', 1),
(2, '2', ' 22b', 1),
(3, 'it1', ' his isjkdf 12', 2),
(4, 'jdsf', ' aslklsldf', 2);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `type` int(11) NOT NULL COMMENT '1 = bid creators, 2 = bidders'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `type`) VALUES
(1, 'pranjal', 'pb', '77f010d49660d2005ebdd263432b0c2b', 1),
(2, 'Pbatra', 'pb1', 'c162fecff1a34109bd492ccf90c80d54', 1),
(3, 'PBatra', 'pb_bidder', 'b6ef008068fd260d93c13527c8ec9b3f', 2),
(4, 'Pbatra2', 'pb_bidder2', 'b6ef008068fd260d93c13527c8ec9b3f', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bids`
--
ALTER TABLE `bids`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bid_amounts`
--
ALTER TABLE `bid_amounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bid_invites`
--
ALTER TABLE `bid_invites`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bid_items`
--
ALTER TABLE `bid_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bids`
--
ALTER TABLE `bids`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `bid_amounts`
--
ALTER TABLE `bid_amounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `bid_invites`
--
ALTER TABLE `bid_invites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `bid_items`
--
ALTER TABLE `bid_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
