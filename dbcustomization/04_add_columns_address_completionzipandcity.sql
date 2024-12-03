alter TABLE `gibbonperson`
add COLUMN `address1Complement` varchar(255) DEFAULT NULL,
add COLUMN `address1City` varchar(255) DEFAULT NULL,
add COLUMN `address1ZipCode` char(10) DEFAULT NULL,
add COLUMN `address2Complement` varchar(255) DEFAULT NULL,
add COLUMN `address2City` varchar(255) DEFAULT NULL,
add COLUMN `address2ZipCode` char(10) DEFAULT NULL;