-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 21, 2025 at 01:39 PM
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
-- Database: `chaircraft_admin`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `created_at`, `updated_at`) VALUES
(44, 53, 38, 1, '2025-09-21 03:49:47', '2025-09-21 03:49:47');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Office Chairs', 'Ergonomic and comfortable chairs for office use', '2025-08-22 12:27:03', '2025-08-22 13:27:50'),
(3, 'Executive Chairs', 'Premium chairs for executives and managers', '2025-08-22 12:27:03', '2025-08-22 12:27:03'),
(4, 'Luxury Chairs', 'A perfect fusion of elegance and functionality', '2025-08-22 12:27:03', '2025-09-08 04:51:12'),
(5, 'Boss Chairs', 'A symbol of executive comfort and elegance', '2025-08-22 12:27:03', '2025-09-08 04:45:34');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `payment_method` varchar(20) NOT NULL DEFAULT 'cash_on_delivery',
  `shipping_address` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `status`, `payment_method`, `shipping_address`, `created_at`) VALUES
(15, 53, 15999.00, 'confirmed', 'cash_on_delivery', 'hello world, this is address demo', '2025-09-20 11:24:40'),
(16, 53, 17999.00, 'user_cancelled', 'cash_on_delivery', 'hhhhhhhhhhhhhhhhhhhhhhhhhhhh', '2025-09-20 11:38:43');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `quantity`, `price`) VALUES
(18, 15, 38, 'Alaster Blue Medium Back Luxury Chair', 1, 15999.00),
(19, 16, 37, 'Magnet High Back Luxury Chair', 1, 17999.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `stock`, `image`, `category_id`) VALUES
(14, 'Matrix High Back Ergonomic Chair', 'Matrix High Back Ergonomic Chair with Adjustable Neck & Arm Rest, Synchro Tilting Mechanism, Hydraulic Height Adjustment & Heavy Duty Wheels, Black Seat', 5399.00, 10, 'gallery-7.jpg', 1),
(15, 'Colt White Workstation Chair', 'Colt White Workstation Chair with Center Tilting Mechanism, Hydraulic Height Adjustment, and Heavy Duty Wheels, Blue Seat', 4399.00, 10, 'Colt-White.png.webp', 1),
(16, 'Dale Grey High Back Ergonomic Chair', 'Dale Grey High Back Ergonomic Chair with Adjustable Neck & Arm Rest, Synchro Tilting Mechanism, Hydraulic Height Adjustment & Heavy Duty Wheels, Blue Seat', 6599.00, 10, 'gallery-6.jpg', 1),
(17, 'Aura High Back Ergonomic Chair', 'Aura High Back Ergonomic Chair with Adjustable Neck Rest, Synchro Tilting Mechanism, Hydraulic Height Adjustment & Heavy Duty Wheels, Blue Seat', 5500.00, 10, 'Aura-5.png.webp', 1),
(18, 'Libra Workstation Chair', 'Libra Workstation Chair with Center Tilting Mechanism, Hydraulic Height Adjustment, and Heavy Duty Wheels, Black Seat', 3350.00, 10, 'Libra.png.webp', 1),
(19, 'Zello High Back Boss Chair', 'Zello High Back Boss Chair with Center Tilting Mechanism, Hydraulic Height Adjustment, Rexine Material & Heavy Duty Wheels, Tan Seat', 6499.00, 10, 'gallery-9.jpg', 1),
(20, 'Spinal White High Back Executive Chair', 'Spinal White High Back Executive Chair with Adjustable Headrest, Adjustable Armrests, Fixed Lumbar Support, Single-Stage Synchro Locking Mechanism, Aluminium Base, and 60mm Nylon Castors', 7999.00, 10, 'Spinal-HB-White.png.webp', 3),
(21, 'Datsun High Back Chair', 'Datsun High Back Chair with Adjustable Arm Rest, Synchro Tilting Mechanism, Hydraulic Height Adjustment & Heavy Duty Wheels, Tan Seat', 5899.00, 10, 'Datsun-2.png.webp', 3),
(23, 'Meteor High Back Exclusive Chair', 'Meteor High Back Exclusive Chair with Adjustable Neck & Arm Rest, Synchro Tilting Mechanism, Hydraulic Height Adjustment & Heavy Duty Wheels, Beige Seat', 6999.00, 10, 'Meteor-2.png.webp', 3),
(24, 'Ample High Back Exclusive Chair', 'Ample High Back Exclusive Chair with Adjustable Neck Rest, Synchro Tilting Mechanism, Hydraulic Height Adjustment & Heavy Duty Wheels, Black Seat', 6999.00, 10, 'Ample-5.png.webp', 3),
(25, 'Matrix Medium Back Exclusive Chair', 'Matrix Medium Back Exclusive Chair with Adjustable Arm Rest, Synchro Tilting Mechanism, Hydraulic Height Adjustment & Heavy Duty Wheels, Black Seat', 4599.00, 10, 'Matrix-1.png.webp', 3),
(26, 'Polo Grey Medium Back Exclusive Workstation Chair', 'Polo Grey Medium Back Exclusive Workstation Chair with Tilting Mechanism, Hydraulic Height Adjustment, and Heavy Duty Wheels, Tan Seat', 5699.00, 10, 'Polo-Grey.png.webp', 3),
(27, 'Vatican High Back Boss Chair', 'Vatican High Back Boss Chair with Center Tilting Mechanism, Hydraulic Height Adjustment, Rexine Material & Heavy Duty Wheels, Brown Seat', 9599.00, 10, 'gallery-4.jpg', 5),
(28, '3 Line High Back Boss Chair', '3 Line High Back Boss Chair with Center Tilting Mechanism, Hydraulic Height Adjustment, Rexine Material & Heavy Duty Wheels, Black Seat', 6499.00, 10, 'gallery-12.jpg', 5),
(29, 'Elegant High Back Ergonomic Boss', 'Elegant High Back Ergonomic Boss Chair with Synchro Tilting Mechanism, Hydraulic Height Adjustment, Rexine Material & Heavy Duty Wheels, Green Seat', 10500.00, 5, 'Elegant-2.png.webp', 5),
(30, 'Legacy High Back Boss Chair', 'Legacy High Back Boss Chair with Center Tilting Mechanism, Hydraulic Height Adjustment, Rexine Material & Heavy Duty Wheels, Beige Seat', 9999.00, 4, 'gallery-3.jpg', 5),
(31, 'BOSS High Back Ergonomic Boss Chair', 'BOSS High Back Ergonomic Boss Chair with Adjustable Neck Rest, Synchro Tilt Locking Mechanism, Hydraulic Height Adjustment, Rexine Material & Heavy Duty Wheels, Beige Seat', 9599.00, 7, 'Boss-2.png.webp', 5),
(32, 'Molto High Back Ergonomic Boss Chair', 'Molto High Back Ergonomic Boss Chair with Synchro Tilting Mechanism, Hydraulic Height Adjustment, Rexine Material & Heavy Duty Wheels, Blue Seat', 10999.00, 3, 'Molto-2.png.webp', 5),
(33, 'Boss Brown High Back Luxury Chair', 'Boss Brown High Back Luxury Chair with Knee-Tilt Mechanism, Multi-Locking Function, Aluminium Die-Cast Armrests with Padded Cushion, Aluminium Die-Cast Base, 60mm Nylon Castors, and Class 4 Gas Lift.', 11999.00, 6, 'BOSS-HB.png.webp', 4),
(34, 'Bentley Leather High Back Luxury Chair', 'Bentley Leather High Back Luxury Chair with Fixed Armrests, Premium Leather Upholstery, Knee Tilt Synchro Mechanism, Multi-Position Lock, Aluminium Die-Cast Base, and Class 4 Gas Lift', 15999.00, 4, 'BENTLY-HB.png.webp', 4),
(35, 'Jaguar High Back Ergonomic Chai', 'Jaguar High Back Ergonomic Chair with Fixed Armrests, Knee Tilt Mechanism, Multi-Position Lock, Aluminium Diecast Base, and 60mm Nylon Castors.', 11599.00, 9, 'JAGUAR-HB-1.png.webp', 4),
(36, 'Oyster Leather High Back Luxury Chair', 'Oyster Leather High Back Luxury Chair with Adjustable Neck & Arm Rest, Synchro Tilting Mechanism, Hydraulic Height Adjustment & Heavy Duty Wheels, Leather Seat', 14999.00, 7, 'OYESTER-HB.png.webp', 4),
(37, 'Magnet High Back Luxury Chair', 'Magnet High Back Luxury Chair with Handcrafted Leather Upholstery, Adjustable Armrests, Knee-Tilt Donati Mechanism, Multi-Position Lock, and Aluminium Die-Cast Base with Nylon Castors', 17999.00, 4, 'MAGNET-HB-1.png.webp', 4),
(38, 'Alaster Blue Medium Back Luxury Chair', 'Alaster Blue Medium Back Luxury Chair with Adjustable Headrest, 2D Adjustable Armrests, Lumbar Support, Synchro Tilting Mechanism, Multi-Lock Feature, and Heavy Duty Glass-Filled Nylon Base with Nylon Castors', 15999.00, 0, 'ALASTER-BLUE-MB.png.webp', 4),
(39, 'Duster High Back Luxury Chair', 'Duster High Back Luxury Chair with Headrest, Syncro Dynamic Mechanism, Auto Body Weight Adjustment, Cantilever Aluminium Die-Cast Armrests with PU Pads, 700mm Aluminium Die-Cast Base with 60mm Nylon Castors & Class 4 Gas Lift', 14599.00, 3, 'DUSTER-CUSH-HB.png.webp', 5);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_as` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 = User, 1 = Admin',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `phone`, `email`, `password`, `role_as`, `created_at`) VALUES
(52, 'dummy', '1234567890', 'dummy@gmail.com', '$2y$10$4KEGaqifDZHoKzNFRkfty.NdqFseJTVfDqm.qqOz3Sudp5q5ITUEq', 0, '2025-09-20 16:48:54'),
(53, 'user', '1234567899', 'user@gmail.com', '$2y$10$rF/zHaD2aaj2c0JMdMAsPeX9CQ75SwIpSwMPBpjcMZKEHwXL4c0HC', 0, '2025-09-20 16:51:03'),
(54, 'admin', '1234567890', 'admin@cc.in', '$2y$10$TxwRCbLg2zPXa2Ma7P2fOuOTx9l8WwQZC2dWf1nH8cTXELH2vuszq', 1, '2025-09-21 17:02:19');

-- --------------------------------------------------------

--
-- Table structure for table `user_requests`
--

CREATE TABLE `user_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `chair` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `location` text NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_requests`
--
ALTER TABLE `user_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `user_requests`
--
ALTER TABLE `user_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_requests`
--
ALTER TABLE `user_requests`
  ADD CONSTRAINT `user_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
