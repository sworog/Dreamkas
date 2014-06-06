<?php

namespace Lighthouse\CoreBundle\Command\Products;

use Lighthouse\CoreBundle\Command\ProjectableCommand;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProductMetricsCalculator;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @DI\Service("lighthouse.core.command.product.recalculate_metrics")
 * @DI\Tag("console.command")
 */
class RecalculateMetricsCommand extends Command implements ProjectableCommand
{
    /**
     * @var StoreProductMetricsCalculator
     */
    protected $metricsCalculator;

    /**
     * @DI\InjectParams({
     *      "metricsCalculator" = @DI\Inject("lighthouse.core.service.product.metrics_calculator")
     * })
     * @param StoreProductMetricsCalculator $metricsCalculator
     */
    public function __construct(StoreProductMetricsCalculator $metricsCalculator)
    {
        parent::__construct('lighthouse:products:recalculate_metrics');

        $this->metricsCalculator = $metricsCalculator;
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

        $this->metricsCalculator->recalculateAveragePrice();
        $this->metricsCalculator->recalculateDailyAverageSales();

        $output->writeln("<info>Recalculate finished</info>");

        return 0;
    }
}
