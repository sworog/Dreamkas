<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\Log\LogRepository;
use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class LogControllerTest extends WebTestCase
{
    public function testGetLogsAction()
    {
        /** @var LogRepository $logRepository */
        $logRepository = $this->getContainer()->get('lighthouse.core.document.repository.log');

        $testLog1 = "test log";
        $logRepository->createLog($testLog1);

        $testLogDate = strtotime("-1 days");
        $testLogDate = new \DateTime("@".$testLogDate);
        $testLog2 = "test log 22";
        $logRepository->createLog($testLog2, $testLogDate);

        $accessToken = $this->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $response = $this->clientJsonRequest(
            $accessToken,
            "GET",
            "/api/1/logs"
        );

        Assert::assertJsonPathCount(2, "*.id", $response);
        Assert::assertJsonPathEquals($testLog1, "*.message", $response);
        Assert::assertJsonPathEquals($testLog2, "*.message", $response);
        Assert::assertJsonPathEquals($testLog1, "0.message", $response);
        Assert::assertJsonPathEquals($testLog2, "1.message", $response);
    }
}
