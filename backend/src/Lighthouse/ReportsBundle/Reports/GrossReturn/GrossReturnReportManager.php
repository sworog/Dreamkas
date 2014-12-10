<?php

namespace Lighthouse\ReportsBundle\Reports\GrossReturn;

use JMS\DiExtraBundle\Annotation\Service;
use Lighthouse\ReportsBundle\Document\GrossReturn\Network\GrossReturnNetworkRepository;
use Symfony\Component\Console\Output\OutputInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @Service("lighthouse.reports.document.gross_return.manager")
 */
class GrossReturnReportManager
{
    /**
     * @var GrossReturnNetworkRepository
     */
    protected $grossReturnNetworkRepository;

    /**
     * @DI\InjectParams({
     *      "grossReturnNetworkRepository" = @DI\Inject("lighthouse.reports.document.gross_return.network.repository"),
     * })
     *
     * @param GrossReturnNetworkRepository $grossReturnNetworkRepository
     */
    public function __construct(GrossReturnNetworkRepository $grossReturnNetworkRepository)
    {
        $this->grossReturnNetworkRepository = $grossReturnNetworkRepository;
    }

    /**
     * @param OutputInterface $output
     * @return int
     */
    public function recalculateNetworkReport(OutputInterface $output = null)
    {
        return $this->grossReturnNetworkRepository->recalculate($output);
    }
}
