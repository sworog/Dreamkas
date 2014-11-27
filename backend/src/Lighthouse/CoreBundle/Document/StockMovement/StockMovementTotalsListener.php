<?php

namespace Lighthouse\CoreBundle\Document\StockMovement;

use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Validator\ObjectInitializerInterface;

/**
 * @DI\Service("lighthouse.core.document.stock_movement.totals_listener")
 * @DI\Tag("validator.initializer")
 */
class StockMovementTotalsListener implements ObjectInitializerInterface
{
    /**
     * @param object $object
     */
    public function initialize($object)
    {
        if ($object instanceof StockMovement) {
            $object->calculateTotals();
        }
    }
}
