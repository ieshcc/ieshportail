-- Drop existing tables if they exist to avoid errors during import
DROP TABLE IF EXISTS `iesh_studentEnrolmentDetails`;
DROP TABLE IF EXISTS `iesh_dormitoryRooms`;
DROP TABLE IF EXISTS `iesh_enrolmentStatus`;
DROP TABLE IF EXISTS `iesh_attendanceTypes`;

-- Create lookup table for Enrolment Types
CREATE TABLE `iesh_attendanceTypes` (
    `attendanceTypeID` int(10) UNSIGNED ZEROFILL AUTO_INCREMENT PRIMARY KEY,
    `attendanceTypeName` CHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert initial Enrolment types
INSERT INTO `iesh_attendanceTypes` (`attendanceTypeName`) VALUES
('Non specified'),
('Online Student'),
('On-site External Student'),          -- For 'Externe'
('On-site Internal Student'),          -- For 'Interne'
('On-site Day Boarder'),               -- For 'Demi-Pensionnaire Journée'
('On-site Midday Boarder'),            -- For 'Demi-Pensionnaire Midi'
('On-site Evening Boarder');           -- For 'Demi-Pensionnaire Soir'

-- Create lookup table for Enrolment Status
CREATE TABLE `iesh_enrolmentStatus` (
    `enrolmentStatusID` int(10) UNSIGNED ZEROFILL AUTO_INCREMENT PRIMARY KEY,
    `enrolmentStatusName` CHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert initial Enrolment Status
INSERT INTO `iesh_enrolmentStatus` (`enrolmentStatusName`) VALUES
('Non applicable/specified'),
('Validated'),
('On Hold'),
('Refused'),
('Cancelled');

-- Create table for Student Enrolment Details
CREATE TABLE `iesh_studentEnrolmentDetails` (
    `studentEnrolmentDetailsID` int(10) UNSIGNED ZEROFILL AUTO_INCREMENT PRIMARY KEY,
    `gibbonStudentEnrolmentID` int(8) UNSIGNED ZEROFILL NOT NULL,
    `gibbonSchoolYearID` int(3) UNSIGNED ZEROFILL NOT NULL,
    `attendanceTypeID` int(10) UNSIGNED ZEROFILL NOT NULL DEFAULT 1,  -- Default to 'Non specified'
    `enrolmentStatusID` int(10) UNSIGNED ZEROFILL NOT NULL DEFAULT 1, -- Default to 'Non specified'
    `gibbonSpaceID` int(10) UNSIGNED ZEROFILL DEFAULT NULL,  -- Nullable, as not all students may have a room
    `comments` TEXT DEFAULT NULL,  -- Nullable, as not all students may have a room
    CONSTRAINT `fk_iesh_studentEnrolmentDetails_gibbonStudentEnrolment` FOREIGN KEY (`gibbonStudentEnrolmentID`) REFERENCES `gibbonStudentEnrolment` (`gibbonStudentEnrolmentID`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_iesh_studentEnrolmentDetails_iesh_attendanceTypes` FOREIGN KEY (`attendanceTypeID`) REFERENCES `iesh_attendanceTypes` (`attendanceTypeID`) ON UPDATE CASCADE,
    CONSTRAINT `fk_iesh_studentEnrolmentDetails_iesh_enrolmentStatus` FOREIGN KEY (`enrolmentStatusID`) REFERENCES `iesh_enrolmentStatus` (`enrolmentStatusID`) ON UPDATE CASCADE,
    CONSTRAINT `fk_iesh_studentEnrolmentDetails_gibbonSpace` FOREIGN KEY (`gibbonSpaceID`) REFERENCES `gibbonSpace` (`gibbonSpaceID`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

