<?php

namespace Lighthouse\CoreBundle\Document\Supplier;

use Lighthouse\CoreBundle\Document\NullObjectInterface;

class NullSupplier extends Supplier implements NullObjectInterface
{
    /**
     * @param string $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getNullObjectIdentifier()
    {
        return $this->id;
    }
}
