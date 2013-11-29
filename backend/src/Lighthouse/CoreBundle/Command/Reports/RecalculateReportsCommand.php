<?php

namespace Lighthouse\CoreBundle\Command\Reports;

use Lighthouse\CoreBundle\Service\StoreGrossSalesReportService;
use Symfony\Component\Console\Command\Command;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @DI\Service("lighthouse.core.command.reports.recalculate")
 * @DI\Tag("console.command")
 */
class RecalculateReportsCommand extends Command
{
    /**
     * @var StoreGrossSalesReportService
     */
    protected $storeGrossSalesReportService;

    /**
     * @DI\InjectParams({
     *      "storeGrossSalesCalculator" = @DI\Inject("lighthouse.core.service.store.report.gross_sales")
     * })
     * @param StoreGrossSalesReportService $storeGrossSalesCalculator
     */
    public function __construct(StoreGrossSalesReportService $storeGrossSalesCalculator)
    {
        parent::__construct('lighthouse:reports:recalculate');

        $this->storeGrossSalesReportService = $storeGrossSalesCalculator;
    }

    /**
     *
     */
    protected function configure()
    {
        $this->setDescription('Recalculate Reports Data');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<info>Recalculate reports started</info>");

        $this->storeGrossSalesReportService->recalculateStoreGrossSalesReport();

        $output->writeln("<info>Recalculate reports finished</info>");

        return 0;
    }
}
