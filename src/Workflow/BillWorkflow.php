<?php

namespace App\Workflow;

use App\Entity\Bill;
use App\Workflow\BillDefinitionWorflow as Definition;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Workflow\Registry;

class BillWorkflow
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

    public function applyEdit(Bill $bill): void
    {
        $workflow = $this->registry->get($bill);
        $workflow->apply($bill, Definition::TRANS_EDIT);
        $this->manager->flush();
    }

    public function applyAcquit(Bill $bill): void
    {
        $workflow = $this->registry->get($bill);
        $workflow->apply($bill, Definition::TRANS_ACQUIT);
        $bill->acquit();

        $this->manager->flush();
    }

    public function applyCancel(Bill $bill): void
    {
        $workflow = $this->registry->get($bill);
        $workflow->apply($bill, Definition::TRANS_CANCEL);
        $this->manager->flush();
    }
}
