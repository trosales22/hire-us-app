-- ALTERS

-- 10/30/2019 (DONE)
ALTER TABLE `temp_booking_list` 
CHANGE COLUMN `temp_payment_option` `temp_payment_option` ENUM('BANK_TRANSFER', 'BANK_DEPOSIT') NOT NULL;

ALTER TABLE `client_booking_list` 
ADD COLUMN `preferred_venue` LONGTEXT NOT NULL AFTER `preferred_time`;

ALTER TABLE `temp_booking_list` 
ADD COLUMN `temp_booking_venue` LONGTEXT NOT NULL AFTER `temp_booking_time`;

-- 11/18/2019 (DONE)
ALTER TABLE `client_reviews` 
CHANGE COLUMN `review_rating` `review_rating` VARCHAR(10) NOT NULL;

-- 11/24/2019 (DONE)
ALTER TABLE `talents` 
CHANGE COLUMN `hourly_rate` `hourly_rate` VARCHAR(255) NULL;

ALTER TABLE `talents` 
ADD COLUMN `screen_name` VARCHAR(100) NOT NULL AFTER `lastname`;

-- 01/13/2020 (DONE)
DROP TABLE `temp_booking_list`;
DROP TABLE `talents_unavailable_dates`;

ALTER TABLE `client_booking_list` 
ADD COLUMN `booking_event_title` VARCHAR(100) NOT NULL AFTER `talent_id`,
ADD COLUMN `booking_remarks` LONGTEXT NULL AFTER `booking_time`,
ADD COLUMN `booking_offer_status` ENUM('APPROVED', 'DECLINED', 'WAITING') NOT NULL DEFAULT 'WAITING' AFTER `booking_remarks`,
ADD COLUMN `booking_decline_reason` ENUM('TALENT_FEE', 'DATE_UNAVAILABILITY', 'LOCATION') NULL AFTER `booking_created_date`,
ADD COLUMN `booking_approved_or_declined_date` DATETIME NULL AFTER `booking_decline_reason`,
CHANGE COLUMN `total_amount` `booking_talent_fee` VARCHAR(100) NOT NULL DEFAULT '0' AFTER `booking_event_title`,
CHANGE COLUMN `preferred_venue` `booking_venue_location` LONGTEXT NOT NULL AFTER `booking_talent_fee`,
CHANGE COLUMN `payment_option` `booking_payment_option` VARCHAR(50) NULL AFTER `booking_venue_location`,
CHANGE COLUMN `preferred_date` `booking_date` LONGTEXT NOT NULL ,
CHANGE COLUMN `preferred_time` `booking_time` LONGTEXT NOT NULL ,
CHANGE COLUMN `created_date` `booking_created_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ;

ALTER TABLE `client_booking_list` 
CHANGE COLUMN `booking_remarks` `booking_other_details` LONGTEXT NULL DEFAULT NULL ;

ALTER TABLE `client_booking_list` 
ADD COLUMN `booking_generated_id` VARCHAR(50) NOT NULL AFTER `talent_id`;

-- 01/19/2020 (DONE)
ALTER TABLE `client_booking_list` 
ADD COLUMN `booking_date_paid` DATETIME NULL DEFAULT NULL AFTER `booking_approved_or_declined_date`;

-- 01/22/2020 (DONE)
ALTER TABLE `talents` DROP COLUMN `hourly_rate`;
