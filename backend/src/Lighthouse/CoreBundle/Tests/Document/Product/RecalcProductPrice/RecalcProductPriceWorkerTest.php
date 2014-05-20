<?php

namespace Lighthouse\CoreBundle\Tests\Document\Product\RecalcProductPrice;

use Lighthouse\CoreBundle\Document\Job\Integration\Set10\ExportProductsJob;
use Lighthouse\CoreBundle\Document\Product\RecalcProductPrice\RecalcProductPriceJob;
use Lighthouse\CoreBundle\Document\Product\RecalcProductPrice\RecalcProductPriceWorker;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProductRepository;
use Lighthouse\CoreBundle\Test\TestCase;

class RecalcProductPriceWorkerTest extends TestCase
{
    /**
     * @return RecalcProductPriceWorker
     */
    protected function getWorker()
    {
        /* @var StoreProductRepository|\PHPUnit_Framework_MockObject_MockObject $repositoryMock */
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
