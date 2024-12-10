SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `bids` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `bidder_name` varchar(255) NOT NULL,
  `bid_amount` decimal(10,2) NOT NULL,
  `bid_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `bid_status` enum('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
  `payment_status` varchar(255) DEFAULT 'Pending',
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `bids` (`id`, `product_id`, `bidder_name`, `bid_amount`, `bid_time`, `bid_status`, `payment_status`, `user_id`) VALUES
(1, 4, 'Yaksh', 1223.00, '2024-11-27 11:03:25', 'Approved', 'Done', 0),
(8, 7, 'Yakshshbfhsdbf', 500.00, '2024-11-27 13:39:40', 'Approved', 'Done', 0),
(9, 8, 'Yaksh', 1000.00, '2024-11-27 13:44:02', 'Approved', 'Done', 0),
(10, 9, 'Yaksh', 13.00, '2024-11-27 13:54:16', 'Approved', 'Pending', 0),
(11, 9, 'Yaksh', 20.00, '2024-11-27 13:55:19', 'Approved', 'Pending', 0),
(12, 9, 'Yaksh', 30.00, '2024-11-27 13:57:55', 'Approved', 'Done', 0),
(13, 10, 'a@gmail.com', 200.00, '2024-11-27 14:18:55', 'Approved', 'Pending', 0),
(14, 10, 'a@gmail.com', 1000.00, '2024-11-27 14:20:24', 'Approved', 'Pending', 1),
(15, 10, 'a@gmail.com', 2000.00, '2024-11-27 14:32:03', 'Approved', 'Done', 1);

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `starting_bid` decimal(10,2) NOT NULL,
  `product_description` text NOT NULL,
  `product_image` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `showp` varchar(255) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `product` (`id`, `product_name`, `starting_bid`, `product_description`, `product_image`, `category`, `showp`, `created_at`) VALUES
(4, 'sfsd', 200.00, 'hvyy', 'uploads/IMG20241126095244.jpg', 'shoes', '0', '2024-11-26 23:47:38'),
(7, 'sfsd', 400.00, 'sbdfsdb', 'uploads/IMG20241126095244.jpg', 'clothing', '0', '2024-11-27 09:09:24'),
(8, 'Iphone 13', 100.00, 'Iphone 13', 'uploads/shopping.webp', 'electronics', '0', '2024-11-27 09:13:43'),
(9, 'sfsd', 12.00, 'asds', 'uploads/shopping.webp', 'clothing', '0', '2024-11-27 09:19:11'),
(10, 'sfsd', 3.00, 'jnj', 'uploads/IMG20241126095244.jpg', 'clothing', '0', '2024-11-27 09:47:25');

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` (`id`, `username`, `password`, `email`, `created_at`) VALUES
(1, 'a@gmail.com', '$2y$10$wkhPqoRslJTnc9eRNsBTn.cRQkvxBWPtKeEaidJSWXbyG429ayZbO', 'a@gmail.com', '2024-11-27 14:16:06');

ALTER TABLE `bids`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

ALTER TABLE `bids`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `bids`
  ADD CONSTRAINT `bids_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);
COMMIT;
