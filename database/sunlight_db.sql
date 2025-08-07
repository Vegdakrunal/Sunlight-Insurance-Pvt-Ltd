-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 03, 2022 at 08:26 AM
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
-- Database: `sunlight_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `category_list`
--

CREATE TABLE `category_list` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `delete_flag` tinyint(1) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `category_list`
--

INSERT INTO `category_list` (`id`, `name`, `description`, `status`, `delete_flag`, `date_created`, `date_updated`) VALUES
(1, 'Commercial', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut pharetra leo eu lorem consectetur interdum. Aliquam auctor dignissim nisi sed gravida. Nunc nisi risus, tincidunt sed malesuada in, euismod in magna. Ut vel orci porttitor, dapibus purus sed, euismod urna. Nulla faucibus venenatis lacus luctus condimentum.', 1, 0, '2022-02-03 08:52:50', '2022-02-03 08:53:09'),
(2, '2 wheeler', 'Integer auctor at mauris a dapibus. Donec id posuere tortor, id vehicula urna. Phasellus libero orci, tristique vitae condimentum a, suscipit non justo. Sed ipsum nisl, pellentesque a nisl luctus, finibus porta orci. Nulla ac vestibulum augue. Etiam placerat lorem eget magna condimentum dignissim.', 1, 0, '2022-02-03 08:53:32', NULL),
(3, '3 Wheeler', 'Sed at leo vel felis pellentesque scelerisque. Nunc ut lobortis mi, non pharetra massa. Praesent in nulla at sem molestie rhoncus lobortis id mi. Nunc erat sapien, pretium a venenatis id, cursus in risus. Sed condimentum felis eget nulla fringilla, eget bibendum ex malesuada. Mauris cursus euismod pellentesque. In tincidunt odio at lacus convallis fringilla.', 1, 0, '2022-02-03 08:54:52', NULL),
(4, '4 Wheeler', 'Quisque at erat at ipsum mollis viverra. Quisque in ex tempor, pulvinar odio at, iaculis nisl. Duis in tellus varius, semper neque quis, pellentesque dui. Morbi orci odio, gravida non risus nec, tristique suscipit magna. Donec cursus, elit et placerat rutrum, justo sapien pulvinar velit, sit amet vulputate felis risus sed magna. Vestibulum elementum ipsum felis, eget posuere lorem viverra nec. Ut at auctor odio.', 1, 0, '2022-02-03 08:56:25', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `client_list`
--

CREATE TABLE `client_list` (
  `id` int(30) NOT NULL,
  `code` varchar(100) NOT NULL,
  `firstname` text NOT NULL,
  `middlename` text NOT NULL,
  `lastname` text NOT NULL,
  `gender` varchar(100) NOT NULL,
  `dob` date NOT NULL,
  `contact` text NOT NULL,
  `email` text NOT NULL,
  `address` text NOT NULL,
  `image_path` text DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `delete_flag` tinyint(4) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `client_list`
--

INSERT INTO `client_list` (`id`, `code`, `firstname`, `middlename`, `lastname`, `gender`, `dob`, `contact`, `email`, `address`, `image_path`, `status`, `delete_flag`, `date_created`, `date_updated`) VALUES
(1, '202202-00001', 'Mark', 'D', 'Cooper', 'Male', '1997-06-23', '09123456789', 'mcooper@sample.com', 'Block 6 Lot 23, Here Subd., Over There, Anywhere 2306', 'uploads/clients/1.png?v=1643856423', 1, 0, '2022-02-03 10:45:56', '2022-02-03 10:47:03'),
(2, '202202-00002', 'Claire', 'C', 'Blake', 'Male', '1997-10-14', '09456789312', 'cblake@sample.com', 'This is her sample address.', 'uploads/clients/2.png?v=1643859659', 1, 0, '2022-02-03 11:40:59', '2022-02-03 11:40:59'),
(3, '202202-00003', 'Test12312', 'Test', 'Test', 'Male', '2022-02-14', '0956465798798', 'test@samnple.com', 'Test', NULL, 0, 1, '2022-02-03 11:41:25', '2022-02-03 11:42:47');

-- --------------------------------------------------------

--
-- Table structure for table `insurance_list`
--

CREATE TABLE `insurance_list` (
  `id` int(30) NOT NULL,
  `client_id` int(30) NOT NULL,
  `policy_id` int(30) NOT NULL,
  `code` varchar(100) NOT NULL,
  `registration_no` text NOT NULL,
  `chassis_no` text NOT NULL,
  `vehicle_model` text NOT NULL,
  `registration_date` date NOT NULL,
  `expiration_date` date NOT NULL,
  `cost` float NOT NULL DEFAULT 0,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `insurance_list`
--

INSERT INTO `insurance_list` (`id`, `client_id`, `policy_id`, `code`, `registration_no`, `chassis_no`, `vehicle_model`, `registration_date`, `expiration_date`, `cost`, `status`, `date_created`, `date_updated`) VALUES
(1, 1, 2, '202202-00001', 'GBN-23141507', '123654789', 'Sample Vehicle 101', '2022-02-03', '2025-02-03', 4999.99, 1, '2022-02-03 13:49:19', '2022-02-03 14:43:54'),
(3, 1, 1, '202202-00002', '654985158', '5489798558', 'Sample 74844158', '2022-02-08', '2023-02-08', 1500, 1, '2022-02-03 15:16:15', NULL),
(4, 2, 3, '202202-00003', '8798754564', '8789564687', 'Sample Vehicle', '2022-01-29', '2023-01-29', 4000, 1, '2022-02-03 15:18:47', NULL),
(5, 2, 3, '202202-00004', '8798754564', '8789564687', 'Sample Vehicle 101', '2015-02-18', '2016-02-18', 4000, 1, '2022-02-03 15:19:13', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `policy_list`
--

CREATE TABLE `policy_list` (
  `id` int(30) NOT NULL,
  `category_id` int(30) NOT NULL,
  `code` varchar(100) NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `duration` float NOT NULL DEFAULT 0,
  `cost` float NOT NULL DEFAULT 0,
  `doc_path` text DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `delete_flag` tinyint(4) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `policy_list`
--

INSERT INTO `policy_list` (`id`, `category_id`, `code`, `name`, `description`, `duration`, `cost`, `doc_path`, `status`, `delete_flag`, `date_created`, `date_updated`) VALUES
(1, 2, '123456789', 'Policy101', 'Sed eget egestas dolor, id mattis ante. Duis non dui at arcu interdum viverra ut vitae est. Nam vulputate posuere gravida. Ut dapibus nulla sed augue semper luctus. Pellentesque ornare, metus et hendrerit euismod, dolor felis laoreet ipsum, sed rutrum massa nunc sit amet neque. Suspendisse bibendum suscipit est a dapibus.', 1, 1500, 'uploads/policies/1.pdf?v=1643853360', 1, 0, '2022-02-03 09:34:32', '2022-02-03 09:56:00'),
(2, 4, '23061415', 'Policy102', 'In bibendum lacus eget purus luctus varius. Integer ultricies libero ac purus vulputate sagittis. Cras ultricies ipsum eget aliquam vestibulum. Pellentesque viverra ligula quis turpis suscipit semper. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Pellentesque dui risus, mollis a nunc sed, semper congue lectus.', 3, 4999.99, 'uploads/policies/2.pdf?v=1643853724', 1, 0, '2022-02-03 10:02:04', '2022-02-03 10:02:04'),
(3, 1, '987654321', 'Commercial Policy1001', 'Integer scelerisque sapien non molestie gravida. Vestibulum cursus bibendum augue, eget dignissim urna sagittis ut. Nulla sed ante mauris. Nam eget mauris felis. Maecenas ac magna in libero sodales sagittis eu et diam. Fusce lobortis quam sit amet lorem molestie, sit amet suscipit diam lacinia. Duis a mollis odio.', 1, 4000, 'uploads/policies/3.pdf?v=1643853870', 1, 0, '2022-02-03 10:04:30', '2022-02-03 10:04:30');

-- --------------------------------------------------------

--
-- Table structure for table `system_info`
--

CREATE TABLE `system_info` (
  `id` int(30) NOT NULL,
  `meta_field` text NOT NULL,
  `meta_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `system_info`
