<?php

namespace Lighthouse\CoreBundle\Document\Report\GrossSales\GrossSalesByStores;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Document\Report\Store\StoreGrossSalesReport;
use Lighthouse\CoreBundle\Document\Store\Store;
use JMS\Serializer\Annotation as Serializer;

class StoreGrossSalesByStores extends AbstractDocument
{
    /**
     * @Serializer\MaxDepth(2)
     * @var Store
     */
    protected $store;

    /**
     * @var StoreGrossSalesReport
     */
    protected $yesterday;

    /**
     * @var StoreGrossSalesReport
     */
    protected $twoDaysAgo;

    /**
     * @var StoreGrossSalesReport
     */
    protected $eightDaysAgo;

    /**
     * @param Store $store
     */
    public function __construct(Store $store)
    {
        $this->store = $store;
    }
}
