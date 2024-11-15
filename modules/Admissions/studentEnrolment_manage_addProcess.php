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

include '../../gibbon.php';

$_POST = $container->get(Validator::class)->sanitize($_POST);

$gibbonSchoolYearID = $_GET['gibbonSchoolYearID'] ?? '';
$gibbonPersonID = $_POST['gibbonPersonID'] ?? '';
$search = $_GET['search'] ?? '';

if ($gibbonSchoolYearID == '') { echo 'Fatal error loading this page!';
} else {
    $URL = $session->get('absoluteURL').'/index.php?q=/modules/'.getModuleName($_POST['address'])."/studentEnrolment_manage_add.php&gibbonSchoolYearID=$gibbonSchoolYearID&search=$search";

    if (isActionAccessible($guid, $connection2, '/modules/Admissions/studentEnrolment_manage_add.php') == false) {
        $URL .= '&return=error0';
        header("Location: {$URL}");
        exit;
    } else {
        //Proceed!
        //Check if person specified
        if ($gibbonPersonID == '') {
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
            
            try {
                $data = array('gibbonPersonID' => $gibbonPersonID);
                $sql = "SELECT gibbonPersonID FROM gibbonPerson WHERE gibbonPersonID=:gibbonPersonID AND (gibbonPerson.status='Full' OR gibbonPerson.status='Expected')";
                $result = $connection2->prepare($sql);
                $result->execute($data);
            } catch (PDOException $e) {
                $URL .= '&return=error2';
                header("Location: {$URL}");
                exit;
            }

            if ($result->rowCount() != 1) {
                $URL .= '&return=error2';
                header("Location: {$URL}");
                exit;
            } else {
                //Check for existing enrolment
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
                    $URL .= '&return=error3';
                    header("Location: {$URL}");
                    exit;
                } else {
                    $gibbonYearGroupID = $_POST['gibbonYearGroupID'] ?? '';
                    $gibbonFormGroupID = $_POST['gibbonFormGroupID'] ?? '';
                    $rollOrder = $_POST['rollOrder'] ?? '';
                    if ($rollOrder == '') {
                        $rollOrder = null;
                    }

                    //Check unique inputs for uniquness
                    try {
                        $data = array('rollOrder' => $rollOrder, 'gibbonFormGroupID' => $gibbonFormGroupID);
                        $sql = "SELECT * FROM gibbonStudentEnrolment WHERE rollOrder=:rollOrder AND gibbonFormGroupID=:gibbonFormGroupID AND NOT rollOrder=''";
                        $result = $connection2->prepare($sql);
                        $result->execute($data);
                    } catch (PDOException $e) {
                        $URL .= '&return=error2';
                        header("Location: {$URL}");
                        exit;
                    }

                    if ($result->rowCount() > 0) {
                        $URL .= '&return=error3';
                        header("Location: {$URL}");
                        exit;
                    } else {
                        //Generate studentID
                        try{
                            $sql = 'SELECT MAX(CAST(studentID AS UNSIGNED)) AS highestStudentID FROM gibbonPerson;';
                            $result = $connection2->prepare($sql);
                            $result->execute();
                            $row = $result->fetch();

                            $studentID = '1';

                            if ($result->rowCount() > 0) {
                                $highestStudentID = $row['highestStudentID'];
                                $studentID = !empty($highestStudentID) ? (string) ((int) $highestStudentID + 1) : '1';
                            }     
                            
                        }catch (PDOException $e) {
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
                            $sql = 'INSERT INTO gibbonStudentEnrolment SET gibbonPersonID=:gibbonPersonID, gibbonSchoolYearID=:gibbonSchoolYearID, gibbonYearGroupID=:gibbonYearGroupID, gibbonFormGroupID=:gibbonFormGroupID, rollOrder=:rollOrder, fields=:fields';
                            $result = $connection2->prepare($sql);
                            $result->execute($data);
                        } catch (PDOException $e) {
                            $URL .= '&return=error2';
                            header("Location: {$URL}");
                            exit;
                        }

                        //Last insert ID
                        $AI = str_pad($connection2->lastInsertID(), 8, '0', STR_PAD_LEFT);
                        
                        // Write resgitration details to database
                        $registrationStatusID = isset($_POST['registrationStatusID']) && !empty($_POST['registrationStatusID']) ? $_POST['registrationStatusID'] : '1';
                        $attendanceTypeID = isset($_POST['attendanceTypeID']) && !empty($_POST['attendanceTypeID']) ? $_POST['attendanceTypeID'] : '1';
                        $dormitoryRoomID = isset($_POST['dormitoryRoomID']) && !empty($_POST['dormitoryRoomID']) ? $_POST['dormitoryRoomID'] : null;
                        $comments = isset($_POST['comments']) && !empty($_POST['comments']) ? $_POST['comments'] : 'No comment';
                        try{
                            $data = array('gibbonStudentEnrolmentID' => $AI, 'gibbonSchoolYearID' => $gibbonSchoolYearID, 'registrationStatusID' => $registrationStatusID, 'attendanceTypeID' => $attendanceTypeID, 'gibbonSpaceID'  => $dormitoryRoomID, 'comments' => $comments);
                            $sql = 'INSERT INTO iesh_studentregistrationdetails SET gibbonStudentEnrolmentID=:gibbonStudentEnrolmentID, gibbonSchoolYearID=:gibbonSchoolYearID, registrationStatusID=:registrationStatusID, attendanceTypeID=:attendanceTypeID, gibbonSpaceID=:gibbonSpaceID, comments=:comments';
                            $result = $connection2->prepare($sql);
                            $result->execute($data);
                        }catch(PDOException $e){
                            error_log($e->getMessage());
                            error_log($_POST['dormitoryRoomID']);
                            $URL .= '&return=warning1&editID='.$AI;
                            header("Location: {$URL}");
                            exit;
                        }


                        // Handle automatic course enrolment if enabled
                        $autoEnrolStudent = $_POST['autoEnrolStudent'] ?? 'N';
                        if ($autoEnrolStudent == 'Y') {
                            $inserted = $container->get(CourseEnrolmentGateway::class)->insertAutomaticCourseEnrolments($gibbonFormGroupID, $gibbonPersonID);

                            if (!$pdo->getQuerySuccess()) {
                                $URL .= "&return=warning1&editID=$AI";
                                header("Location: {$URL}");
                                exit;
                            }
                        }

                        $URL .= "&return=success0&editID=$AI";
                        header("Location: {$URL}");
                        exit;
                    }
                }
            }
        }
    }
}
