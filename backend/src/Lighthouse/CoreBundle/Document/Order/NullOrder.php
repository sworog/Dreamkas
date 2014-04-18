<?php

namespace Lighthouse\CoreBundle\Document\Order;

use Lighthouse\CoreBundle\Document\NullObjectInterface;

class NullOrder extends Order implements NullObjectInterface
{
    /**
     * @return string
     */
    public function getNullObjectIdentifier()
    {
        return $this->id;
    }
}
