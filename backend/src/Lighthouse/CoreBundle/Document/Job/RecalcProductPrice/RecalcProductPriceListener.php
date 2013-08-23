<?php

namespace Lighthouse\CoreBundle\Document\Job\RecalcProductPrice;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\DoctrineMongoDBListener(events={"postPersist", "postUpdate"})
 */
class RecalcProductPriceListener
{
    public function postPersist(LifecycleEventArgs $eventArgs)
    {

    }

    public function postUpdate(LifecycleEventArgs $eventArgs)
    {

    }
}
 