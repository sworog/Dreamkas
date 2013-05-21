<?php

namespace Lighthouse\CoreBundle\Document\WriteOff\Product;

use Lighthouse\CoreBundle\Document\DocumentRepository;
use Lighthouse\CoreBundle\Document\WriteOff\WriteOff;

class WriteOffProductRepository extends DocumentRepository
{
    /**
     * @param WriteOff $writeOff
     * @return WriteOffProductCollection
     */
    public function findAllByWriteOff(WriteOff $writeOff)
    {
        $cursor = $this->findBy(array('writeOff' => $writeOff->id));
        return new WriteOffProductCollection($cursor);
    }
}
