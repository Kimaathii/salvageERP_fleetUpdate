-- Disable foreign key checks to avoid issues during table creation
SET FOREIGN_KEY_CHECKS=0;

-- Create the `approvals` table
CREATE TABLE IF NOT EXISTS `approvals` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `approval_name` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create the `banktransfers` table
CREATE TABLE IF NOT EXISTS `banktransfers` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `senderbankaccount` VARCHAR(255) NOT NULL,
    `receiverbankaccount` VARCHAR(255) NOT NULL,
    `amount` DECIMAL(15,2) NOT NULL,
    `currency` CHAR(3) NOT NULL,
    `datebanked` DATE NOT NULL,
    `reference` VARCHAR(255),
    `description` TEXT,
    `createdby` VARCHAR(50) NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create the `drivers` table
CREATE TABLE IF NOT EXISTS `drivers` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `full_name` VARCHAR(255) NOT NULL,
    `license_number` VARCHAR(255) NOT NULL UNIQUE,
    `phone_number` VARCHAR(15),
    `email` VARCHAR(255),
    `status` ENUM('Active', 'Inactive') DEFAULT 'Active',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create the `user_approvals` table
CREATE TABLE IF NOT EXISTS `user_approvals` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL,
    `approval_level` INT NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `username_approval_level` (`username`, `approval_level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create the `vehicles` table
CREATE TABLE IF NOT EXISTS `vehicles` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `year` INT NOT NULL,
    `make` VARCHAR(255) NOT NULL,
    `model` VARCHAR(255) NOT NULL,
    `color` VARCHAR(50),
    `license_number` VARCHAR(255) UNIQUE NOT NULL,
    `fuel_consumption` DECIMAL(10,3),
    `tank_capacity` DECIMAL(10,2),
    `vehicle_class` VARCHAR(50),
    `status` ENUM('Available', 'Unavailable', 'Out of Fleet') NOT NULL DEFAULT 'Available',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create the `order_approvals` table
CREATE TABLE IF NOT EXISTS `order_approvals` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `orderno` INT NOT NULL,
    `approval_level` INT NOT NULL,
    `approved_by` VARCHAR(50) NOT NULL,
    `status` ENUM('Approved', 'Rejected') NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`orderno`) REFERENCES `salesorders`(`orderno`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Modify the `salesorders` table
ALTER TABLE `salesorders`
    ADD COLUMN `current_approval_level` INT DEFAULT 0 AFTER `freightcost`,
    ADD COLUMN `approval_status` ENUM('Pending', 'Level 1 Approved', 'Level 2 Approved', 'Rejected') DEFAULT 'Pending' AFTER `current_approval_level`,
    ADD COLUMN `salesman_id` INT DEFAULT NULL AFTER `approval_status`,
    ADD COLUMN `vehicle_id` INT DEFAULT NULL AFTER `salesman_id`,
    ADD COLUMN `driver_id` INT DEFAULT NULL AFTER `vehicle_id`,
    ADD FOREIGN KEY (`salesman_id`) REFERENCES `salesman`(`salesmancode`) ON DELETE SET NULL,
    ADD FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles`(`id`) ON DELETE SET NULL,
    ADD FOREIGN KEY (`driver_id`) REFERENCES `drivers`(`id`) ON DELETE SET NULL;

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS=1;