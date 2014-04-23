<?php

namespace Lighthouse\CoreBundle\Document\Invoice;

use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\AbstractMongoDBListener;
use Symfony\Component\Validator\ObjectInitializerInterface;

/**
 * @DI\Service("lighthouse.core.document.invoice.totals_listener")
 * @DI\Tag("validator.initializer")
 */
class InvoiceTotalsListener extends AbstractMongoDBListener implements ObjectInitializerInterface
{
    /**
     * @param object $object
     */
    public function initialize($object)
    {
        if ($object instanceof Invoice) {
            $object->calculateTotals();
        }
    }
}
