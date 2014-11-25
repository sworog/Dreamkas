<?php

namespace Lighthouse\CoreBundle\Document\StockMovement;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\StockMovement\Invoice\Invoice;
use Lighthouse\CoreBundle\Document\StockMovement\SupplierReturn\SupplierReturn;
use Lighthouse\CoreBundle\Exception\HasDeletedException;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @DI\DoctrineMongoDBListener(events="preRemove")
 */
class DeletedListener
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @DI\InjectParams({
     *      "translator" = @DI\Inject("translator")
     * })
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function preRemove(LifecycleEventArgs $event)
    {
        $document = $event->getDocument();
        if ($document instanceof StockMovement) {
            $messages = array();
            if ($document->getStore()->getDeletedAt()) {
                $messages[] = $this->translator->trans(
                    'lighthouse.validation.errors.deleted.store.forbid.delete',
                    array(),
                    'validators'
                );
            }

            if (($document instanceof Invoice || $document instanceof SupplierReturn)
                && $document->supplier
                && $document->supplier->getDeletedAt()
            ) {
                $messages[] = $this->translator->trans(
                    'lighthouse.validation.errors.deleted.supplier.forbid.delete',
                    array(),
                    'validators'
                );
            }

            if (!empty($messages)) {
                throw new HasDeletedException(implode("\n", $messages));
            }
        }
    }
}
