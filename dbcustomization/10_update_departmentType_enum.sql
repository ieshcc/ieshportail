-- Migration to update the ENUM values for the 'type' column in the 'gibbondepartment' table.
-- Date: 2024-12-12
-- Author: ODI HAMD

-- Update the ENUM list in the 'type' column
ALTER TABLE `gibbonDepartment`
CHANGE COLUMN `type` `type` ENUM('Learning Area', 'Administration', 'Staff') NOT NULL DEFAULT 'Learning Area';

-- Add logging or output a message for successful migration
SELECT 'Migration 2024_12_12_update_type_enum applied successfully' AS Result;
