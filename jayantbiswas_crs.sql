-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 06, 2025 at 07:56 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jayantbiswas_crs`
--

-- --------------------------------------------------------

--
-- Table structure for table `ally_types`
--

CREATE TABLE `ally_types` (
  `id` int(11) NOT NULL,
  `ally_type` varchar(255) DEFAULT NULL,
  `ally_code` varchar(255) DEFAULT NULL,
  `ally_fees` float(9,2) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ally_types`
--

INSERT INTO `ally_types` (`id`, `ally_type`, `ally_code`, `ally_fees`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Ally Type - 01', 'AL', 899.00, 1, '2025-09-06 05:35:46', '2025-09-06 05:35:46');

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` int(11) NOT NULL,
  `branch_name` varchar(255) DEFAULT NULL,
  `branch_code` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `branch_name`, `branch_code`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Assam', 'ASS', 1, '2025-09-05 10:54:56', '2025-09-06 05:29:04');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `read_status` tinyint(4) DEFAULT 2,
  `status` tinyint(4) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `email`, `contact`, `subject`, `message`, `read_status`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Manish Jangid', 'admin@admin.com', '3452342234', 'Software Building', 'test', 1, 1, '2025-08-29 09:10:20', '2025-08-29 09:10:20'),
(2, 'Manish Jangid', 'admin@admin.com', '2343453453', 'Ecommerce Portal', 'This is test\r\nmessage', 2, 1, '2025-08-29 14:41:59', '2025-08-29 14:41:59');

-- --------------------------------------------------------

--
-- Table structure for table `inner_pages`
--

CREATE TABLE `inner_pages` (
  `id` int(11) NOT NULL,
  `page` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `content` text DEFAULT NULL,
  `heading` varchar(255) DEFAULT NULL,
  `sub_heading` varchar(255) DEFAULT NULL,
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_description` text DEFAULT NULL,
  `seo_keyword` text DEFAULT NULL,
  `robot_tags` varchar(255) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `highlight_image` varchar(50) DEFAULT NULL,
  `banner` varchar(255) DEFAULT NULL,
  `banner_status` tinyint(4) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inner_pages`
--

INSERT INTO `inner_pages` (`id`, `page`, `title`, `description`, `content`, `heading`, `sub_heading`, `seo_title`, `seo_description`, `seo_keyword`, `robot_tags`, `image`, `highlight_image`, `banner`, `banner_status`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Home', 'Home', '<h4 style=\"padding: 0px; margin-right: 0px; margin-bottom: 0px; margin-left: 0px; font-family: Inter, sans-serif; line-height: 1.2; color: rgb(65, 65, 65); font-size: 20px; list-style-type: none; transition: 0.3s ease-in-out; background-color: rgb(222, 222, 222);\">Hi, I’m Upamanyu Naskar,<br style=\"padding: 0px; margin: 0px;\"><span style=\"padding: 0px; margin: 0px; font-weight: bold;\">Sr. Product Designer</span></h4><h2 style=\"padding: 0px; margin: 30px 0px 10px; font-family: Poppins, sans-serif; font-weight: 600; line-height: 1.2; color: rgb(65, 65, 65); font-size: 35px; list-style-type: none; transition: 0.3s ease-in-out; background-color: rgb(222, 222, 222);\">I craft products where innovation meets elegance</h2><h5 class=\"mb-md-5 mb-3\" style=\"padding: 0px; margin-right: 0px; margin-left: 0px; font-family: Poppins, sans-serif; line-height: 1.2; color: rgb(65, 65, 65); font-size: 25px; list-style-type: none; transition: 0.3s ease-in-out; background-color: rgb(222, 222, 222); margin-bottom: 3rem !important;\">Designing and launching high-impact products across web, mobile, and<br style=\"padding: 0px; margin: 0px; font-family: Inter, sans-serif;\">enterprise systems. Previously, led product experiences for<br style=\"padding: 0px; margin: 0px; font-family: Inter, sans-serif;\"><span style=\"padding: 0px; margin: 0px; font-family: Inter, sans-serif; font-weight: 600;\">12 ERPs, 18 apps, and 100+ websites</span></h5><h5 style=\"padding: 0px; margin-right: 0px; margin-bottom: 0px; margin-left: 0px; font-family: Poppins, sans-serif; line-height: 1.2; color: rgb(65, 65, 65); font-size: 25px; list-style-type: none; transition: 0.3s ease-in-out; background-color: rgb(222, 222, 222);\">At Endeavor; built ecosystem-first systems<br style=\"padding: 0px; margin: 0px; font-family: Inter, sans-serif;\">Academic consulting at WePegasus;<br style=\"padding: 0px; margin: 0px; font-family: Inter, sans-serif;\">and directed brand and UX transformations for global clients.</h5>', 'Enjoy the beautiful desert scenery, including beautiful sand dunes, from the luxurious Palace on Wheels India. India looks even more beautiful when you are riding the fourth-best luxury train in the world. Leaving New Delhi every Wednesday night, the trip goes to the desert and comes back the next Wednesday morning. This amazing Palace on Wheels Tour Package lasts for eight days and seven nights and covers more than 3,000 km (1850 miles). This trip is the best way to learn about history and have amazing activities.<br><br>The most luxurious accommodations, which evoke a bygone age, and the friendly service ensure that travellers have a memorable trip. A relaxing spa, nice seats and a fully stocked bar are some of the things that the Palace on Wheels India Tour has to offer. Trains stop every day at famous landmarks and old cities, where tourists can see great palaces, busy bazaars, and strong forts, which opens their thoughts to new ideas. Since everything was planned so well, guests will be able to see all of Rajasthan\'s cultures, from the busy bazaars in Jaipur to the calm lakes in Udaipur. The skilled cooks on the gourmet dining trip mix modern and traditional Indian foods to make tasty meals that you can eat at sea. You can take a unique trip through the middle of India on the Palace on Wheels Travel Tour. It stands out because it has a mix of fancy things, easy things to do, and things that might happen in real life.<br><br>', NULL, NULL, 'Home', 'Home', 'Home', 'index,follow', NULL, NULL, '', 1, 1, '2024-06-01 11:50:48', '2025-09-05 07:23:08'),
(2, 'About Us', 'About Us', '<br>', '<p>Explore the enchanting world of Palace on Wheels through our captivating image gallery. Immerse yourself in the opulence and grandeur of this iconic luxury train as it journeys through the cultural treasures of Rajasthan. Witness the beauty and elegance that defines this extraordinary travel experience.<br></p>', NULL, NULL, 'About Us', 'About Us', 'About Us', 'index,follow', NULL, NULL, '', 1, 1, '2024-06-01 11:50:48', '2025-08-28 10:15:03'),
(3, 'Services', 'Services', '<p>tes</p>', '<p>Explore the enchanting world of Palace on Wheels through our captivating image gallery. Immerse yourself in the opulence and grandeur of this iconic luxury train as it journeys through the cultural treasures of Rajasthan. Witness the beauty and elegance that defines this extraordinary travel experience.<br></p>', NULL, NULL, 'Services', 'Services', 'Services', 'index,follow', NULL, NULL, '', 1, 1, '2024-06-01 11:50:48', '2025-08-30 16:51:03'),
(4, 'Terms & Conditions', 'Terms & Conditions', '<p><br></p>', 'Explore the enchanting world of Palace on Wheels through our captivating image gallery. Immerse yourself in the opulence and grandeur of this iconic luxury train as it journeys through the cultural treasures of Rajasthan. Witness the beauty and elegance that defines this extraordinary travel experience.', NULL, NULL, 'Terms & Conditions', 'Terms & Conditions', 'Terms & Conditions', 'index,follow', NULL, NULL, '', 1, 1, '2024-06-01 11:50:48', '2025-09-05 07:34:18'),
(5, 'Privacy Policy', 'Privacy Policy', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries,\r\n</p><p>\r\nLorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s,</p>', '<p>Explore the enchanting world of Palace on Wheels through our captivating image gallery. Immerse yourself in the opulence and grandeur of this iconic luxury train as it journeys through the cultural treasures of Rajasthan. Witness the beauty and elegance that defines this extraordinary travel experience.<br></p>', 'Privacy Policy', 'has been the industry\'s standard', 'Privacy Policy', 'Privacy Policy', 'Privacy Policy', 'index,follow', NULL, NULL, '', 1, 1, '2024-06-01 11:50:48', '2025-08-28 16:00:02'),
(6, 'Portfolio', 'Project', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries,\r\n</p><p>\r\nLorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s,</p>', '<p>Explore the enchanting world of Palace on Wheels through our captivating image gallery. Immerse yourself in the opulence and grandeur of this iconic luxury train as it journeys through the cultural treasures of Rajasthan. Witness the beauty and elegance that defines this extraordinary travel experience.<br></p>', 'Project', NULL, 'Portfolio', 'Portfolio', 'Portfolio', 'index,follow', NULL, NULL, NULL, 1, 1, '2024-06-01 11:50:48', '2025-08-30 16:51:45'),
(7, 'Blogs', 'Blogs', '<p><br></p>', '<p>Explore the enchanting world of Palace on Wheels through our captivating image gallery. Immerse yourself in the opulence and grandeur of this iconic luxury train as it journeys through the cultural treasures of Rajasthan. Witness the beauty and elegance that defines this extraordinary travel experience.<br></p>', NULL, NULL, 'Blogs', 'Blogs', 'Blogs', 'index,follow', NULL, NULL, NULL, 2, 1, '2024-06-01 11:50:48', '2024-07-09 18:02:01'),
(8, 'Contact Us', 'Contact Us', '<p><br></p>', '<p>Explore the enchanting world of Palace on Wheels through our captivating image gallery. Immerse yourself in the opulence and grandeur of this iconic luxury train as it journeys through the cultural treasures of Rajasthan. Witness the beauty and elegance that defines this extraordinary travel experience.<br></p>', NULL, NULL, 'Contact Us', 'Contact Us', 'Contact Us', 'index,follow', NULL, NULL, '', 1, 1, '2024-06-01 11:50:48', '2024-07-10 10:54:52'),
(9, 'Our Team', 'Our Team', '<p><br></p>', '<p>Explore the enchanting world of Palace on Wheels through our captivating image gallery. Immerse yourself in the opulence and grandeur of this iconic luxury train as it journeys through the cultural treasures of Rajasthan. Witness the beauty and elegance that defines this extraordinary travel experience.<br></p>', NULL, NULL, 'Our Team', 'Our Team', 'Our Team', 'index,follow', NULL, NULL, '', 1, 1, '2024-06-01 11:50:48', '2025-08-30 16:52:11'),
(10, 'FAQ', 'FAQ', '<p><br></p>', '<p>Explore the enchanting world of Palace on Wheels through our captivating image gallery. Immerse yourself in the opulence and grandeur of this iconic luxury train as it journeys through the cultural treasures of Rajasthan. Witness the beauty and elegance that defines this extraordinary travel experience.<br></p>', NULL, NULL, 'FAQ', 'FAQ', 'FAQ', 'index,follow', NULL, NULL, '', 1, 1, '2024-06-01 11:50:48', '2025-08-30 16:52:24'),
(11, 'Pricing', 'Pricing', '<p><br></p>', 'Explore the enchanting world of Palace on Wheels through our captivating image gallery. Immerse yourself in the opulence and grandeur of this iconic luxury train as it journeys through the cultural treasures of Rajasthan. Witness the beauty and elegance that defines this extraordinary travel experience.', NULL, NULL, 'Pricing', 'Pricing', 'Pricing', 'index,follow', NULL, NULL, NULL, 1, 1, '2024-06-01 11:50:48', '2025-09-05 07:34:02');

-- --------------------------------------------------------

--
-- Table structure for table `newsletter`
--

CREATE TABLE `newsletter` (
  `id` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `newsletter`
--

INSERT INTO `newsletter` (`id`, `email`, `status`, `created_at`, `updated_at`) VALUES
(1, 'manish@test.com', 1, '2025-08-29 15:57:57', '2025-08-29 15:57:57'),
(2, 'test@test.coms', 1, '2025-08-29 15:59:09', '2025-08-29 15:59:09'),
(3, 'hello@test.com', 1, '2025-08-29 16:02:35', '2025-08-29 16:02:35');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `unit_price` float(9,2) DEFAULT NULL,
  `max_unit` int(11) DEFAULT NULL,
  `per_unit_register_fees` float(9,2) DEFAULT NULL,
  `return_in_days` int(11) DEFAULT NULL,
  `interest_rate` float(9,2) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `unit_price`, `max_unit`, `per_unit_register_fees`, `return_in_days`, `interest_rate`, `status`, `created_at`, `updated_at`) VALUES
(1, 'MSP 2B', 20000.00, 9, 1998.00, 40, 40.00, 1, '2025-09-05 09:20:10', '2025-09-05 09:25:51'),
(2, 'MSP 1A', 34.00, 4, 1500.00, 10, 2.00, 1, '2025-09-05 09:31:40', '2025-09-05 09:32:23');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `admin_email` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `business_address` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `whatsapp` varchar(50) DEFAULT NULL,
  `footer_content` varchar(255) DEFAULT NULL,
  `footer_info` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `admin_email`, `company_name`, `business_address`, `mobile`, `whatsapp`, `footer_content`, `footer_info`, `logo`, `created_at`, `updated_at`) VALUES
(1, 'webddh1989@gmail.com', 'WBD IT Solutions', '62, Moti Nagar, Jhotwara, Jaipur, 302012.', '7737406899', '7737406899', '© Copyright © 2019-2025 WEBD IT SOLUTIONS | All Rights Reserved', 'We provide comprehensive IT services, including web design, software development, and technology solutions to help businesses with digital transformation, operational efficiency, and sustainable growth.', '1737978390379765911.jpg', '2023-04-26 18:10:27', '2025-09-05 06:29:43');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `otp` varchar(255) DEFAULT NULL,
  `slug` text DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'User' COMMENT 'Teacher/Student',
  `username` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `password` text DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `dob` varchar(255) DEFAULT NULL,
  `added_by` int(11) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `branch_id` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(200) DEFAULT NULL,
  `country` varchar(200) DEFAULT NULL,
  `zipcode` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `website` text DEFAULT NULL,
  `referral_code` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `business_document` varchar(255) DEFAULT NULL,
  `product_manufatured_type` varchar(255) DEFAULT NULL,
  `manufacture_unit_address` varchar(255) DEFAULT NULL,
  `monthly_output` varchar(255) DEFAULT NULL,
  `sell_on_caabaa` varchar(255) DEFAULT NULL,
  `service_type` text DEFAULT NULL,
  `workshop_address` text DEFAULT NULL,
  `service_pincode_coverage` varchar(255) DEFAULT NULL,
  `experience` varchar(255) DEFAULT NULL,
  `certificate` varchar(255) DEFAULT NULL,
  `service_on_caabaa` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `is_delete` tinyint(4) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `otp`, `slug`, `type`, `username`, `name`, `email`, `mobile`, `password`, `gender`, `dob`, `added_by`, `phone`, `branch_id`, `address`, `city`, `state`, `country`, `zipcode`, `photo`, `website`, `referral_code`, `logo`, `business_document`, `product_manufatured_type`, `manufacture_unit_address`, `monthly_output`, `sell_on_caabaa`, `service_type`, `workshop_address`, `service_pincode_coverage`, `experience`, `certificate`, `service_on_caabaa`, `status`, `is_delete`, `last_login`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, 'Admin', 'SuperAdmin', 'Narendra Jangid', 'admin@admin.com', '1111111111', '$2y$10$J3FtsBY9Xiy.ux7xRNRbOe3uEOoRHD4m1fkUccHC/RtVvlAMhxTb6', NULL, NULL, 0, '1111111111', '', 'Rajasthan', 'Jaipur', 'Rajsthan', 'India', '302012', '1756371562.jpg', '', '', NULL, NULL, '', '', '', '', '', '', '', '', NULL, '', 1, NULL, NULL, '2022-12-07 08:11:31', '2025-09-05 06:29:36'),
(2, NULL, NULL, 'Account', 'Admin', NULL, NULL, '11223344556', '$2y$10$FqIN84e3gkRL4au2ljEkdOxnDowDjc7/G4eVqCr4gvw.L8N5u7VMC', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2025-09-05 10:20:33', '2025-09-05 10:24:27'),
(3, NULL, NULL, 'BranchAdmin', NULL, 'S Mukhetrjee', NULL, '1666666667', '$2y$10$DR0GeLq/v5y.4dYqpdK4Su4la4E0IpmfAW1cjjxJQB7Zqjeh6amB.', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2025-09-05 12:12:51', '2025-09-05 12:28:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ally_types`
--
ALTER TABLE `ally_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inner_pages`
--
ALTER TABLE `inner_pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `newsletter`
--
ALTER TABLE `newsletter`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
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
-- AUTO_INCREMENT for table `ally_types`
--
ALTER TABLE `ally_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `inner_pages`
--
ALTER TABLE `inner_pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `newsletter`
--
ALTER TABLE `newsletter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
