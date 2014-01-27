<?php

namespace Lighthouse\ReportsBundle\Command;

use Lighthouse\ReportsBundle\Reports\GrossMargin\GrossMarginManager;
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
    protected $grossSalesManager;

    /**
     * @var GrossMarginManager
     */
    protected $grossMarginManager;

    /**
     * @DI\InjectParams({
     *      "grossSalesManager" = @DI\Inject("lighthouse.reports.gross_sales.manager"),
     *      "grossMarginManager" = @DI\Inject("lighthouse.reports.gross_margin.manager")
     * })
     * @param GrossSalesReportManager $grossSalesManager
     * @param GrossMarginManager $grossMarginManager
     */
    public function __construct(
        GrossSalesReportManager $grossSalesManager,
        GrossMarginManager $grossMarginManager
    ) {
        parent::__construct('lighthouse:reports:recalculate');

        $this->grossSalesManager = $grossSalesManager;
        $this->grossMarginManager = $grossMarginManager;
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
        $this->grossSalesManager->recalculateStoreGrossSalesReport();

        $output->writeln("<info>Product Gross Sales</info>");
        $this->grossSalesManager->recalculateGrossSalesProductReport(5000);

        $output->writeln("<info>SubCategory Gross Sales</info>");
        $this->grossSalesManager->recalculateGrossSalesBySubCategories($output);

        $output->writeln("<info>Category Gross Sales</info>");
        $this->grossSalesManager->recalculateGrossSalesByCategories($output);

        $output->writeln("<info>Group Gross Sales</info>");
        $this->grossSalesManager->recalculateGrossSalesByGroups($output);

        $output->writeln("<info>Store Gross Margin</info>");
        $this->grossMarginManager->calculateGrossMarginUnprocessedTrialBalance();
        $this->grossMarginManager->recalculateStoreGrossMargin();

        $output->writeln("<info>Recalculate reports finished</info>");

        return 0;
    }
}
