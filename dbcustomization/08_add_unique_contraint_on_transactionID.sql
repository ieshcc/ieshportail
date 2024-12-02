-- Step 1: Resolve duplicate values by appending a unique suffix to them
UPDATE gibbonPayment
SET paymentTransactionID = CONCAT('PAY-', LPAD(FLOOR(RAND() * 10000), 4, '0')) -- Append unique ID or other identifier
WHERE paymentTransactionID IN (
    SELECT paymentTransactionID
    FROM (
        SELECT paymentTransactionID
        FROM gibbonPayment
        GROUP BY paymentTransactionID
        HAVING COUNT(*) > 1
    ) AS duplicates
);

-- Step 2: Handle NULL or empty values in paymentTransactionID
UPDATE gibbonPayment
SET paymentTransactionID = CONCAT('PAY-', LPAD(FLOOR(RAND() * 10000), 4, '0')) -- Replace with unique prefix and primary key
WHERE paymentTransactionID IS NULL OR paymentTransactionID = '';

-- Step 3: Check if all rows now have unique and non-null values (optional validation)
SELECT COUNT(*) AS total_rows, 
       COUNT(DISTINCT paymentTransactionID) AS unique_rows
FROM gibbonPayment;

-- Step 4: Add the unique constraint if no duplicates or null values remain
ALTER TABLE gibbonPayment
ADD CONSTRAINT unique_paymentTransactionID UNIQUE (paymentTransactionID);
