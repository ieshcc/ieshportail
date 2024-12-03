-- Migration to update the ENUM values for the 'document' column in the 'gibbonpersonaldocument' table.
-- Date: 2024-08-21
-- Author: ODI HAMD

USE ieshportail;

-- Step 1: Check for usage of removed ENUM values
SELECT `document`, COUNT(*) AS count
FROM `gibbonPersonalDocument`
WHERE `document` NOT IN ('Visa')
OR `document` NOT IN ('Primary Passport')
OR `document` NOT IN ('Additional Passport')
OR `document` NOT IN ('Residency/Visa')
OR `document` NOT IN ('Birth Certificate')
GROUP BY `document`;

-- Step 2: Update records that might be using deprecated ENUM values
-- Adjust this step based on the results from Step 1. If records are found, you may need to run an update query like this:
-- UPDATE `gibbonpersonaldocument`
-- SET `document` = 'Document'  -- Choose an appropriate default or reclassification
-- WHERE `document` NOT IN ('Passport', 'ID Card', 'Document');

-- Step 3: Update the ENUM list in the 'document' column
ALTER TABLE `gibbonPersonalDocument`
CHANGE COLUMN `document` `document` ENUM('ID Card', 'Document') NOT NULL DEFAULT 'Document';

-- Add logging or output a message for successful migration
SELECT 'Migration 2024_08_21_update_document_enum applied successfully' AS Result;
