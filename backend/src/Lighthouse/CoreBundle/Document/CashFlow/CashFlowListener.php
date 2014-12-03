<?php

namespace Lighthouse\CoreBundle\Document\CashFlow;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Lighthouse\CoreBundle\Document\AbstractMongoDBListener;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Exception\NotEditableException;
use Symfony\Component\Translation\Translator;

/**
 * @DI\DoctrineMongoDBListener(events={"preRemove", "preUpdate"})
 */
class CashFlowListener extends AbstractMongoDBListener
{
    /**
     * @var Translator
     */
    protected $translator;

    /**
     * @DI\InjectParams({
     *      "translator" = @DI\Inject("translator"),
     * })
     * @param Translator $translator
     */
    public function __construct(
        Translator $translator
    ) {
        $this->translator = $translator;
    }

    public function preRemove(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();
        if ($document instanceof CashFlow) {
            if (null !== $document->reason) {
                throw new NotEditableException(
                    $this
                        ->translator
                        ->trans('lighthouse.messages.cash_flow.delete', array(), 'messages')
                );
            }
        }
    }

    public function preUpdate(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();
        if ($document instanceof CashFlow) {
            if (null !== $document->reason) {
                throw new NotEditableException(
                    $this
                        ->translator
                        ->trans('lighthouse.messages.cash_flow.edit', array(), 'messages')
                );
            }
        }
    }
}
