<?php

namespace Lighthouse\CoreBundle\Document\Report\GrossSales\GrossSalesByStores;

use Lighthouse\CoreBundle\Document\AbstractCollection;
use Lighthouse\CoreBundle\Document\Store\Store;

class GrossSalesByStoresCollection extends AbstractCollection
{
    /**
     * @param Store $store
     * @return StoreGrossSalesByStores
     */
    public function getByStore(Store $store)
    {
        if (!$this->containsKey($store->id)) {
            $this->set($store->id, new StoreGrossSalesByStores($store));
        }
        return $this->get($store->id);
    }

    /**
     * Workaround for serialization without keys
     * @return array
     */
    public function toArray()
    {
        return $this->getValues();
    }
}
