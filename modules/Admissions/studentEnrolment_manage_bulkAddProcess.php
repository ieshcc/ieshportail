<?php
/*
Gibbon: the flexible, open school platform
Founded by Ross Parker at ICHK Secondary. Built by Ross Parker, Sandra Kuipers and the Gibbon community (https://gibbonedu.org/about/)
Copyright © 2010, Gibbon Foundation
Gibbon™, Gibbon Education Ltd. (Hong Kong)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

use Gibbon\Domain\Timetable\CourseEnrolmentGateway;
use Gibbon\Data\Validator;
use Gibbon\Forms\CustomFieldHandler;
use Gibbon\Data\StudentIDGenerator;

include '../../gibbon.php';

$_POST = $container->get(Validator::class)->sanitize($_POST);

$gibbonSchoolYearID = $_GET['gibbonSchoolYearID'] ?? '';
$gibbonPersonIDs = $_POST['gibbonPersonIDs'] ?? '';
$search = $_GET['search'] ?? '';

if ($gibbonSchoolYearID == '') { echo 'Fatal error loading this page!';
} else {
    $URL = $session->get('absoluteURL').'/index.php?q=/modules/'.getModuleName($_POST['address'])."/studentEnrolment_manage_bulkAdd.php&gibbonSchoolYearID=$gibbonSchoolYearID&search=$search";

    if (isActionAccessible($guid, $connection2, '/modules/Admissions/studentEnrolment_manage_bulkAdd.php') == false) {
        $URL .= '&return=error0';
        header("Location: {$URL}");
        exit;
    } else {
        //Proceed!
        //Check if person specified
        if (empty($gibbonPersonIDs)) {
            $URL .= '&return=error1';
            header("Location: {$URL}");
            exit;
        } else {
            $customRequireFail = false;
            $fields = $container->get(CustomFieldHandler::class)->getFieldDataFromPOST('Student Enrolment', [], $customRequireFail);

            if ($customRequireFail) {
                $URL .= '&return=error1';
                header("Location: {$URL}");
                exit;
            }
            
            $invalidIDs = [];
            foreach ($gibbonPersonIDs as $gibbonPersonID) {
                try {
                    // check if person is full or expected
                    $data = array('gibbonPersonID' => $gibbonPersonID);
                    $sql = "SELECT gibbonPersonID FROM gibbonPerson WHERE gibbonPersonID=:gibbonPersonID AND (gibbonPerson.status='Full' OR gibbonPerson.status='Expected')";
                    $result = $connection2->prepare($sql);
                    $result->execute($data);
                } catch (PDOException $e) {
                    $URL .= '&return=error1';
                    header("Location: {$URL}");
                    exit;
                }

                if ($result->rowCount() != 1) {
                    $invalidIDs[] = $gibbonPersonID;
                }
            }

            if (!empty($invalidIDs)) {
                $URL .= '&return=error12&invalidIDs=' . implode(',', $invalidIDs);
                header("Location: {$URL}");
                exit;
            } else {
                //Check for existing enrolment
                $alreadyEnrolled = [];
                foreach ($gibbonPersonIDs as $gibbonPersonID) {
                    try {
                        $data = array('gibbonPersonID' => $gibbonPersonID, 'gibbonSchoolYearID' => $gibbonSchoolYearID);
                        $sql = 'SELECT * FROM gibbonStudentEnrolment WHERE gibbonPersonID=:gibbonPersonID AND gibbonSchoolYearID=:gibbonSchoolYearID';
                        $result = $connection2->prepare($sql);
                        $result->execute($data);
                    } catch (PDOException $e) {
                        $URL .= '&return=error2';
                        header("Location: {$URL}");
                        exit;
                    }

                    if ($result->rowCount() > 0) {
                        $alreadyEnrolled[] = $gibbonPersonID;
                    }
                }

                if (!empty($alreadyEnrolled)) {
                    $URL .= '&return=error13&alreadyEnrolled=' . implode(',', $alreadyEnrolled);
                    header("Location: {$URL}");
                    exit;
                } else {
                    $gibbonYearGroupID = $_POST['gibbonYearGroupID'] ?? '';
                    $gibbonFormGroupID = $_POST['gibbonFormGroupID'] ?? '';
                    $rollOrder = null;
                    foreach ($gibbonPersonIDs as $gibbonPersonID) {
                        //Generate studentID
                        try{
                            //Check is student does not have a studentID set
                            $sql = "SELECT studentID FROM gibbonPerson WHERE gibbonPersonID = :gibbonPersonID LIMIT 1";
                            $result = $connection2->prepare($sql);
                            $result->execute([':gibbonPersonID' => $gibbonPersonID]);
                            $row = $result->fetch();

                            if ($row && empty($row['studentID'])) {
                                $connection2->beginTransaction();

                                // Generate a new studentID
                                $studentIDGenerator = new StudentIDGenerator();
                                $studentID = $studentIDGenerator->generate($connection2);

                                // Insert the new studentID
                                $sql = "UPDATE gibbonPerson SET studentID = :studentID WHERE gibbonPersonID = :gibbonPersonID";
                                $update = $connection2->prepare($sql);
                                $update->execute([':studentID' => $studentID, ':gibbonPersonID' => $gibbonPersonID]);

                                // Commit the transaction
                                $connection2->commit();
                            }
                            
                        }catch (PDOException $e) {
                            $connection2->rollBack(); 
                            if($session->get('installType') == 'Development');
                            {
                                error_log("Error with student ID Generation");
                                error_log($e->getMessage());
                            }
                            $URL .= '&return=error13';
                            header("Location: {$URL}");
                            exit();
                        }

                        //Write to database
                        try {
                            $data = array('gibbonPersonID' => $gibbonPersonID, 'gibbonSchoolYearID' => $gibbonSchoolYearID, 'gibbonYearGroupID' => $gibbonYearGroupID, 'gibbonFormGroupID' => $gibbonFormGroupID, 'rollOrder' => $rollOrder, 'fields' => $fields);
                            $sql = 'INSERT INTO gibbonStudentEnrolment SET gibbonPersonID=:gibbonPersonID, gibbonSchoolYearID=:gibbonSchoolYearID, gibbonYearGroupID=:gibbonYearGroupID, gibbonFormGroupID=:gibbonFormGroupID,
                            rollOrder=:rollOrder,
                            fields=:fields';
                            $result = $connection2->prepare($sql);
                            $result->execute($data);
                        } catch (PDOException $e) {
                            $URL .= '&return=error2';
                            header("Location: {$URL}");
                            exit;
                        }

                        //Last insert ID
                        $AI = str_pad($connection2->lastInsertID(), 8, '0', STR_PAD_LEFT);
                        
                        // Write registration details to database
                        $enrolmentStatusID = isset($_POST['enrolmentStatusID']) && !empty($_POST['enrolmentStatusID']) ? $_POST['enrolmentStatusID'] : '1';
                        $attendanceTypeID = isset($_POST['attendanceTypeID']) && !empty($_POST['attendanceTypeID']) ? $_POST['attendanceTypeID'] : '1';
                        try{
                            $data = array('gibbonStudentEnrolmentID' => $AI, 'gibbonSchoolYearID' => $gibbonSchoolYearID, 'enrolmentStatusID' => $enrolmentStatusID, 'attendanceTypeID' => $attendanceTypeID);
                            $sql = 'INSERT INTO iesh_studentEnrolmentDetails SET gibbonStudentEnrolmentID=:gibbonStudentEnrolmentID, gibbonSchoolYearID=:gibbonSchoolYearID, enrolmentStatusID=:enrolmentStatusID, attendanceTypeID=:attendanceTypeID';
                            $result = $connection2->prepare($sql);
                            $result->execute($data);
                        }catch(PDOException $e){
                            $URL .= '&return=warning1&editID='.$AI;
                            header("Location: {$URL}");
                            exit;
                        }


                        // Handle automatic course enrolment if enabled
                        $autoEnrolStudent = $_POST['autoEnrolStudent'] ?? 'N';
                        if ($autoEnrolStudent == 'Y') {
                            $inserted = $container->get(CourseEnrolmentGateway::class)->insertAutomaticCourseEnrolments($gibbonFormGroupID, $gibbonPersonID);

                            if (!$pdo->getQuerySuccess()) {
                                $URL .= "&return=warning1";
                                header("Location: {$URL}");
                                exit;
                            }
                        }

                    }

                    $URL .= "&return=success0";
                    header("Location: {$URL}");
                    exit;
                }
            }
        }
    }
}
