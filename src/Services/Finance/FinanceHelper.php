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

namespace Gibbon\Services\Finance;

/**
 * @version v23
 * @since   v23
 */
class FinanceHelper
{
    public static function getInvoiceTotalFee($pdo, $gibbonFinanceInvoiceID, $status)
    {
        try {
            $dataTotal = array('gibbonFinanceInvoiceID' => $gibbonFinanceInvoiceID);
            if ($status == 'Pending') {
                $sqlTotal = 'SELECT gibbonFinanceInvoiceFee.fee AS fee, gibbonFinanceFee.fee AS fee2 FROM gibbonFinanceInvoiceFee LEFT JOIN gibbonFinanceFee ON (gibbonFinanceInvoiceFee.gibbonFinanceFeeID=gibbonFinanceFee.gibbonFinanceFeeID) WHERE gibbonFinanceInvoiceID=:gibbonFinanceInvoiceID';
            } else {
                $sqlTotal = 'SELECT gibbonFinanceInvoiceFee.fee AS fee, NULL AS fee2 FROM gibbonFinanceInvoiceFee WHERE gibbonFinanceInvoiceID=:gibbonFinanceInvoiceID';
            }
            $resultTotal = $pdo->executeQuery($dataTotal, $sqlTotal);
        } catch (PDOException $e) {
            return null;
        }

        $totalFee = 0;

        while ($rowTotal = $resultTotal->fetch()) {
            $totalFee += is_numeric($rowTotal['fee2'])? $rowTotal['fee2'] : $rowTotal['fee'];
        }

        return $totalFee;
    }
}