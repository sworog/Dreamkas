<?php

namespace Lighthouse\CoreBundle\Test\Client;

use Symfony\Bundle\FrameworkBundle\Client as BaseClient;

class Client extends BaseClient
{
    protected function createCrawlerFromContent($uri, $content, $type)
    {
        return null;
    }
}
