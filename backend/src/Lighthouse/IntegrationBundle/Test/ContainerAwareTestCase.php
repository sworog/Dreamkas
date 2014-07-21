<?php

namespace Lighthouse\IntegrationBundle\Test;

use Lighthouse\CoreBundle\Test\ContainerAwareTestCase as BaseContainerAwareTestCase;

class ContainerAwareTestCase extends BaseContainerAwareTestCase
{
    /**
     * @param string $filePath
     * @return string
     */
    protected function getFixtureFilePath($filePath)
    {
        return __DIR__ . '/../Tests/Fixtures/' . $filePath;
    }
}
