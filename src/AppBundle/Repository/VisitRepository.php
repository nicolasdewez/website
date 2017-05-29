<?php

namespace AppBundle\Repository;

use AppBundle\Form\VisitSearchType;
use AppBundle\Model\VisitSearch;
use Doctrine\ORM\EntityRepository;

/**
 * Class VisitRepository.
 */
class VisitRepository extends EntityRepository
{
    /**
     * @param \DateTime   $start
     * @param \DateTime   $end
     * @param string|null $path
     * @param string|null $groupBy
     *
     * @return array
     */
    public function findBySearch(\DateTime $start, \DateTime $end, string $path = null, string $groupBy = null): array
    {
        $queryBuilder = $this->createQueryBuilder('v');

        $select = 'v.date, v.path, v.count nb';
        if (null !== $groupBy && '' !== $groupBy) {
            $select = 'v.date, v.path, SUM(v.count) nb';
        }

        $queryBuilder
            ->select($select)
            ->where('v.date >= :start')
            ->andWhere('v.date <= :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->orderBy('v.date')
            ->addOrderBy('v.path')
        ;

        if (null !== $path && '' !== $path) {
            $queryBuilder
                ->andWhere('v.path = :path')
                ->setParameter('path', $path)
            ;
        }

        if (null !== $groupBy && '' !== $groupBy) {
            $queryBuilder
                ->groupBy(sprintf('v.%s', $groupBy))
                ->orderBy(sprintf('v.%s', $groupBy))
            ;
        }

        return $queryBuilder->getQuery()->execute();
    }
}
