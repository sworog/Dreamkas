<?php

namespace Lighthouse\CoreBundle\Document\Supplier;

use Lighthouse\CoreBundle\Document\DocumentRepository;

class SupplierRepository extends DocumentRepository
{
    /**
     * @param string $id
     * @return NullSupplier
     */
    public function getNullObject($id)
    {
        $nullSupplier = new NullSupplier();
        $nullSupplier->id = $id;
        return $nullSupplier;
    }
}
