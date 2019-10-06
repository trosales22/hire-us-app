-- 10/06/2019
ALTER TABLE `dbhireus`.`talents_address` 
CHANGE COLUMN `province` `province` VARCHAR(50) NOT NULL ,
CHANGE COLUMN `city_muni` `city_muni` VARCHAR(50) NOT NULL ,
CHANGE COLUMN `barangay` `barangay` VARCHAR(50) NOT NULL ,
CHANGE COLUMN `zip_code` `zip_code` VARCHAR(50) NOT NULL ;
