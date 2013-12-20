<?php

namespace Lighthouse\CoreBundle\Document\Product\RecalcProductPrice;

use Lighthouse\CoreBundle\Document\Job\Integration\Set10\ExportProductsJob;
use Lighthouse\CoreBundle\Test\TestCase;

class RecalcProductPriceWorkerTest extends TestCase
{
    /**
     * @return RecalcProductPriceWorker|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getWorker()
    {
        $repositoryMock = $this->getMock(
            'Lighthouse\\CoreBundle\\Document\\Product\\Store\\StoreProductRepository',
            array(),
            array(),
            '',
            false
        );
        return new RecalcProductPriceWorker($repositoryMock);
    }

    public function testSupportedJob()
    {
        $worker = $this->getWorker();
        $supportedJob = new RecalcProductPriceJob();
        $this->assertTrue($worker->supports($supportedJob));
    }

    public function testNotSupportedJob()
    {
        $worker = $this->getWorker();
        $supportedJob = new ExportProductsJob();
        $this->assertFalse($worker->supports($supportedJob));
    }
}
