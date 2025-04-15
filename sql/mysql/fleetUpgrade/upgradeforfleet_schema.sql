SET FOREIGN_KEY_CHECKS=0;

-- Create the `approvals` table
CREATE TABLE IF NOT EXISTS `approvals` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `locationname` VARCHAR(255) NOT NULL,
    `approval_level` INT NOT NULL,
    `loccode` VARCHAR(5) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `locationname` (`locationname`, `approval_level`),
    KEY `fk_approvals_loccode` (`loccode`),
    CONSTRAINT `fk_approvals_loccode` FOREIGN KEY (`loccode`) REFERENCES `locations` (`loccode`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Create the `banktransfers` table
CREATE TABLE IF NOT EXISTS `banktransfers` (
    `transferid` INT NOT NULL AUTO_INCREMENT,
    `senderbankaccount` VARCHAR(20) NOT NULL,
    `receiverbankaccount` VARCHAR(20) NOT NULL,
    `amount` DECIMAL(15,2) NOT NULL,
    `currency` CHAR(3) NOT NULL,
    `datebanked` DATE NOT NULL,
    `reference` VARCHAR(50) DEFAULT NULL,
    `description` TEXT,
    `createdby` VARCHAR(50) NOT NULL,
    `createdon` DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`transferid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Create the `drivers` table
CREATE TABLE IF NOT EXISTS `drivers` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `full_name` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
    `phone` VARCHAR(50) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `picture` VARCHAR(255) DEFAULT NULL,
    `location` VARCHAR(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    KEY `loccode` (`location`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Create the `user_approvals` table
CREATE TABLE IF NOT EXISTS `user_approvals` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `location` VARCHAR(255) NOT NULL,
    `department` VARCHAR(255) NOT NULL,
    `username` VARCHAR(255) NOT NULL,
    `approval_level` INT NOT NULL,
    `created_at` DATETIME NOT NULL,
    KEY `location` (`location`, `approval_level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Create the `vehicles` table
CREATE TABLE IF NOT EXISTS `vehicles` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `year` YEAR NOT NULL,
    `make` VARCHAR(50) NOT NULL,
    `model` VARCHAR(50) NOT NULL,
    `color` VARCHAR(30) DEFAULT NULL,
    `license_number` VARCHAR(20) NOT NULL,
    `fuel_consumption` DECIMAL(5,3) NOT NULL,
    `tank_capacity` DECIMAL(5,2) NOT NULL,
    `vehicle_class` ENUM('Sedan', 'SUV', 'Truck', 'Van', 'Motorcycle', 'Bus', 'Other') NOT NULL,
    `status` ENUM('Available', 'Unavailable', 'Out of Fleet') NOT NULL DEFAULT 'Available',
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `license_number` (`license_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Create the `order_approvals` table
CREATE TABLE IF NOT EXISTS `order_approvals` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `orderno` INT NOT NULL,
    `approval_level` INT NOT NULL,
    `approved_by` VARCHAR(50) NOT NULL,
    `approved_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `status` ENUM('Approved', 'Rejected') NOT NULL,
    `comments` TEXT,
    PRIMARY KEY (`id`),
    KEY `orderno` (`orderno`),
    CONSTRAINT `order_approvals_ibfk_1` FOREIGN KEY (`orderno`) REFERENCES `salesorders` (`orderno`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Modify the `salesorders` table
ALTER TABLE `salesorders`
    ADD COLUMN `current_approval_level` INT DEFAULT 1 AFTER `freightcost`,
    ADD COLUMN `approval_status` ENUM('Pending', 'Level 1 Approved', 'Level 2 Approved', 'Rejected') DEFAULT 'Pending' AFTER `current_approval_level`,
    ADD COLUMN `salesman_id` VARCHAR(50) DEFAULT NULL AFTER `approval_status`,
    ADD COLUMN `vehicle_id` INT DEFAULT NULL AFTER `salesman_id`,
    ADD COLUMN `driver_id` INT DEFAULT NULL AFTER `vehicle_id`,
    ADD FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles`(`id`) ON DELETE SET NULL,
    ADD FOREIGN KEY (`driver_id`) REFERENCES `drivers`(`id`) ON DELETE SET NULL;

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS=1;
