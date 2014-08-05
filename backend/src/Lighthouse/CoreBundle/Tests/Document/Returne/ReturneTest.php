<?php

namespace Lighthouse\CoreBundle\Tests\Document\Returne;

use Lighthouse\CoreBundle\Document\StockMovement\Returne\Returne;
use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;

class ReturneTest extends ContainerAwareTestCase
{
    public function testCreatedDateIsSetOnPrePersist()
    {
        $this->clearMongoDb();
        $this->authenticateProject();

        $dm = $this->getDocumentManager();
        $return = new Returne();
        $return->hash = 'hash';

        $this->assertNull($return->date);
        $dm->persist($return);

        $expectedTime = time();

        $dm->flush();

        $this->assertNotNull($return->date);
        $this->assertEquals($expectedTime, $return->date->getTimestamp());
    }
}
