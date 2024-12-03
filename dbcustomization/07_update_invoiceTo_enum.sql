-- Migration to update the ENUM values for the 'invoiceTo' column in the 'gibbonfinanceinvoicee' table.
-- Date: 2024-11-25
-- Author: ODI HAMD

USE ieshportail; 

-- Update the ENUM list in the 'invoiceTo' column
ALTER TABLE `gibbonFinanceInvoicee`
CHANGE COLUMN `invoiceTo` `invoiceTo` ENUM('Student', 'Family', 'Company') NOT NULL DEFAULT 'Student';

ALTER TABLE `gibbonFinanceInvoice`
CHANGE COLUMN `invoiceTo` `invoiceTo` ENUM('Student', 'Family', 'Company') NOT NULL DEFAULT 'Student';

-- Add logging or output a message for successful migration
SELECT 'Migration 2024_08_21_update_invoiceto_enum applied successfully' AS Result;
