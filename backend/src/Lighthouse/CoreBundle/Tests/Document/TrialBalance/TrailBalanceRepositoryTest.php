<?php

namespace Lighthouse\CoreBundle\Tests\Document\TrialBalance;

use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;

class TrailBalanceRepositoryTest extends ContainerAwareTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->clearMongoDb();
    }

    public function testRecalculationDoesNotFailIfTrialBalanceCollectionDoesNotExist()
    {
        $trailBalanceRepository = $this->getContainer()->get('lighthouse.core.document.repository.trial_balance');
        $dm = $trailBalanceRepository->getDocumentManager();
        $documentName = $trailBalanceRepository->getDocumentName();
        $collection = $dm->getDocumentCollection($documentName);
        $collection->drop();

        $result = $trailBalanceRepository->calculateAveragePurchasePrice();
        $this->assertCount(0, $result);

        $result = $trailBalanceRepository->calculateDailyAverageSales();
        $this->assertCount(0, $result);
    }
}
