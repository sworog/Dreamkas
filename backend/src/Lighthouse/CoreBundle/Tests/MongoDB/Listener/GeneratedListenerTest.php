<?php

namespace Lighthouse\CoreBundle\Tests\MongoDB\Listener;

use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use Lighthouse\CoreBundle\Tests\Fixtures\Document\GeneratedDocument;

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
}
