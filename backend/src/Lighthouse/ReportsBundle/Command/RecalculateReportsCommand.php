<?php

namespace Lighthouse\ReportsBundle\Command;

use Lighthouse\CoreBundle\Document\Project\Project;
use Lighthouse\CoreBundle\Security\Project\ProjectContext;
use Lighthouse\ReportsBundle\Reports\GrossMargin\GrossMarginManager;
use Lighthouse\ReportsBundle\Reports\GrossMarginSales\GrossMarginSalesReportManager;
use Symfony\Component\Console\Command\Command;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @DI\Service("lighthouse.reports.command.recalculate")
 * @DI\Tag("console.command")
 */
class RecalculateReportsCommand extends Command
{
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
     *      "grossMarginManager" = @DI\Inject("lighthouse.reports.gross_margin.manager"),
     *      "grossMarginSalesReportManager" = @DI\Inject("lighthouse.reports.gross_margin_sales.manager"),
     *      "projectContext" = @DI\Inject("project.context"),
     * })
     * @param GrossMarginManager $grossMarginManager
     * @param GrossMarginSalesReportManager $grossMarginSalesReportManager
     * @param ProjectContext $projectContext
     */
    public function __construct(
        GrossMarginManager $grossMarginManager,
        GrossMarginSalesReportManager $grossMarginSalesReportManager,
        ProjectContext $projectContext
    ) {
        parent::__construct('lighthouse:reports:recalculate');

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

        $this->projectContext->applyInProjects(
            function (Project $project, ContainerInterface $container) use ($output) {
                $grossMarginManager = $container->get('lighthouse.reports.gross_margin.manager');
                $grossMarginSalesReportManager = $container->get('lighthouse.reports.gross_margin_sales.manager');
                try {
                    $output->writeln("<info>Recalculate reports for project {$project->getName()}");

                    $output->writeln("<info>Cost Of Goods</info>");
                    $grossMarginManager->calculateGrossMarginUnprocessedTrialBalance($output);

                    $output->writeln("<info>Gross Margin Sales</info>");
                    $output->writeln("<info>Products</info>");
                    $grossMarginSalesReportManager->recalculateProductReport($output);

                    $output->writeln("<info>Catalog Groups</info>");
                    $grossMarginSalesReportManager->recalculateCatalogGroupReport($output);

                    $output->writeln("<info>Stores</info>");
                    $grossMarginSalesReportManager->recalculateStoreReport($output);

                    $output->writeln("<info>Network</info>");
                    $grossMarginSalesReportManager->recalculateNetworkReport($output);
                } catch (\Exception $e) {
                    $output->writeln(sprintf("<error>%s</error>", (string) $e));
                }
            }
        );

        $output->writeln("<info>Recalculate reports finished</info>");

        return 0;
    }
}
