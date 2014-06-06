<?php

namespace Lighthouse\CoreBundle\Tests\Document\TrialBalance;

use Lighthouse\CoreBundle\Document\TrialBalance\TrialBalanceRepository;
use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;

class TrialBalanceRepositoryTest extends ContainerAwareTestCase
{
    public function testRecalculationDoesNotFailIfTrialBalanceCollectionDoesNotExist()
    {
        $this->clearMongoDb();
        $this->authenticateProject();

        /* @var TrialBalanceRepository $trailBalanceRepository */
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
