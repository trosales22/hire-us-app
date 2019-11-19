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
