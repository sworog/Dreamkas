<?php

namespace Lighthouse\CoreBundle\Document\Classifier;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Rounding\RoundingManager;

/**
 * @DI\DoctrineMongoDBListener(events={"prePersist"}, priority = 255)
 */
class RoundingListener
{
    /**
     * @var RoundingManager
     */
    protected $roundingManager;

    /**
     * @DI\InjectParams({
     *      "roundingManager" = @DI\Inject("lighthouse.core.rounding.manager")
     * })
     * @param RoundingManager $roundingManager
     */
    public function __construct(RoundingManager $roundingManager)
    {
        $this->roundingManager = $roundingManager;
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function prePersist(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();
        if ($document instanceof AbstractNode) {
            if (null === $document->rounding) {
                $document->rounding = $this->roundingManager->findDefault();
            }
        }
    }
}
