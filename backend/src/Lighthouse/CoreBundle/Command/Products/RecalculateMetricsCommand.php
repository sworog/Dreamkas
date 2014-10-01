<?php

namespace Lighthouse\CoreBundle\Command\Products;

use Lighthouse\CoreBundle\Command\ProjectableCommand;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProductMetricsCalculator;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Security\Project\ProjectContext;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @DI\Service("lighthouse.core.command.product.recalculate_metrics")
 * @DI\Tag("console.command")
 */
class RecalculateMetricsCommand extends Command
{
    /**
     * @var StoreProductMetricsCalculator
     */
    protected $metricsCalculator;

    /**
     * @var ProjectContext
     */
    protected $projectContext;

    /**
     * @DI\InjectParams({
     *      "metricsCalculator" = @DI\Inject("lighthouse.core.service.product.metrics_calculator"),
     *      "projectContext" = @DI\Inject("project.context")
     * })
     * @param StoreProductMetricsCalculator $metricsCalculator
     * @param ProjectContext $projectContext
     */
    public function __construct(
        StoreProductMetricsCalculator $metricsCalculator,
        ProjectContext $projectContext
    ) {
        parent::__construct('lighthouse:products:recalculate_metrics');

        $this->metricsCalculator = $metricsCalculator;
        $this->projectContext = $projectContext;
    }

    /**
     *
     */
    protected function configure()
    {
        $this->setDescription('Recalculate Product metrics');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<info>Recalculate started</info>");

        $projects = $this->projectContext->getAllProjects();
        foreach ($projects as $project) {
            $output->writeln("<info>Recalculate metrics for project {$project->getName()}</info>");

            $this->projectContext->authenticate($project);

            $this->metricsCalculator->recalculateAveragePrice();
            $this->metricsCalculator->recalculateDailyAverageSales();
        }

        $output->writeln("<info>Recalculate finished</info>");

        return 0;
    }
}
