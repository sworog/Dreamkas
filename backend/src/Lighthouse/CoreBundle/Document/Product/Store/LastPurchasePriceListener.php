<?php

namespace Lighthouse\CoreBundle\Document\Product\Store;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Event\PostFlushEventArgs;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\TrialBalance\TrialBalance;
use Lighthouse\CoreBundle\Document\TrialBalance\TrialBalanceRepository;

/**
 * @DI\DoctrineMongoDBListener(events={"postPersist", "postUpdate", "postRemove", "postFlush"})
 */
class LastPurchasePriceListener
{
    /**
     * @var StoreProductRepository
     */
    protected $storeProductRepository;

    /**
     * @var TrialBalanceRepository
     */
    protected $trialBalanceRepository;

    /**
     * @var StoreProduct[]
     */
    protected $storeProductsToUpdate = array();

    /**
     * @DI\InjectParams({
     *     "storeProductRepository"=@DI\Inject("lighthouse.core.document.repository.store_product"),
     *     "trialBalanceRepository"=@DI\Inject("lighthouse.core.document.repository.trial_balance")
     * })
     *
     * @param StoreProductRepository $storeProductRepository
     * @param TrialBalanceRepository $trialBalanceRepository
     */
    public function __construct(
        StoreProductRepository $storeProductRepository,
        TrialBalanceRepository $trialBalanceRepository
    ) {
        $this->storeProductRepository = $storeProductRepository;
        $this->trialBalanceRepository = $trialBalanceRepository;
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function postPersist(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();

        if ($document instanceof TrialBalance) {
            $this->addProductToUpdate($document->storeProduct);
        }
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function postUpdate(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();

        if ($document instanceof TrialBalance) {
            /* @var DocumentManager $dm */
            $dm = $eventArgs->getDocumentManager();
            $changeSet = $dm->getUnitOfWork()->getDocumentChangeSet($document);
            if (isset($changeSet['storeProduct'])) {
                $this->addProductToUpdate($changeSet['storeProduct'][0]);
                $this->addProductToUpdate($changeSet['storeProduct'][1]);
            } else {
                $this->addProductToUpdate($document->storeProduct);
            }
        }
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function postRemove(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();

        if ($document instanceof TrialBalance) {
            $this->addProductToUpdate($document->storeProduct);
        }
    }

    /**
     * @param StoreProduct $storeProduct
     */
    public function addProductToUpdate(StoreProduct $storeProduct)
    {
        $this->storeProductsToUpdate[spl_object_hash($storeProduct)] = $storeProduct;
    }

    /**
     * @param PostFlushEventArgs $eventArgs
     */
    public function postFlush(PostFlushEventArgs $eventArgs)
    {
        if (count($this->storeProductsToUpdate) > 0) {
            foreach ($this->storeProductsToUpdate as $storeProduct) {
                $lastTrialBalance = $this->trialBalanceRepository->findOneReasonInvoiceProductByProduct($storeProduct);
                $price = (null !== $lastTrialBalance) ? $lastTrialBalance->price : null;

                $this->storeProductRepository->updateLastPurchasePrice($storeProduct, $price);
            }
        }
        $this->storeProductsToUpdate = array();
    }
}
