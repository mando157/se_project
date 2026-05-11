
CREATE DATABASE IF NOT EXISTS parking_system;
USE parking_system;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('customer','owner','admin') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `parking_spots` (
  `spot_id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) NOT NULL,
  `spot_name` varchar(100) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `total_slots` int(11) DEFAULT 1,
  `price_per_hour` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`spot_id`),
  FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
);


CREATE TABLE IF NOT EXISTS `bookings` (
  `booking_id` int(11) NOT NULL AUTO_INCREMENT,
  `spot_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `total_cost` decimal(10,2) DEFAULT 0.00,
  `status` enum('pending','active','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`booking_id`),
  FOREIGN KEY (`spot_id`) REFERENCES `parking_spots` (`spot_id`) ON DELETE CASCADE
);


CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
);


INSERT INTO `users` (`name`, `email`, `password`, `role`) VALUES
('Ahmed Owner', 'owner@test.com', MD5('123456'), 'owner'),
('Mohamed Customer', 'customer@test.com', MD5('123456'), 'customer');

INSERT INTO `parking_spots` (`owner_id`, `spot_name`, `location`, `total_slots`, `price_per_hour`) VALUES
(1, 'Garage Downtown', 'Downtown Street', 20, 5.00),
(1, 'Parking Mall', 'City Mall', 15, 7.00);

INSERT INTO `bookings` (`spot_id`, `user_id`, `date`, `start_time`, `end_time`, `total_cost`, `status`) VALUES
(1, 2, CURDATE(), '10:00:00', '12:00:00', 10.00, 'active'),
(1, 2, CURDATE(), '14:00:00', '16:00:00', 10.00, 'completed'),
(2, 2, CURDATE(), '09:00:00', '11:00:00', 14.00, 'pending');