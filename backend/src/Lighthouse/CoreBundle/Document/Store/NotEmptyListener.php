<?php

namespace Lighthouse\CoreBundle\Document\Store;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProductRepository;
use Lighthouse\CoreBundle\Exception\NotEmptyException;
use Symfony\Component\Translation\Translator;

/**
 * @DI\Service("lighthouse.core.document.store.listener.not_empty")
 * @DI\DoctrineMongoDBListener(events={"preSoftDelete"})
 */
class NotEmptyListener
{
    /**
     * @var StoreProductRepository
     */
    protected $storeProductRepository;

    /**
     * @var Translator
     */
    protected $translator;

    /**
     * @DI\InjectParams({
     *      "storeProductRepository"    = @DI\Inject("lighthouse.core.document.repository.store_product"),
     *      "translator"                = @DI\Inject("translator")
     * })
     * @param StoreProductRepository $storeProductRepository
     * @param Translator $translator
     */
    public function __construct(
        StoreProductRepository $storeProductRepository,
        Translator $translator
    ) {
        $this->storeProductRepository = $storeProductRepository;
        $this->translator = $translator;
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function preSoftDelete(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();
        if ($document instanceof Store) {
            if (!$this->storeProductRepository->checkStoreIsEmpty($document)) {
                throw new NotEmptyException(
                    $this
                        ->translator
                        ->trans("lighthouse.messages.store.delete", array(), 'messages')
                );
            }
        }
    }
}
