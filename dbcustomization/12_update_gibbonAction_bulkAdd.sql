UPDATE `gibbonAction`
    SET `URLList` = CONCAT(`URLList`, ',studentEnrolment_manage_bulkAdd.php')
    WHERE `name` = 'Student Enrolment';