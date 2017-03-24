<?php
namespace Service;

class Stats
{
    public function __construct()
    {
        $this->_em = \Dao\EntityManager::getInstance()->getManager();
    }

    public function getMonthlyDynamics()
    {
        $currMonth = date('m');
        $prevMonth = date('m', strtotime(date('Y-m')." -1 month"));
        $yearOfPrevMonth = date('Y', strtotime(date('Y-m')." -1 month"));

        $sql = '
            SELECT g.name as garage, l.uniqueID,
                ( SELECT COUNT(id)
                  FROM `booking` b1
                  WHERE b1.garageID = g.id
                    AND b1.lineID = l.uniqueID
                    AND b1.startDatetime BETWEEN \'' . $yearOfPrevMonth . '-' . $prevMonth . '-01 00:00:00\' AND \'' . $yearOfPrevMonth . '-' . $prevMonth . '-31 23:59:59\'
                    AND b1.status != "removed"
                ) as prevMonth,
                ( SELECT COUNT(id)
                  FROM `booking` b2
                  WHERE b2.garageID = g.id
                    AND b2.lineID = l.uniqueID
                    AND b2.startDatetime BETWEEN \'' . date('Y') . '-' . $currMonth . '-01 00:00:00\' AND \'' . date('Y') . '-' . $currMonth . '-31 23:59:59\'
                    AND b2.status != "removed"
                ) as currMonth
            FROM `line` l
            LEFT JOIN `garage` g ON (l.garageID = g.id)
            LEFT JOIN `booking` b ON (l.id = b.lineID)
            
            WHERE l.status = "created"
                AND g.status = "created"
            
            GROUP BY g.name, l.uniqueID        
        ';

        $results = $this->_em->getConnection()->fetchAll($sql);
        return $results;
    }

    public function getWeeklyStatistics($filter = array()){
        $sql = '
            SELECT
                g.name as garage,
                COUNT(case WHEN DATE_FORMAT(b.startDatetime, \'%W\') = \'Monday\' then 1 end) \'mon\',
                COUNT(case WHEN DATE_FORMAT(b.startDatetime, \'%W\') = \'Tuesday\' then 1 end) \'tue\',
                COUNT(case WHEN DATE_FORMAT(b.startDatetime, \'%W\') = \'Wednesday\' then 1 end) \'wed\',
                COUNT(case WHEN DATE_FORMAT(b.startDatetime, \'%W\') = \'Thursday\' then 1 end) \'thu\',
                COUNT(case WHEN DATE_FORMAT(b.startDatetime, \'%W\') = \'Friday\' then 1 end) \'fri\',
                COUNT(case WHEN DATE_FORMAT(b.startDatetime, \'%W\') = \'Saturday\' then 1 end) \'sat\',
                COUNT(case WHEN DATE_FORMAT(b.startDatetime, \'%W\') = \'Sunday\' then 1 end) \'sun\'
            
            FROM `booking` b
            LEFT JOIN `garage` g ON (g.id = b.garageID)
            LEFT JOIN `line` l ON (l.id = b.lineID)
            
            WHERE b.status = \'booked\'
        ';

        if ($filter['bookingType']){
            $bookingType = $filter['bookingType'];

            if ($bookingType == 'carsOnly'){
                $sql .= " AND b.vehicleType = 'car'";
            } elseif ($bookingType == 'vansTrucksOnly'){
                $sql .= " AND b.vehicleType IN ('van', 'truck')";
            } // else { // all
        }

        $startDate = !is_null($filter['startDate']) ? date("Y-m-d", strtotime($filter['startDate'])) : null;
        $endDate = !is_null($filter['endDate']) ? date("Y-m-d", strtotime($filter['endDate'])) : null;

        // Filter by startDatetime datetime range from "X" to "Y"
        if ($startDate || $startDate) {
            if ($startDate and !$endDate) {
                $sql .= " AND b.startDatetime >= '" . $startDate . "'";
            } else if (!$startDate and $endDate) {
                $sql .= " AND b.startDatetime <= '" . $endDate . "'";
            } else if ($startDate and $endDate) { // both (from, to) were specified
                $sql .= " AND b.startDatetime >= '" . $startDate . "' AND b.startDatetime <= '" . $endDate . "'";
            }
        }

        $sql .= ' GROUP BY g.name ';

        $results = $this->_em->getConnection()->fetchAll($sql);
        return $results;
    }
}