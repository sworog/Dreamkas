<?php

namespace Lighthouse\CoreBundle\Tests\Document;

use Doctrine\MongoDB\ArrayIterator;
use Lighthouse\CoreBundle\Document\DocumentRepository;
use MongoCollection;

class TestDocumentRepository extends DocumentRepository
{
    /**
     * @var MongoCollection
     */
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
     * @return ArrayIterator
     */
    public function testAggregate(array $ops = array())
    {
        return $this->aggregate($ops);
    }
}
