-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 28, 2023 at 08:04 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `captcha_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `captcha_tbl`
--

CREATE TABLE `captcha_tbl` (
  `id` int(11) NOT NULL,
  `image` varchar(10) NOT NULL,
  `code` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `captcha_tbl`
--

INSERT INTO `captcha_tbl` (`id`, `image`, `code`) VALUES
(1, 'cap1.png', 'tw7y4b'),
(2, 'cap2.png', 'ad136y'),
(3, 'cap3.png', 'qr1ye0'),
(4, 'cap4.png', 'f7up3i'),
(5, 'cap5.png', '6tw9yb'),
(6, 'cap6.png', 'z0j8ir0'),
(7, 'cap7.png', '1logv4'),
(8, 'cap8.png', 'mq5oy'),
(9, 'cap9.png', '7ty40j'),
(10, 'cap10.png', 'ef3baz'),
(11, 'cap11.png', 'kp8h90');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `name` varchar(32) DEFAULT NULL,
  `username` varchar(16) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`name`, `username`, `password`, `email`, `image`) VALUES
('Abebe Biru Soboka', 'nerav51670@dewar', '4beabdb41751a6b09288c89077000699', 'nerav51670@dewareff.com', 'phplogo.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `captcha_tbl`
--
ALTER TABLE `captcha_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD KEY `name` (`name`(6)),
  ADD KEY `username` (`username`(6)),
  ADD KEY `email` (`email`(6));

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `captcha_tbl`
--
ALTER TABLE `captcha_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;
