<?php

namespace Lighthouse\ReportsBundle\Reports\GrossSales\GrossSalesByStores;

use Lighthouse\ReportsBundle\Reports\GrossSales\GrossSales\GrossSales;
use Lighthouse\CoreBundle\Document\Store\Store;
use JMS\Serializer\Annotation as Serializer;

class StoreGrossSalesByStores extends GrossSales
{
    /**
     * @Serializer\MaxDepth(2)
     * @var Store
     */
    protected $store;
    /**
     * @param Store $store
     */
    public function __construct(Store $store)
    {
        $this->store = $store;
    }
}
