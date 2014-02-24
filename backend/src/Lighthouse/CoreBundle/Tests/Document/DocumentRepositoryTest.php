<?php

namespace Lighthouse\CoreBundle\Tests\Document;

use Lighthouse\CoreBundle\Test\TestCase;
use MongoCollection;
use PHPUnit_Framework_MockObject_MockObject;

class DocumentRepositoryTest extends TestCase
{
    /**
     * @expectedException \Doctrine\ODM\MongoDB\MongoDBException
     * @expectedExceptionMessage Error
     * @expectedExceptionCode 22
     */
    public function testAggregateException()
    {
        /* @var MongoCollection|PHPUnit_Framework_MockObject_MockObject $collection */
        $collection = $this->getMock(
            'MongoCollection',
            array(),
            array(),
            '',
            false
        );
        $aggregateResponse = array(
            'ok' => 0,
            'errmsg' => 'Error',
            'code' => 22,
        );
        $collection->expects($this->once())
                    ->method('aggregate')
                    ->will($this->returnValue($aggregateResponse));

        $repository = new TestDocumentRepository($collection);

        $repository->testAggregate();
    }

    public function testAggregate()
    {
        /* @var MongoCollection|PHPUnit_Framework_MockObject_MockObject $collection */
        $collection = $this->getMock(
            'MongoCollection',
            array(),
            array(),
            '',
            false
        );
        $aggregateResult = array(1,2);
        $aggregateResponse = array(
            'ok' => 1,
            'result' => $aggregateResult,
        );
        $collection->expects($this->once())
            ->method('aggregate')
            ->will($this->returnValue($aggregateResponse));

        $repository = new TestDocumentRepository($collection);

        $result = $repository->testAggregate();

        $this->assertSame($aggregateResult, $result);
    }
}
