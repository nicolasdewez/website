<?php

namespace App\Repository;

use App\Workflow\BillDefinitionWorflow;
use Doctrine\ORM\EntityRepository;

class BillRepository extends EntityRepository
{
    public function getBalance(\DateTime $startDate, \DateTime $endDate): array
    {
        $results = $this->getEntityManager()
            ->getConnection()
            ->executeQuery('
                SELECT MONTH(b.acquit_date) AS "month", SUM(b.total_price) AS "total" 
                FROM bill b
                WHERE b.state = :state
                AND b.acquit_date >= :start AND b.acquit_date <= :end 
                GROUP BY month;
            ', [
               'state' => BillDefinitionWorflow::PLACE_ACQUITTED,
               'start' => $startDate->format('Y-m-d'),
               'end' => $endDate->format('Y-m-d'),
            ]
            );

        $data = [];
        foreach ($results as $result) {
            $data[] = [
                'month' => (int) $result['month'],
                'total' => (float) $result['total'],
            ];
        }

        return $data;
    }
}
