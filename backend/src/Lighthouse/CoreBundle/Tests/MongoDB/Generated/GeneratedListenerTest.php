<?php

namespace Lighthouse\CoreBundle\Tests\MongoDB\Generated;

use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use Lighthouse\CoreBundle\Tests\Fixtures\Document\GeneratedDocument;
use Lighthouse\CoreBundle\Tests\Fixtures\Document\GeneratedDocumentWithStartValue;

class GeneratedListenerTest extends ContainerAwareTestCase
{
    public function testValueGeneratedSinglePersist()
    {
        $this->clearMongoDb();

        for ($i = 1; $i < 10; $i++) {
            $document = new GeneratedDocument();

            $this->getDocumentManager()->persist($document);
            $this->getDocumentManager()->flush();

            $this->assertEquals($i, $document->getSku());
        }
    }

    public function testValueGeneratedMultiplePersist()
    {
        $this->clearMongoDb();

        /* @var GeneratedDocument[] $documents */
        $documents = array();
        for ($i = 1; $i < 10; $i++) {
            $documents[$i] = new GeneratedDocument();

            $this->getDocumentManager()->persist($documents[$i]);
        }
        $this->getDocumentManager()->flush();

        foreach ($documents as $expectedValue => $document) {
            $this->assertEquals($expectedValue, $document->getSku());
        }
    }

    public function testStartValue()
    {
        $this->clearMongoDb();

        for ($i = 1; $i < 10; $i++) {
            $document = new GeneratedDocumentWithStartValue();

            $this->getDocumentManager()->persist($document);
            $this->getDocumentManager()->flush();

            $this->assertEquals($i + 10000, $document->getSku());
        }
    }
}
