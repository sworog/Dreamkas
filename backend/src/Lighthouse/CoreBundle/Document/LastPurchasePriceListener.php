<?php

namespace Lighthouse\CoreBundle\Document;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Event\PostFlushEventArgs;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\DoctrineMongoDBListener(events={"postPersist", "postUpdate", "postRemove", "postFlush"})
 */
class LastPurchasePriceListener
{
    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var TrialBalanceRepository
     */
    protected $trialBalanceRepository;

    /**
     * @var Product[]
     */
    protected $productsToUpdate = array();

    /**
     * @DI\InjectParams({
     *     "productRepository"=@DI\Inject("lighthouse.core.document.repository.product"),
     *     "trialBalanceRepository"=@DI\Inject("lighthouse.core.document.repository.trial_balance")
     * })
     *
     * @param ProductRepository $productRepository
     * @param TrialBalanceRepository $trialBalanceRepository
     */
    public function __construct(
        ProductRepository $productRepository,
        TrialBalanceRepository $trialBalanceRepository
    ) {
        $this->productRepository = $productRepository;
        $this->trialBalanceRepository = $trialBalanceRepository;
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function postPersist(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();

        if ($document instanceof TrialBalance) {
            $this->addProductToUpdate($document->product);
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
            if (isset($changeSet['product'])) {
                $this->addProductToUpdate($changeSet['product'][0]);
                $this->addProductToUpdate($changeSet['product'][1]);
            } else {
                $this->addProductToUpdate($document->product);
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
            $this->addProductToUpdate($document->product);
        }
    }

    /**
     * @param Product $product
     */
    public function addProductToUpdate(Product $product)
    {
        $this->productsToUpdate[spl_object_hash($product)] = $product;
    }

    /**
     * @param PostFlushEventArgs $eventArgs
     */
    public function postFlush(PostFlushEventArgs $eventArgs)
    {
        if (count($this->productsToUpdate) > 0) {
            foreach ($this->productsToUpdate as $product) {
                $lastTrialBalance = $this->trialBalanceRepository->findOneReasonInvoiceProductByProduct($product);
                $price = (null !== $lastTrialBalance) ? $lastTrialBalance->price : null;

                $this->productRepository->updateLastPurchasePrice($product, $price);
            }
        }
        $this->productsToUpdate = array();
    }
}
