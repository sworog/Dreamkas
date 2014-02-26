<?php

namespace Lighthouse\CoreBundle\Tests\MongoDB\Reference;

use Lighthouse\CoreBundle\MongoDB\Reference\ReferenceManager;
use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;

class ReferenceManagerTest extends ContainerAwareTestCase
{
    public function testGetProviders()
    {
        /* @var ReferenceManager $manager */
        $manager = $this->getContainer()->get('lighthouse.core.mongodb.reference.manager');
        $providers = $manager->getReferenceProviders();
        $this->assertCount(1, $providers);
    }
}
