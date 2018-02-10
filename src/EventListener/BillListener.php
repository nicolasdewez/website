<?php

namespace App\EventListener;

use App\Entity\Bill;
use App\Entity\BillLine;
use App\Generator\BillCodeGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\UnitOfWork;

class BillListener
{
    /** @var BillCodeGenerator */
    private $generator;

    public function __construct(BillCodeGenerator $generator)
    {
        $this->generator = $generator;
    }

    public function onFlush(OnFlushEventArgs $eventArgs): void
    {
        $em = $eventArgs->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if (!$this->supports($entity)) {
                continue;
            }

            if ($this->isBillLine($entity)) {
                /* @var BillLine $entity */
                $entity->calculateTotalPrice();
                $entity->getBill()->calculateTotalPrice();
                $this->recompute($uow, $em, BillLine::class, $entity);
                $this->recompute($uow, $em, Bill::class, $entity->getBill());
                continue;
            }

            /* @var Bill $entity */
            $entity->setCode($this->generator->execute());
            $entity->calculateTotalPrice();
            $this->recompute($uow, $em, Bill::class, $entity);
        }

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            if (!$this->supports($entity)) {
                continue;
            }

            if ($this->isBillLine($entity)) {
                /* @var BillLine $entity */
                $entity->calculateTotalPrice();
                $entity->getBill()->calculateTotalPrice();
                $this->recompute($uow, $em, BillLine::class, $entity);
                $this->recompute($uow, $em, Bill::class, $entity->getBill());

                continue;
            }

            /* @var Bill $entity */
            $entity->calculateTotalPrice();
            $this->recompute($uow, $em, Bill::class, $entity);
        }
    }

    private function recompute(UnitOfWork $unitOfWork, EntityManagerInterface $manager, string $class, $entity): void
    {
        $unitOfWork->recomputeSingleEntityChangeSet(
            $manager->getClassMetadata($class),
            $entity
        );
    }

    /**
     * @param object $entity
     *
     * @return bool
     */
    private function supports($entity): bool
    {
        return $this->isBill($entity) || $this->isBillLine($entity);
    }

    /**
     * @param object $entity
     *
     * @return bool
     */
    private function isBill($entity): bool
    {
        return $entity instanceof Bill;
    }

    /**
     * @param object $entity
     *
     * @return bool
     */
    private function isBillLine($entity): bool
    {
        return $entity instanceof BillLine;
    }
}
