<?php

namespace Lighthouse\CoreBundle\Document\Job\RecalcProductPrice;

use Doctrine\MongoDB\Event\EventArgs;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\DoctrineMongoDBListener(events={"postPersist", "postUpdate"})
 */
class RecalcProductPriceListener
{
    public function postPersist(EventArgs $eventArgs)
    {

    }

    public function postUpdate(EventArgs $eventArgs)
    {

    }
}
 