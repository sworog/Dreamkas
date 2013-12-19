<?php

namespace Lighthouse\CoreBundle\Document\Report\GrossSales;

use Lighthouse\CoreBundle\Document\AbstractCollection;

class DayHoursGrossSalesCollection extends AbstractCollection
{
    /**
     * equals php ksort
     * @return $this
     */
    public function keySort()
    {
        $array = $this->toArray();
        $this->clear();
        ksort($array);
        foreach ($array as $key => $value) {
            $this->set($key, $value);
        }
        return $this;
    }
}
