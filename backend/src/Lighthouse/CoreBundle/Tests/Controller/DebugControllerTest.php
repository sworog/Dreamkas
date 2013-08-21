<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\WebTestCase;

class DebugControllerTest extends WebTestCase
{
    public function testIndexAction()
    {
        $this->client->request('GET', '/debug');

        $this->assertResponseCode(200);

        $content = $this->client->getResponse()->getContent();
        $this->assertContains('Zend', $content);
    }
}
