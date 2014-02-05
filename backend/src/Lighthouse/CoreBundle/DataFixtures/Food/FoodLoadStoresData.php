<?php

namespace Lighthouse\CoreBundle\DataFixtures\Food;

use Lighthouse\CoreBundle\DataFixtures\AbstractLoadStoresData;

class FoodLoadStoresData extends AbstractLoadStoresData
{
    /**
     * @return array
     */
    public function getStoresData()
    {
        return array(
            1 => array('address' => 'Авиаконструкторов 2'),
            2 => array('address' => 'Есенина 1'),
            3 => array('address' => 'Металлистов, 116 (МЕ)'),
        );
    }
}
