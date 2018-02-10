<?php

namespace App\Workflow;

use App\Entity\Estimate;
use App\Workflow\EstimateDefinitionWorflow as Definition;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Workflow\Registry;

class EstimateWorkflow
{
    /** @var Registry */
    private $registry;

    /** @var EntityManagerInterface */
    private $manager;

    public function __construct(Registry $registry, EntityManagerInterface $manager)
    {
        $this->registry = $registry;
        $this->manager = $manager;
    }

    public function applyEdit(Estimate $estimate): void
    {
        $workflow = $this->registry->get($estimate);
        $workflow->apply($estimate, Definition::TRANS_EDIT);
        $this->manager->flush();
    }

    public function applyAccept(Estimate $estimate): void
    {
        $workflow = $this->registry->get($estimate);
        $workflow->apply($estimate, Definition::TRANS_ACCEPT);
        $this->manager->flush();
    }

    public function applyCancel(Estimate $estimate): void
    {
        $workflow = $this->registry->get($estimate);
        $workflow->apply($estimate, Definition::TRANS_CANCEL);
        $this->manager->flush();
    }
}