--

INSERT INTO `system_info` (`id`, `meta_field`, `meta_value`) VALUES
(1, 'name', 'Vehicle Insurance Management System'),
(6, 'short_name', 'VIMS - PHP'),
(11, 'logo', 'uploads/logo-1643849196.png'),
(13, 'user_avatar', 'uploads/user_avatar.jpg'),
(14, 'cover', 'uploads/cover-1643849034.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(50) NOT NULL,
  `firstname` varchar(250) NOT NULL,
  `middlename` text DEFAULT NULL,
  `lastname` varchar(250) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `avatar` text DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `status` int(1) NOT NULL DEFAULT 1 COMMENT '0=not verified, 1 = verified',
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `middlename`, `lastname`, `username`, `password`, `avatar`, `last_login`, `type`, `status`, `date_added`, `date_updated`) VALUES
(1, 'Adminstrator', NULL, 'Admin', 'admin', '0192023a7bbd73250516f069df18b500', 'uploads/clients/1.png?v=1643856356', NULL, 1, 1, '2021-01-20 14:02:37', '2022-02-03 10:45:56'),
(8, 'Claire', NULL, 'Blake', 'cblake', '4744ddea876b11dcb1d169fadf494418', 'uploads/avatar-8.png?v=1643872307', NULL, 2, 1, '2022-02-03 15:11:47', '2022-02-03 15:11:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category_list`
--
ALTER TABLE `category_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_list`
--
ALTER TABLE `client_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `insurance_list`
--
ALTER TABLE `insurance_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `policy_id` (`policy_id`);

--
-- Indexes for table `policy_list`
--
ALTER TABLE `policy_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `system_info`
--
ALTER TABLE `system_info`
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
-- AUTO_INCREMENT for table `category_list`
--
ALTER TABLE `category_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `client_list`
--
ALTER TABLE `client_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `insurance_list`
--
ALTER TABLE `insurance_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `policy_list`
--
ALTER TABLE `policy_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `system_info`
--
ALTER TABLE `system_info`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `insurance_list`
--
ALTER TABLE `insurance_list`
  ADD CONSTRAINT `insurance_list_ibfk_1` FOREIGN KEY (`policy_id`) REFERENCES `policy_list` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `insurance_list_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `client_list` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `policy_list`
--
ALTER TABLE `policy_list`
  ADD CONSTRAINT `policy_list_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category_list` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
