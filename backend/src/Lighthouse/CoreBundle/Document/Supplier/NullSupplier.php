<?php

namespace Lighthouse\CoreBundle\Document\Supplier;

use Lighthouse\CoreBundle\Document\NullObjectInterface;

class NullSupplier extends Supplier implements NullObjectInterface
{
    /**
     * @return string
     */
    public function getNullObjectIdentifier()
    {
        return $this->id;
    }
}
