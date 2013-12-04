<?php

namespace Lighthouse\CoreBundle\Command\Reports;

use Lighthouse\CoreBundle\Document\Report\GrossSales\GrossSalesReportManager;
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
     * @var GrossSalesReportManager
     */
    protected $storeGrossSalesReportManager;

    /**
     * @DI\InjectParams({
     *      "storeGrossSalesManager" = @DI\Inject("lighthouse.core.document.report.gross_sales.manager")
     * })
     * @param GrossSalesReportManager $storeGrossSalesManager
     */
    public function __construct(GrossSalesReportManager $storeGrossSalesManager)
    {
        parent::__construct('lighthouse:reports:recalculate');

        $this->storeGrossSalesReportManager = $storeGrossSalesManager;
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

        $this->storeGrossSalesReportManager->recalculateStoreGrossSalesReport();

        $output->writeln("<info>Recalculate reports finished</info>");

        return 0;
    }
}
