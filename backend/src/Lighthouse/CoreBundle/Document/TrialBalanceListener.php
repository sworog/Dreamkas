<?php

namespace Lighthouse\CoreBundle\Document;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Event\OnFlushEventArgs;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\InvoiceProduct;

/**
 * Class TrialBalanceListener
 *
 * @DI\DoctrineMongoDBListener(events={"onFlush"})
 */
class TrialBalanceListener
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.trial_balance")
     * @var TrialBalanceRepository
     */
    public $trialBalanceRepository;

    /**
     * @param OnFlushEventArgs $eventArgs
     */
    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        /* @var DocumentManager $dm */
        $dm = $eventArgs->getDocumentManager();
        $uow = $dm->getUnitOfWork();
        foreach ($uow->getScheduledDocumentInsertions() as $document) {
            if ($document instanceof InvoiceProduct) {
                $trialBalance = new TrialBalance();

                $trialBalance->price = $document->price;
                $trialBalance->quantity = $document->quantity;
                $trialBalance->product = $document->product;
                $trialBalance->reason = $document;

                $dm->persist($trialBalance);

                $metadata = $dm->getClassMetadata(get_class($trialBalance));
                $uow->computeChangeSet($metadata, $trialBalance);
            }
        }

        foreach ($uow->getScheduledDocumentUpdates() as $document) {
            if ($document instanceof InvoiceProduct) {
                $trialBalance = $this->trialBalanceRepository->findOneByReason($document);

                $trialBalance->price = $document->price;
                $trialBalance->quantity = $document->quantity;
                $trialBalance->product = $document->product;

                $dm->persist($trialBalance);

                $metadata = $dm->getClassMetadata(get_class($trialBalance));
                $uow->computeChangeSet($metadata, $trialBalance);
            }
        }

        foreach ($uow->getScheduledDocumentDeletions() as $document) {
            if ($document instanceof InvoiceProduct) {
                $trialBalance = $this->trialBalanceRepository->findOneByReason($document);
                $dm->remove($trialBalance);

                $metadata = $dm->getClassMetadata(get_class($trialBalance));
                $uow->computeChangeSet($metadata, $trialBalance);
            }
        }
    }
}