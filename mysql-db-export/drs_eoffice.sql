-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 31, 2021 at 11:02 AM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 8.0.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `drs_eoffice`
--

-- --------------------------------------------------------

--
-- Table structure for table `approvals`
--

CREATE TABLE `approvals` (
  `id` int(11) NOT NULL,
  `manager_id` int(11) NOT NULL,
  `commenter` text NOT NULL,
  `comment` text NOT NULL,
  `comment_status` varchar(10) NOT NULL,
  `request_id` int(11) NOT NULL,
  `approval_status` varchar(20) NOT NULL DEFAULT 'Pending',
  `approval_flag` int(1) NOT NULL DEFAULT 0,
  `datetime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `comment_status` varchar(20) NOT NULL,
  `commenter` varchar(50) NOT NULL,
  `request_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `file_id` int(11) NOT NULL,
  `file_name` text NOT NULL,
  `path` text NOT NULL,
  `request_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `id` int(11) NOT NULL,
  `subject` text NOT NULL,
  `user_id` text NOT NULL,
  `sender` text NOT NULL,
  `request_route_id` int(11) NOT NULL,
  `request_route` text NOT NULL,
  `current_route_position` int(11) DEFAULT 0,
  `timeStamp` datetime NOT NULL DEFAULT current_timestamp(),
  `major_filepath` text NOT NULL,
  `major_filename` text NOT NULL,
  `approval_status` text NOT NULL DEFAULT 'Pending',
  `approval_flag` int(11) NOT NULL,
  `request_log` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `request_traversal`
--

CREATE TABLE `request_traversal` (
  `id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `approval_authority` varchar(50) NOT NULL,
  `sent_by` varchar(50) NOT NULL,
  `operation` varchar(50) NOT NULL,
  `prev_dec` int(11) NOT NULL,
  `processed` int(11) NOT NULL DEFAULT 0,
  `comment` text NOT NULL,
  `comment_status` varchar(20) NOT NULL,
  `datetime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `request_type`
--

CREATE TABLE `request_type` (
  `id` int(11) NOT NULL,
  `request_description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `request_type`
--

INSERT INTO `request_type` (`id`, `request_description`) VALUES
(7, 'Loan Application'),
(8, 'Leave Application'),
(9, 'Transport Allocation'),
(10, 'Disability Allowance'),
(11, 'Health Care'),
(15, 'Bonus Request');

-- --------------------------------------------------------

--
-- Table structure for table `routes`
--

CREATE TABLE `routes` (
  `route_id` int(11) NOT NULL,
  `EmpID` text NOT NULL,
  `request_type_id` int(11) NOT NULL,
  `route` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `routes`
--

INSERT INTO `routes` (`route_id`, `EmpID`, `request_type_id`, `route`) VALUES
(28, '1010', 7, '1020,1010'),
(29, '1020', 10, '1010,1020'),
(30, '1010', 15, '1010,1020'),
(31, '1010', 11, '1020');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `EmpID` text NOT NULL,
  `fullName` text NOT NULL,
  `department` varchar(50) NOT NULL,
  `Contact` text NOT NULL DEFAULT '03012345678',
  `cnic` text NOT NULL,
  `creation_date` datetime NOT NULL DEFAULT current_timestamp(),
  `email` text DEFAULT 'yourname@email.com',
  `password` text NOT NULL,
  `passcode` int(10) NOT NULL DEFAULT 123,
  `address` text NOT NULL DEFAULT '40th Street FDR Drive New York, NY',
  `app_auth` int(11) NOT NULL DEFAULT 0,
  `route_mngr` int(11) NOT NULL DEFAULT 0,
  `emp_mngr` int(11) NOT NULL DEFAULT 0,
  `admin` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `EmpID`, `fullName`, `department`, `Contact`, `cnic`, `creation_date`, `email`, `password`, `passcode`, `address`, `app_auth`, `route_mngr`, `emp_mngr`, `admin`) VALUES
(1, '1010', 'Muhammad Ahmad', 'HR', '03012345678', '82317871231312', '2021-08-31 10:18:57', 'yourname@email.com', 'ahmad', 123, '40th Street FDR Drive New York, NY', 0, 0, 0, 0),
(19, '1020', 'Muhammad Muneeb', 'Shipping', '03012345678', '82317871231312', '2021-08-31 10:18:57', 'yourname@email.com', 'muneeb', 1122, '40th Street FDR Drive New York, NY', 0, 0, 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `approvals`
--
ALTER TABLE `approvals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ApprovalRequestFK` (`request_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `CommentRequestFK` (`request_id`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`file_id`),
  ADD KEY `FileRequestFK` (`request_id`);

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `request_traversal`
--
ALTER TABLE `request_traversal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `TraversalRequestFK` (`request_id`);

--
-- Indexes for table `request_type`
--
ALTER TABLE `request_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `routes`
--
ALTER TABLE `routes`
  ADD PRIMARY KEY (`route_id`),
  ADD KEY `RouteRequestTypeFK` (`request_type_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `approvals`
--
ALTER TABLE `approvals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=167;

--
-- AUTO_INCREMENT for table `request_traversal`
--
ALTER TABLE `request_traversal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `request_type`
--
ALTER TABLE `request_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `routes`
--
ALTER TABLE `routes`
  MODIFY `route_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `approvals`
--
ALTER TABLE `approvals`
  ADD CONSTRAINT `ApprovalRequestFK` FOREIGN KEY (`request_id`) REFERENCES `request` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `CommentRequestFK` FOREIGN KEY (`request_id`) REFERENCES `request` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `FileRequestFK` FOREIGN KEY (`request_id`) REFERENCES `request` (`id`);

--
-- Constraints for table `request_traversal`
--
ALTER TABLE `request_traversal`
  ADD CONSTRAINT `TraversalRequestFK` FOREIGN KEY (`request_id`) REFERENCES `request` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `routes`
--
ALTER TABLE `routes`
  ADD CONSTRAINT `RouteRequestTypeFK` FOREIGN KEY (`request_type_id`) REFERENCES `request_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
