<?php

namespace AppBundle\Service;

use AppBundle\Entity\Visit;
use AppBundle\Model\VisitSearch;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;

/**
 * Class VisitManager.
 */
class VisitManager
{
    /** @var EntityManager */
    private $manager;

    /**
     * @param EntityManager $manager
     */
    public function __construct(EntityManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param \DateTime $date
     * @param string    $path
     */
    public function addVisit(\DateTime $date, string $path)
    {
        $visit = $this->loadOrCreate($date, $path);
        $visit->addCount();

        $this->manager->flush();
    }

    /**
     * @param VisitSearch $search
     *
     * @return array
     */
    public function searchVisit(VisitSearch $search): array
    {
        return $this->manager->getRepository(Visit::class)->findBySearch(
            $search->getStart(),
            $search->getEnd(),
            $search->getPath(),
            $search->getGroup()
        );
    }

    /**
     * @param \DateTime $date
     * @param string    $path
     *
     * @return Visit
     */
    private function loadOrCreate(\DateTime $date, string $path): Visit
    {
        $visit = $this->manager->getRepository(Visit::class)->findOneBy(['date' => $date, 'path' => $path]);
        if (null === $visit) {
            $visit = new Visit();
            $visit
                ->setDate($date)
                ->setPath($path)
            ;

            $this->manager->persist($visit);
        }

        return $visit;
    }
}
