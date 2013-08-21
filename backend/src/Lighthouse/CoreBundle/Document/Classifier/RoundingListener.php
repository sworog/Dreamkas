<?php

namespace Lighthouse\CoreBundle\Document\Classifier;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Event\PreUpdateEventArgs;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Rounding\RoundingManager;

/**
 * @DI\DoctrineMongoDBListener(events={"prePersist", "preUpdate"}, priority = 255)
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
        $this->updateRounding($document);
    }

    /**
     * @param PreUpdateEventArgs $eventArgs
     */
    public function preUpdate(PreUpdateEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();
        if ($this->updateRounding($document)) {
            $dm = $eventArgs->getDocumentManager();
            $classMeta = $dm->getClassMetadata(get_class($document));
            $dm->getUnitOfWork()->recomputeSingleDocumentChangeSet($classMeta, $document);
        }
    }

    /**
     * @param AbstractNode|object $document
     * @return boolean
     */
    protected function updateRounding($document)
    {
        if ($document instanceof AbstractNode) {
            if (null === $document->rounding) {
                $rounding = null;
                if ($document->getParent()) {
                    $rounding = $document->getParent()->rounding;
                }
                if (null === $rounding) {
                    $rounding = $this->roundingManager->findDefault();
                }
                $document->setRounding($rounding);
            }
            return true;
        }
        return false;
    }
}
