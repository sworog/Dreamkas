<?php

namespace Lighthouse\ReportsBundle\Command;

use Lighthouse\CoreBundle\Command\ProjectableCommand;
use Lighthouse\CoreBundle\Security\Project\ProjectContext;
use Lighthouse\ReportsBundle\Reports\GrossMargin\GrossMarginManager;
use Lighthouse\ReportsBundle\Reports\GrossMarginSales\GrossMarginSalesReportManager;
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
     * @var GrossMarginSalesReportManager
     */
    protected $grossMarginSalesReportManager;

    /**
     * @var ProjectContext
     */
    protected $projectContext;

    /**
     * @DI\InjectParams({
     *      "grossSalesManager" = @DI\Inject("lighthouse.reports.gross_sales.manager"),
     *      "grossMarginManager" = @DI\Inject("lighthouse.reports.gross_margin.manager"),
     *      "grossMarginSalesReportManager" = @DI\Inject("lighthouse.reports.gross_margin_sales.manager"),
     *      "projectContext" = @DI\Inject("project.context"),
     * })
     * @param GrossSalesReportManager $grossSalesManager
     * @param GrossMarginManager $grossMarginManager
     * @param GrossMarginSalesReportManager $grossMarginSalesReportManager
     * @param ProjectContext $projectContext
     */
    public function __construct(
        GrossSalesReportManager $grossSalesManager,
        GrossMarginManager $grossMarginManager,
        GrossMarginSalesReportManager $grossMarginSalesReportManager,
        ProjectContext $projectContext
    ) {
        parent::__construct('lighthouse:reports:recalculate');

        $this->grossSalesManager = $grossSalesManager;
        $this->grossMarginManager = $grossMarginManager;
        $this->grossMarginSalesReportManager = $grossMarginSalesReportManager;
        $this->projectContext = $projectContext;
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

        $projects = $this->projectContext->getAllProjects();
        foreach ($projects as $project) {
            $output->writeln("<info>Recalculate reports for project {$project->getName()}");

            $this->projectContext->authenticate($project);

            $this->grossMarginManager->calculateGrossMarginUnprocessedTrialBalance($output);

            $output->writeln("<info>Gross Margin Sales</info>");
            $this->grossMarginSalesReportManager->recalculateGrossMarginSalesProductReport();

            $this->projectContext->logout();
        }

        $output->writeln("<info>Recalculate reports finished</info>");

        return 0;
    }
}
