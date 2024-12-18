-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 18, 2024 at 10:24 AM
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
-- Database: `kmkdb_final`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_table`
--

CREATE TABLE `admin_table` (
  `adminID` int(11) NOT NULL,
  `adminName` varchar(255) NOT NULL,
  `adminEmail` varchar(255) NOT NULL,
  `adminPassword` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_table`
--

INSERT INTO `admin_table` (`adminID`, `adminName`, `adminEmail`, `adminPassword`) VALUES
(1, 'KMK Apparel Admin', 'kmkadmin@example.com', '$2y$10$SeRae9U5Qr1trfqsRuRgqe9vzpFxIc6ThBK4bQn0n2hMMQ8q9w8dG');

-- --------------------------------------------------------

--
-- Table structure for table `cart_table`
--

CREATE TABLE `cart_table` (
  `cartID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `productID` int(11) NOT NULL,
  `sizeName` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category_table`
--

CREATE TABLE `category_table` (
  `id` int(11) NOT NULL,
  `productID` int(11) NOT NULL,
  `productCategory` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category_table`
--

INSERT INTO `category_table` (`id`, `productID`, `productCategory`, `created_at`, `modified_at`) VALUES
(3, 3, 'Equipment', '2024-05-13 07:45:43', '2024-05-13 07:45:43'),
(4, 4, 'Footwear', '2024-05-14 02:27:08', '2024-05-14 02:27:08'),
(5, 5, 'Apparel', '2024-05-16 07:25:55', '2024-05-16 07:25:55'),
(6, 6, 'Footwear', '2024-05-17 09:58:49', '2024-05-17 09:58:49');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_table`
--

CREATE TABLE `inventory_table` (
  `varID` int(11) NOT NULL,
  `productID` int(11) NOT NULL,
  `productSize` varchar(255) NOT NULL,
  `productQuantity` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory_table`
--

INSERT INTO `inventory_table` (`varID`, `productID`, `productSize`, `productQuantity`, `created_at`, `modified_at`) VALUES
(8, 3, 'Small', 2, '2024-05-13 07:45:43', '2024-05-14 02:32:25'),
(9, 3, 'Medium', 6, '2024-05-13 07:45:43', '2024-05-18 05:33:06'),
(10, 4, '7', 4, '2024-05-14 02:27:08', '2024-05-14 02:27:08'),
(11, 4, '8', 5, '2024-05-14 02:27:08', '2024-05-18 07:09:27'),
(12, 4, '9', 5, '2024-05-14 02:27:08', '2024-05-15 16:45:35'),
(13, 4, '10', 0, '2024-05-14 02:27:08', '2024-05-18 05:35:45'),
(14, 5, 'Small', 5, '2024-05-16 07:25:55', '2024-05-16 07:25:55'),
(15, 5, 'Medium', 2, '2024-05-16 07:25:55', '2024-05-18 05:33:06'),
(16, 5, 'Large', 8, '2024-05-16 07:25:55', '2024-05-16 07:28:19'),
(17, 6, '7', 7, '2024-05-17 09:58:49', '2024-05-17 09:58:49'),
(18, 6, '8', 8, '2024-05-17 09:58:49', '2024-05-17 09:58:49'),
(19, 6, '9', 9, '2024-05-17 09:58:49', '2024-05-17 10:06:25'),
(20, 6, '10', 11, '2024-05-17 09:58:49', '2024-05-17 09:58:49');

-- --------------------------------------------------------

--
-- Table structure for table `order_details_table`
--

CREATE TABLE `order_details_table` (
  `orderDetailID` int(11) NOT NULL,
  `orderID` int(11) NOT NULL,
  `productID` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_details_table`
--

INSERT INTO `order_details_table` (`orderDetailID`, `orderID`, `productID`, `quantity`, `price`) VALUES
(34, 19, 2, 1, 1499),
(35, 19, 1, 1, 6969),
(36, 19, 3, 1, 699),
(37, 20, 2, 1, 1499),
(38, 21, 3, 2, 699),
(39, 30, 1, 1, 6969),
(40, 31, 1, 1, 6969),
(41, 33, 1, 1, 6969),
(42, 34, 1, 1, 6969),
(43, 35, 2, 1, 1499),
(44, 36, 2, 1, 1499),
(45, 37, 3, 1, 699),
(46, 38, 2, 1, 1499),
(47, 38, 3, 1, 699),
(48, 39, 2, 1, 1499),
(49, 39, 3, 1, 699),
(50, 40, 4, 1, 4789),
(51, 41, 4, 1, 4789),
(52, 42, 2, 1, 1499),
(53, 43, 4, 2, 4789),
(54, 43, 2, 4, 1499),
(55, 44, 5, 1, 50000),
(56, 44, 5, 5, 50000),
(57, 45, 2, 5, 1499),
(58, 46, 4, 1, 4789),
(59, 47, 2, 1, 1499),
(60, 48, 6, 1, 600000),
(61, 49, 4, 1, 4789),
(62, 49, 5, 1, 50000),
(63, 49, 3, 1, 699),
(64, 50, 4, 4, 4789),
(65, 51, 4, 1, 4789);

-- --------------------------------------------------------

--
-- Table structure for table `order_table`
--

CREATE TABLE `order_table` (
  `orderID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `order_date` datetime NOT NULL,
  `orderStatus` varchar(50) NOT NULL DEFAULT '''Processing''',
  `totalAmount` decimal(10,2) NOT NULL,
  `shippingAddress` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_table`
--

INSERT INTO `order_table` (`orderID`, `userID`, `order_date`, `orderStatus`, `totalAmount`, `shippingAddress`, `created_at`) VALUES
(19, 2, '2024-05-14 08:02:44', 'Delivered', 9167.00, 'P-16, Poblacion, Valencia City, Bukidnon', '2024-05-15 16:20:10'),
(20, 2, '2024-05-14 08:10:33', 'Processing', 1499.00, 'P-16, Poblacion, Valencia City, Bukidnon', '2024-05-14 00:10:33'),
(21, 2, '2024-05-14 08:15:41', 'Processing', 1398.00, 'P-16, Poblacion, Valencia City, Bukidnon', '2024-05-14 00:15:41'),
(30, 2, '2024-05-14 08:25:25', 'Processing', 6969.00, 'P-16, Poblacion, Valencia City, Bukidnon', '2024-05-14 00:25:25'),
(31, 2, '2024-05-14 08:45:51', 'Processing', 6969.00, 'P-16, Poblacion, Valencia City, Bukidnon', '2024-05-14 00:45:51'),
(33, 2, '2024-05-14 08:46:37', 'Processing', 6969.00, 'P-16, Poblacion, Valencia City, Bukidnon', '2024-05-14 00:46:37'),
(34, 2, '2024-05-14 08:49:21', 'Processing', 6969.00, 'P-16, Poblacion, Valencia City, Bukidnon', '2024-05-14 00:49:21'),
(35, 2, '2024-05-14 09:48:58', 'Processing', 1499.00, 'P-16, Poblacion, Valencia City, Bukidnon', '2024-05-14 01:48:58'),
(36, 2, '2024-05-14 09:49:36', 'Processing', 1499.00, 'P-16, Poblacion, Valencia City, Bukidnon', '2024-05-14 01:49:36'),
(37, 2, '2024-05-14 09:51:27', 'Processing', 699.00, 'P-16, Poblacion, Valencia City, Bukidnon', '2024-05-14 01:51:27'),
(38, 2, '2024-05-14 09:53:44', 'Processing', 2198.00, 'P-16, Poblacion, Valencia City, Bukidnon', '2024-05-14 01:53:44'),
(39, 3, '2024-05-14 10:32:25', 'Processing', 2198.00, 'P-16, Poblacion, Valencia City, Bukidnon', '2024-05-14 02:32:25'),
(40, 2, '2024-05-15 23:28:26', 'Processing', 4789.00, 'P-16, Poblacion, Valencia City, Bukidnon', '2024-05-15 15:28:26'),
(41, 2, '2024-05-15 23:58:01', 'Processing', 4789.00, 'P-16, Poblacion, Valencia City, Bukidnon', '2024-05-15 15:58:01'),
(42, 2, '2024-05-15 23:58:29', 'Processing', 1499.00, 'P-16, Poblacion, Valencia City, Bukidnon', '2024-05-15 15:58:29'),
(43, 2, '2024-05-16 00:45:35', 'Processing', 15574.00, 'P-16, Poblacion, Valencia City, Bukidnon', '2024-05-15 16:45:35'),
(44, 2, '2024-05-16 15:28:19', 'Delivered', 300000.00, 'P-16, Poblacion, Valencia City, Bukidnon', '2024-05-16 07:29:03'),
(45, 4, '2024-05-16 15:36:06', 'Processing', 7495.00, 'valencia', '2024-05-16 07:36:06'),
(46, 2, '2024-05-16 22:14:00', 'Processing', 4789.00, 'P-16, Poblacion, Valencia City, Bukidnon', '2024-05-16 14:14:00'),
(47, 5, '2024-05-17 17:57:17', 'Delivered', 1499.00, 'Valencia City, Bukidnon', '2024-05-17 09:58:01'),
(48, 5, '2024-05-17 18:06:25', 'Delivered', 600000.00, 'Valencia City, Bukidnon', '2024-05-18 05:12:32'),
(49, 2, '2024-05-18 13:33:06', 'Processing', 55488.00, 'P-16, Poblacion, Valencia City, Bukidnon', '2024-05-18 05:33:06'),
(50, 2, '2024-05-18 13:35:45', 'Processing', 19156.00, 'P-16, Poblacion, Valencia City, Bukidnon', '2024-05-18 05:35:45'),
(51, 6, '2024-05-18 15:09:27', 'Delivered', 4789.00, 'PUrok oten', '2024-05-18 07:10:30');

-- --------------------------------------------------------

--
-- Table structure for table `product_table`
--

CREATE TABLE `product_table` (
  `productID` int(11) NOT NULL,
  `productName` varchar(255) NOT NULL,
  `productDescription` varchar(255) NOT NULL,
  `productBrand` varchar(255) NOT NULL,
  `productPrice` int(11) NOT NULL,
  `productImage` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_table`
--

INSERT INTO `product_table` (`productID`, `productName`, `productDescription`, `productBrand`, `productPrice`, `productImage`, `created_at`, `modified_at`) VALUES
(3, 'Ball', 'Basketball', 'Nike', 699, 'ball1.png', '2024-05-13 07:45:43', '2024-05-13 07:45:43'),
(4, 'Air Jordan 1 Low SE', 'Leather Suede', 'Nike', 4789, 'shoes1.png', '2024-05-14 02:27:08', '2024-05-14 02:27:08'),
(5, 'Short', 'Nike', 'Nike', 50000, 'short3.png', '2024-05-16 07:25:55', '2024-05-16 07:25:55'),
(6, 'Travis Scot x Nike ', 'Leathe', 'Nike', 600000, 'shoes2.png', '2024-05-17 09:58:49', '2024-05-17 09:58:49');

-- --------------------------------------------------------

--
-- Table structure for table `sales_invoice_table`
--

CREATE TABLE `sales_invoice_table` (
  `invoiceID` int(11) NOT NULL,
  `orderID` int(11) NOT NULL,
  `invoiceDate` datetime DEFAULT current_timestamp(),
  `invoiceAmount` decimal(10,2) NOT NULL,
  `productID` int(11) NOT NULL,
  `sizeName` varchar(255) NOT NULL,
  `productCategory` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales_invoice_table`
--

INSERT INTO `sales_invoice_table` (`invoiceID`, `orderID`, `invoiceDate`, `invoiceAmount`, `productID`, `sizeName`, `productCategory`) VALUES
(1, 19, '2024-05-14 08:02:44', 1499.00, 2, 'Large', 'Apparel'),
(2, 19, '2024-05-14 08:02:44', 1499.00, 2, 'Large', 'Apparel'),
(3, 19, '2024-05-14 08:02:44', 6969.00, 1, '11', 'Footwear'),
(4, 19, '2024-05-14 08:02:44', 6969.00, 1, '11', 'Footwear'),
(5, 19, '2024-05-14 08:02:44', 699.00, 3, 'Small', 'Equipment'),
(6, 19, '2024-05-14 08:02:44', 699.00, 3, 'Small', 'Equipment'),
(7, 20, '2024-05-14 08:10:33', 1499.00, 2, 'Small', 'Apparel'),
(8, 21, '2024-05-14 08:15:41', 1398.00, 3, 'Medium', 'Equipment'),
(9, 30, '2024-05-14 08:25:25', 6969.00, 1, '11', 'Footwear'),
(10, 31, '2024-05-14 08:45:51', 6969.00, 1, '9', 'Footwear'),
(11, 33, '2024-05-14 08:46:37', 6969.00, 1, '10', 'Footwear'),
(12, 34, '2024-05-14 08:49:21', 6969.00, 1, '10', 'Footwear'),
(13, 35, '2024-05-14 09:48:58', 1499.00, 2, '0', 'Apparel'),
(14, 36, '2024-05-14 09:49:36', 1499.00, 2, '0', 'Apparel'),
(15, 37, '2024-05-14 09:51:27', 699.00, 3, '0', 'Equipment'),
(16, 38, '2024-05-14 09:53:44', 1499.00, 2, '0', 'Apparel'),
(17, 38, '2024-05-14 09:53:44', 699.00, 3, '0', 'Equipment'),
(18, 39, '2024-05-14 10:32:25', 1499.00, 2, '0', 'Apparel'),
(19, 39, '2024-05-14 10:32:25', 699.00, 3, '0', 'Equipment'),
(20, 40, '2024-05-15 23:28:26', 4789.00, 4, '10', 'Footwear'),
(21, 41, '2024-05-15 23:58:01', 4789.00, 4, '10', 'Footwear'),
(22, 42, '2024-05-15 23:58:29', 1499.00, 2, '0', 'Apparel'),
(23, 43, '2024-05-16 00:45:35', 9578.00, 4, '9', 'Footwear'),
(24, 43, '2024-05-16 00:45:35', 5996.00, 2, '0', 'Apparel'),
(25, 44, '2024-05-16 15:28:19', 50000.00, 5, '0', 'Apparel'),
(26, 44, '2024-05-16 15:28:19', 250000.00, 5, '0', 'Apparel'),
(27, 45, '2024-05-16 15:36:06', 7495.00, 2, '0', 'Apparel'),
(28, 46, '2024-05-16 22:14:00', 4789.00, 4, '10', 'Footwear'),
(29, 47, '2024-05-17 17:57:17', 1499.00, 2, '0', 'Apparel'),
(30, 48, '2024-05-17 18:06:25', 600000.00, 6, '9', 'Footwear'),
(31, 49, '2024-05-18 13:33:06', 4789.00, 4, '10', 'Footwear'),
(32, 49, '2024-05-18 13:33:06', 50000.00, 5, '0', 'Apparel'),
(33, 49, '2024-05-18 13:33:06', 699.00, 3, '0', 'Equipment'),
(34, 50, '2024-05-18 13:35:45', 19156.00, 4, '10', 'Footwear'),
(35, 51, '2024-05-18 15:09:27', 4789.00, 4, '8', 'Footwear');

-- --------------------------------------------------------

--
-- Table structure for table `user_table`
--

CREATE TABLE `user_table` (
  `userID` int(11) NOT NULL,
  `userFullname` varchar(255) NOT NULL,
  `userName` varchar(255) NOT NULL,
  `userEmail` varchar(255) NOT NULL,
  `userPassword` varchar(255) NOT NULL,
  `userAddress` varchar(255) NOT NULL,
  `mobileNo` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_table`
--

INSERT INTO `user_table` (`userID`, `userFullname`, `userName`, `userEmail`, `userPassword`, `userAddress`, `mobileNo`, `created_at`, `modified_at`) VALUES
(2, 'James Abaquita', 'James', 'jamesrioabaquita0@gmail.com', '$2y$10$OsbSACdvLH1P61NZLG0bgur0iY.tenLQMHc5xJV0LInzCv4M0rJ4G', 'P-16, Poblacion, Valencia City, Bukidnon', '09914307297', '2024-05-13 22:25:44', '2024-05-15 15:28:04'),
(3, 'Sedrick James Camiguing', 'sedrickjames', 'sedrick@gmail.com', '$2y$10$lfCKAlsrQjWRzBFs0WSv9u8oqpVZ/Y7Qw/k6uidecMWEBf57CyQF.', 'P-16, Poblacion, Valencia City, Bukidnon', '09914307297', '2024-05-14 02:29:03', '2024-05-14 02:29:03'),
(4, 'nimo', 'Otin', 'bayolet@gmail.com', '$2y$10$hBpA9W6j5WqDV2u0OIviUuu/id2vdRuFZDdIByj9Jpuoords2mlre', 'valencia', '09914307297', '2024-05-16 07:33:10', '2024-05-16 07:33:10'),
(5, 'Ethel Joy Bangcoyo', 'Ethel', 'ethel@gmail.com', '$2y$10$077aMFif22BYugg8kE6mBufBKWHHO7kdFhXuYJfEkeG25YFu37XBu', 'Valencia City, Bukidnon', '099143020120', '2024-05-17 09:56:25', '2024-05-17 09:56:25'),
(6, 'tito', 'etits', 'tit@oten.com', '$2y$10$j7rYjRFXpw7HuqKKxmDlJ.Mq.90fha8q9dAcYSgdnV7W3WX0VJTPW', 'PUrok oten', '0901000000', '2024-05-18 07:07:41', '2024-05-18 07:07:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_table`
--
ALTER TABLE `admin_table`
  ADD PRIMARY KEY (`adminID`);

--
-- Indexes for table `cart_table`
--
ALTER TABLE `cart_table`
  ADD PRIMARY KEY (`cartID`),
  ADD KEY `productID` (`productID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `category_table`
--
ALTER TABLE `category_table`
  ADD PRIMARY KEY (`id`),
  ADD KEY `productID` (`productID`);

--
-- Indexes for table `inventory_table`
--
ALTER TABLE `inventory_table`
  ADD PRIMARY KEY (`varID`),
  ADD KEY `productID` (`productID`);

--
-- Indexes for table `order_details_table`
--
ALTER TABLE `order_details_table`
  ADD PRIMARY KEY (`orderDetailID`),
  ADD KEY `orderID` (`orderID`),
  ADD KEY `productID` (`productID`);

--
-- Indexes for table `order_table`
--
ALTER TABLE `order_table`
  ADD PRIMARY KEY (`orderID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `product_table`
--
ALTER TABLE `product_table`
  ADD PRIMARY KEY (`productID`);

--
-- Indexes for table `sales_invoice_table`
--
ALTER TABLE `sales_invoice_table`
  ADD PRIMARY KEY (`invoiceID`),
  ADD KEY `orderID` (`orderID`),
  ADD KEY `productID` (`productID`);

--
-- Indexes for table `user_table`
--
ALTER TABLE `user_table`
  ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_table`
--
ALTER TABLE `admin_table`
  MODIFY `adminID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart_table`
--
ALTER TABLE `cart_table`
  MODIFY `cartID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `category_table`
--
ALTER TABLE `category_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `inventory_table`
--
ALTER TABLE `inventory_table`
  MODIFY `varID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `order_details_table`
--
ALTER TABLE `order_details_table`
  MODIFY `orderDetailID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `order_table`
--
ALTER TABLE `order_table`
  MODIFY `orderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `product_table`
--
ALTER TABLE `product_table`
  MODIFY `productID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `sales_invoice_table`
--
ALTER TABLE `sales_invoice_table`
  MODIFY `invoiceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `user_table`
--
ALTER TABLE `user_table`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart_table`
--
ALTER TABLE `cart_table`
  ADD CONSTRAINT `cart_table_ibfk_1` FOREIGN KEY (`productID`) REFERENCES `product_table` (`productID`),
  ADD CONSTRAINT `cart_table_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `user_table` (`userID`);

--
-- Constraints for table `category_table`
--
ALTER TABLE `category_table`
  ADD CONSTRAINT `category_table_ibfk_1` FOREIGN KEY (`productID`) REFERENCES `product_table` (`productID`);

--
-- Constraints for table `inventory_table`
--
ALTER TABLE `inventory_table`
  ADD CONSTRAINT `inventory_table_ibfk_1` FOREIGN KEY (`productID`) REFERENCES `product_table` (`productID`);

--
-- Constraints for table `order_details_table`
--
ALTER TABLE `order_details_table`
  ADD CONSTRAINT `order_details_table_ibfk_1` FOREIGN KEY (`orderID`) REFERENCES `order_table` (`orderID`),
  ADD CONSTRAINT `order_details_table_ibfk_2` FOREIGN KEY (`productID`) REFERENCES `product_table` (`productID`);

--
-- Constraints for table `order_table`
--
ALTER TABLE `order_table`
  ADD CONSTRAINT `order_table_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user_table` (`userID`);

--
-- Constraints for table `sales_invoice_table`
--
ALTER TABLE `sales_invoice_table`
  ADD CONSTRAINT `sales_invoice_table_ibfk_1` FOREIGN KEY (`orderID`) REFERENCES `order_table` (`orderID`),
  ADD CONSTRAINT `sales_invoice_table_ibfk_2` FOREIGN KEY (`productID`) REFERENCES `product_table` (`productID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
