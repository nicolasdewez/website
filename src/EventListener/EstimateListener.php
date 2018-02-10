<?php

namespace App\EventListener;

use App\Entity\Estimate;
use App\Entity\EstimateLine;
use App\Generator\EstimateCodeGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\UnitOfWork;

class EstimateListener
{
    /** @var EstimateCodeGenerator */
    private $generator;

    public function __construct(EstimateCodeGenerator $generator)
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

            if ($this->isEstimateLine($entity)) {
                /* @var EstimateLine $entity */
                $entity->calculateTotalPrice();
                $entity->getEstimate()->calculateTotalPrice();
                $this->recompute($uow, $em, EstimateLine::class, $entity);
                $this->recompute($uow, $em, Estimate::class, $entity->getEstimate());
                continue;
            }

            /* @var Estimate $entity */
            $entity->setCode($this->generator->execute());
            $entity->calculateTotalPrice();
            $this->recompute($uow, $em, Estimate::class, $entity);
        }

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            if (!$this->supports($entity)) {
                continue;
            }

            if ($this->isEstimateLine($entity)) {
                /* @var EstimateLine $entity */
                $entity->calculateTotalPrice();
                $entity->getEstimate()->calculateTotalPrice();
                $this->recompute($uow, $em, EstimateLine::class, $entity);
                $this->recompute($uow, $em, Estimate::class, $entity->getEstimate());

                continue;
            }

            /* @var Estimate $entity */
            $entity->calculateTotalPrice();
            $this->recompute($uow, $em, Estimate::class, $entity);
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
        return $this->isEstimate($entity) || $this->isEstimateLine($entity);
    }

    /**
     * @param object $entity
     *
     * @return bool
     */
    private function isEstimate($entity): bool
    {
        return $entity instanceof Estimate;
    }

    /**
     * @param object $entity
     *
     * @return bool
     */
    private function isEstimateLine($entity): bool
    {
        return $entity instanceof EstimateLine;
    }
}
