<?php

namespace Lighthouse\CoreBundle\DataFixtures\Food;

use Lighthouse\CoreBundle\DataFixtures\AbstractLoadStoresData;
use Lighthouse\CoreBundle\DataFixtures\MongoDB\LoadSuppliersData;

class FoodLoadStoresData extends AbstractLoadStoresData
{
    /**
     * @return array
     */
    public function getStoresData()
    {
        return array(
            'Авиаконструкторов 2' => array('address' => 'Авиаконструкторов 2'),
            'Есенина 1' => array('address' => 'Есенина 1'),
            'Металлистов, 116 (МЕ)' => array('address' => 'Металлистов, 116 (МЕ)'),
        );
    }

    /**
     * @return array
     */
    public function getDependencies()
    {
        return array_merge(
            array(
                LoadSuppliersData::getClassName()
            ),
            parent::getDependencies()
        );
    }
}
