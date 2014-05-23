<?php

namespace Lighthouse\CoreBundle\Tests\Fixtures\Document;

use Doctrine\MongoDB\ArrayIterator;
use Lighthouse\CoreBundle\Document\DocumentRepository;

class TestDocumentRepository extends DocumentRepository
{
    /**
     * @param array $ops
     * @return ArrayIterator
     */
    public function testAggregate(array $ops)
    {
        return $this->aggregate($ops);
    }
}
