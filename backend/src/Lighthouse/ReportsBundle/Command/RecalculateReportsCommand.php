<?php

namespace Lighthouse\ReportsBundle\Command;

use Lighthouse\ReportsBundle\Reports\GrossSales\GrossSalesReportManager;
use Symfony\Component\Console\Command\Command;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @DI\Service("lighthouse.reports.command.recalculate")
 * @DI\Tag("console.command")
 */
class RecalculateReportsCommand extends Command
{
    /**
     * @var GrossSalesReportManager
     */
    protected $grossSalesReportManager;

    /**
     * @DI\InjectParams({
     *      "grossSalesReportManager" = @DI\Inject("lighthouse.reports.gross_sales.manager")
     * })
     * @param GrossSalesReportManager $grossSalesReportManager
     */
    public function __construct(GrossSalesReportManager $grossSalesReportManager)
    {
        parent::__construct('lighthouse:reports:recalculate');

        $this->grossSalesReportManager = $grossSalesReportManager;
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

        $output->writeln("<info>Store Gross Sales</info>");
        $this->grossSalesReportManager->recalculateStoreGrossSalesReport();

        $output->writeln("<info>Product Gross Sales</info>");
        $this->grossSalesReportManager->recalculateGrossSalesProductReport(5000);

        $output->writeln("<info>SubCategory Gross Sales</info>");
        $this->grossSalesReportManager->recalculateGrossSalesBySubCategories($output);

        $output->writeln("<info>Category Gross Sales</info>");
        $this->grossSalesReportManager->recalculateGrossSalesByCategories($output);

        $output->writeln("<info>Group Gross Sales</info>");
        $this->grossSalesReportManager->recalculateGrossSalesByGroups($output);

        $output->writeln("<info>Recalculate reports finished</info>");

        return 0;
    }
}
