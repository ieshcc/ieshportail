-- Drop existing table if it exists to avoid errors during import
DROP TABLE IF EXISTS `iesh_courseWeighting`;

-- Create the table for course weighting
CREATE TABLE `iesh_courseWeighting` (
  `gibbonCourseClassID` int(10) UNSIGNED ZEROFILL AUTO_INCREMENT PRIMARY KEY,
  `weighting` FLOAT NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
