<?php

namespace Lighthouse\CoreBundle\Tests\Document\Returne;

use Lighthouse\CoreBundle\Document\Returne\Returne;
use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;

class ReturneTest extends ContainerAwareTestCase
{
    public function testCreatedDateIsSetOnPrePersist()
    {
        $this->clearMongoDb();

        $dm = $this->getDocumentManager();
        $return = new Returne();
        $return->hash = 'hash';

        $this->assertNull($return->createdDate);
        $dm->persist($return);

        $expectedTime = time();

        $dm->flush();

        $this->assertNotNull($return->createdDate);
        $this->assertEquals($expectedTime, $return->createdDate->getTimestamp());
    }
}
