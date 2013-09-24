<?php

namespace Lighthouse\CoreBundle\Document\Store;

interface Storeable
{
    /**
     * @return Store
     */
    public function getStore();
}
