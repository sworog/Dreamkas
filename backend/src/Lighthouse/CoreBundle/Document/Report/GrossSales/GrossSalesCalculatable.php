<?php

namespace Lighthouse\CoreBundle\Document\Report\GrossSales;

interface GrossSalesCalculatable
{
    /**
     * @param array $ids
     * @return array
     */
    public function calculateGrossSalesByIds(array $ids);
}
