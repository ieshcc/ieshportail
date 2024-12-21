alter TABLE `gibbonPerson`
add COLUMN `address1Complement` varchar(255) DEFAULT NULL,
add COLUMN `address1City` varchar(255) DEFAULT NULL,
add COLUMN `address1ZipCode` varchar(15) DEFAULT NULL,
add COLUMN `address2Complement` varchar(255) DEFAULT NULL,
add COLUMN `address2City` varchar(255) DEFAULT NULL,
add COLUMN `address2ZipCode` varchar(15) DEFAULT NULL;