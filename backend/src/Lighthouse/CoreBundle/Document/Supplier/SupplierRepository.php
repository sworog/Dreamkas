<?php

namespace Lighthouse\CoreBundle\Document\Supplier;

use Lighthouse\CoreBundle\Document\DocumentRepository;

class SupplierRepository extends DocumentRepository
{
    public function getNullObject($id)
    {
        return new NullSupplier($id);
    }
}
