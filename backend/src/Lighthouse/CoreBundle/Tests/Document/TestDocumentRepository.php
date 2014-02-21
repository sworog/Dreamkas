<?php

namespace Lighthouse\CoreBundle\Tests\Document;

use Lighthouse\CoreBundle\Document\DocumentRepository;
use MongoCollection;

class TestDocumentRepository extends DocumentRepository
{
    private $collection;

    /**
     * @param MongoCollection $collection
     */
    public function __construct(MongoCollection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * @return MongoCollection
     */
    protected function getMongoCollection()
    {
        return $this->collection;
    }

    /**
     * @param array $ops
     * @return array
     */
    public function testAggregate(array $ops = array())
    {
        return $this->aggregate($ops);
    }
}
