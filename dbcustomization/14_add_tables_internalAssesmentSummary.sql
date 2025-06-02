
-- Drop existing table if it exists to avoid errors during import
DROP TABLE IF EXISTS `iesh_internalAssessmentSummary`;

-- Create the table for internal assessment summary
CREATE TABLE `iesh_internalAssessmentSummary` (
  `gibbonPersonID` int(10) UNSIGNED ZEROFILL NOT NULL,
  `gibbonCourseClassID` int(10) UNSIGNED ZEROFILL NOT NULL,
  `gibbonSchoolYearID` int(3) UNSIGNED ZEROFILL NOT NULL,
  `finalGrade` float NOT NULL,
  `catchupGrade` float NOT NULL,
  PRIMARY KEY (`gibbonPersonID`, `gibbonCourseClassID`, `gibbonSchoolYearID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
