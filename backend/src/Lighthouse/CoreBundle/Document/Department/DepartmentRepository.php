<?php

namespace Lighthouse\CoreBundle\Document\Department;

use Doctrine\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\DocumentRepository;

class DepartmentRepository extends DocumentRepository
{
    /**
     * @param string $storeId
     * @return Cursor|Department[]
     */
    public function findByStore($storeId)
    {
        return $this->findBy(array('store' => $storeId));
    }
}
