<?php

namespace Lighthouse\CoreBundle\Tests\Security;

use Lighthouse\CoreBundle\Security\PermissionExtractor;
use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;

class PermissionExtractorTest extends ContainerAwareTestCase
{
    public function testMethodsWithoutSecurity()
    {
        /* @var PermissionExtractor $extractor */
        $extractor = $this->getContainer()->get('lighthouse.core.security.permissions_extractor');
        $resources = $extractor->extractAll();
        $notSecuredResources = array();
        foreach ($resources as $name => $methods) {
            foreach ($methods as $method => $roles) {
                if (true === $roles) {
                    $notSecuredResources[$name][] = $method;
                }
            }
        }

        $expected = array(
            'jobs' => array(
                'GET'
            ),
            'logs' => array(
                'GET'
            ),
            'roundings' => array(
                'GET',
                'GET::{name}',
                'POST::{name}/round'
            ),
            'users' => array(
                'GET::current',
                'GET::permissions'
            ),
            'others' => array(
                '::api/1/service/permissions',
                '::api/1/service/recalculate-average-purchase-price'
            ),
        );
        $this->assertEquals($expected, $notSecuredResources, 'There are unexpected not secured methods');
    }
}
