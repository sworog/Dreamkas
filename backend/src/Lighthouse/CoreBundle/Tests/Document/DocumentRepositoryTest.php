<?php

namespace Lighthouse\CoreBundle\Tests\Document;

use Doctrine\MongoDB\Exception\ResultException;
use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use Lighthouse\CoreBundle\Tests\Fixtures\Document\TestDocument;
use Lighthouse\CoreBundle\Tests\Fixtures\Document\TestDocumentRepository;

class DocumentRepositoryTest extends ContainerAwareTestCase
{
    /**
     * @expectedException \Doctrine\MongoDB\Exception\ResultException
     * @expectedExceptionMessage exception:
     * @expectedExceptionCode 15942
     */
    public function testEmptyAggregateFail()
    {
        $this->getTestDocumentRepository()->testAggregate(array());
    }

    public function testAggregateOk()
    {
        $ops = array(
            '$match' => array('_id' => 1)
        );
        $result = $this->getTestDocumentRepository()->testAggregate($ops);
        $expectedCommandResult = array('ok' => 1, 'result' => array());
        $this->assertEquals($expectedCommandResult, $result->getCommandResult());
    }

    /**
     * @return TestDocumentRepository
     */
    protected function getTestDocumentRepository()
    {
        return $this->getDocumentManager()->getRepository(TestDocument::getClassName());
    }
}
