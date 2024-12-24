-- Migration to update the ENUM values for the 'role' column in the 'gibbondepartmentstaff' table.
-- Date: 2024-12-12
-- Author: ODI HAMD

-- Update the ENUM list in the 'role' column
ALTER TABLE `gibbonDepartmentStaff`
CHANGE COLUMN `role` `role` ENUM('Coordinator','Assistant Coordinator','Department Manager','Education Manager','Teacher (Curriculum)','Teacher','Director','Manager','Administrator','Worker','Chef','Dean','Superintendent','Secretary','IT Manager','Accountant','Accounting Assistant','Other') NOT NULL DEFAULT 'Other';

-- Add logging or output a message for successful migration
SELECT 'Migration 2024_12_12_update_role_enum applied successfully' AS Result;
