<?php

namespace Lighthouse\CoreBundle\Document\TrialBalance;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\AbstractMongoDBListener;
use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProduct;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;

/**
 * @DI\DoctrineMongoDBListener(events={"prePersist"})
 */
class IndexRangeListener extends AbstractMongoDBListener
{
    /**
     * @var TrialBalanceRepository
     */
    protected $trialBalanceRepository;

    /**
     * @var NumericFactory
     */
    protected $numericFactory;

    /**
     * @DI\InjectParams({
     *      "trialBalanceRepository" = @DI\Inject("lighthouse.core.document.repository.trial_balance"),
     *      "numericFactory" = @DI\Inject("lighthouse.core.types.numeric.factory")
     * })
     * @param TrialBalanceRepository $trialBalanceRepository
     * @param NumericFactory $numericFactory
     */
    public function __construct(TrialBalanceRepository $trialBalanceRepository, NumericFactory $numericFactory)
    {
        $this->trialBalanceRepository = $trialBalanceRepository;
        $this->numericFactory = $numericFactory;
    }

    public function prePersist(LifecycleEventArgs $event)
    {
        $document = $event->getDocument();
        if ($document instanceof TrialBalance && $document->reason instanceof InvoiceProduct) {
            $this->setIndexRange($document, $event->getDocumentManager());
        }
    }

    /**
     * @param TrialBalance $trialBalance
     * @param DocumentManager $dm
     */
    protected function setIndexRange(TrialBalance $trialBalance, DocumentManager $dm)
    {
        $previousTrialBalance = $this->trialBalanceRepository->findOnePrevious($trialBalance);
        if ($previousTrialBalance) {
            $trialBalance->startIndex = clone $previousTrialBalance->endIndex;
        } else {
            $trialBalance->startIndex = $this->numericFactory->createQuantity(0);
        }
        $trialBalance->endIndex = $trialBalance->startIndex->add($trialBalance->quantity);

        $this->computeChangeSet($dm, $trialBalance);
    }
}
