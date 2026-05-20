CREATE DATABASE IF NOT EXISTS `covoiturage` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `covoiturage`;

CREATE TABLE IF NOT EXISTS `Users` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(50) NOT NULL,
    `role` VARCHAR(20) DEFAULT 'user',
    `profile_pic` VARCHAR(255) DEFAULT NULL,
    `reset_token_hash` VARCHAR(64) DEFAULT NULL,
    `reset_token_expires_at` DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `Trips` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `departure` VARCHAR(255) NOT NULL,
    `destination` VARCHAR(255) NOT NULL,
    `date_trip` DATETIME NOT NULL,
    `seats` INT NOT NULL,
    `price` DECIMAL(10,2) NOT NULL,
    `car_brand` VARCHAR(255) NOT NULL,
    `offers` VARCHAR(255) NOT NULL,
    FOREIGN KEY (`user_id`) REFERENCES `Users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `Bookings` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `trip_id` INT UNSIGNED NOT NULL,
    `status` VARCHAR(20) DEFAULT 'active',
    FOREIGN KEY (`user_id`) REFERENCES `Users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`trip_id`) REFERENCES `Trips`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
