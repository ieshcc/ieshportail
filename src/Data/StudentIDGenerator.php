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

namespace Gibbon\Data;
use Gibbon\Contracts\Database\Connection;

use PDOException;

/**
 * Helper class to generate a studentID. Guarantees the uniqueness of the returned studentID.
 *
 * @version v1
 * @since   v1
 */
class StudentIDGenerator
{

    protected $defaultValue = '1';

    /**
     * Class constructor with database dependancy injection.
     * @version  v1
     * @since    v1
     */
    public function __construct()
    {
        mb_internal_encoding("utf-8");
    }


    /**
     * Generates studentID based on the provided quantity, will retrieve the last studentID available and generate from there.
     * @version  v15
     * @since    v15
     * @param    string  $format
     * @return   string  Unique studentID
     */
    public function generate($pdo)
    {        
        try{
            $sql = "SELECT MAX(CAST(studentID AS UNSIGNED)) AS highestStudentID FROM gibbonPerson WHERE studentID IS NOT NULL AND studentID != ''";
            
            $results = $pdo->prepare($sql);
            $results->execute();

            $r = $results->fetch();

            $highestStudentID = $r['highestStudentID'] ?? null;
            
            return !empty($highestStudentID) ? (string)((int)$highestStudentID + 1) : $this->defaultValue;

        }catch(PDOException $e){
            throw new PDOException("Failed to generate a student ID.");
        }
    }

    /**
     * Generates studentID based on the provided quantity, will retrieve the last studentID available and generate from there.
     * @version  v1
     * @since    v1
     * @param    string  $format
     * @return   string  Unique studentID Array
     */
    public function bulkgenerate(Connection $pdo, $quantity)
    {
        
        $studentIDs = [];

        try {
            // Fetch the highest existing studentID
            $sql = "SELECT MAX(CAST(studentID AS UNSIGNED)) AS highestStudentID 
                    FROM gibbonPerson 
                    WHERE studentID IS NOT NULL AND studentID != ''";
            
            $stmt = $pdo->select($sql);
            // $stmt->execute();
            $result = $stmt->fetch();
    
            $highestStudentID = $result['highestStudentID'] ?? null;
    
            // Start generating new studentIDs
            $currentStudentID = !empty($highestStudentID) ? (int)$highestStudentID : 0;
    
            for ($i = 0; $i < $quantity; $i++) {
                $currentStudentID++;
                $studentIDs[] = (string)$currentStudentID;
            }
    
        } catch (PDOException $e) {
            error_log("Error in StudentIDGenerator::bulkgenerate: " . $e->getMessage());
            throw new PDOException("Failed to generate student IDs in bulk.");
        }

        return $studentIDs;
    }


}
