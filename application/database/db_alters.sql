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

-- 01/26/2020 (DONE)
CREATE TABLE `announcements` (
  `announcement_id` INT(255) NOT NULL AUTO_INCREMENT,
  `announcement_caption` VARCHAR(100) NOT NULL,
  `announcement_details` LONGTEXT NOT NULL,
  `created_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  `created_by` MEDIUMINT(8) NOT NULL,
  `active_flag` ENUM('Y', 'N') NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`announcement_id`));

  CREATE TABLE `news_and_updates` (
  `news_id` INT(255) NOT NULL AUTO_INCREMENT,
  `news_display_pic` VARCHAR(100) NULL,
  `news_caption` VARCHAR(50) NOT NULL,
  `news_details` LONGTEXT NOT NULL,
  `news_link` VARCHAR(100) NULL,
  `created_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  `created_by` MEDIUMINT(8) NOT NULL,
  `active_flag` ENUM('Y', 'N') NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`news_id`));

-- 03/21/2020 (DONE)
ALTER TABLE `news_and_updates` ADD COLUMN `news_author` VARCHAR(100) NOT NULL AFTER `news_link`;

-- 03/28/2020 (DONE)
ALTER TABLE `talents_category` 
DROP COLUMN `tc_id`,
ADD UNIQUE INDEX `talent_id_UNIQUE` (`talent_id` ASC),
DROP PRIMARY KEY;

ALTER TABLE `talents_category` 
CHANGE COLUMN `category_id` `category_id` LONGTEXT NOT NULL;