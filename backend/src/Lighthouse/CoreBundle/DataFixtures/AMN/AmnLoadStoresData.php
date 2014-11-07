<?php

namespace Lighthouse\CoreBundle\DataFixtures\AMN;

use Lighthouse\CoreBundle\DataFixtures\AbstractLoadStoresData;
use Lighthouse\CoreBundle\DataFixtures\MongoDB\LoadApiClientData;

class AmnLoadStoresData extends AbstractLoadStoresData
{
    /**
     * @return array
     */
    public function getStoresData()
    {
        return array(
            1 => array('address' => 'Магазин Галерея'),
            2 => array('address' => 'СитиМолл'),
            3 => array('address' => 'ТК Невский 104'),
            4 => array('address' => 'ТК НОРД 1-44'),
            5 => array('address' => 'ТК Пик'),
        );
    }

    /**
     * @return array
     */
    public function getDependencies()
    {
        return array(
            LoadApiClientData::getClassName(),
        );
    }
}
