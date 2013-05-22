<?php

namespace Lighthouse\CoreBundle\Document\WriteOff;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Lighthouse\CoreBundle\Document\AbstractMongoDBListener;
use Lighthouse\CoreBundle\Document\WriteOff\Product\WriteOffProduct;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\DoctrineMongoDBListener(events={"postPersist", "postUpdate", "postRemove"})
 */
class WriteOffTotalsListener extends AbstractMongoDBListener
{
    /**
     * @var WriteOffRepository
     */
    protected $writeOffRepository;

    /**
     * @DI\InjectParams({
     *     "writeOffRepository"=@DI\Inject("lighthouse.core.document.repository.writeoff")
     * })
     *
     * @param WriteOffRepository $writeOffRepository
     */
    public function __construct(WriteOffRepository $writeOffRepository)
    {
        $this->writeOffRepository = $writeOffRepository;
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function postPersist(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();

        if ($document instanceof WriteOffProduct) {
            $totalPriceDiff = $this->getChangeSetIntPropertyDiff($eventArgs, 'totalPrice');
            $this->writeOffRepository->updateTotals($document->writeOff, 1, $totalPriceDiff);
        }
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function postUpdate(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();

        if ($document instanceof WriteOffProduct) {
            $totalPriceDiff = $this->getChangeSetIntPropertyDiff($eventArgs, 'totalPrice');
            $this->writeOffRepository->updateTotals($document->writeOff, 0, $totalPriceDiff);
        }
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function postRemove(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();

        if ($document instanceof WriteOffProduct) {
            $this->writeOffRepository->updateTotals($document->writeOff, -1, $document->totalPrice->getCount() * -1);
        }
    }
}
