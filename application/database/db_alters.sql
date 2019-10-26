-- ALTERS

ALTER TABLE `temp_booking_list` 
CHANGE COLUMN `temp_payment_option` `temp_payment_option` ENUM('BANK_TRANSFER', 'BANK_DEPOSIT') NOT NULL;

