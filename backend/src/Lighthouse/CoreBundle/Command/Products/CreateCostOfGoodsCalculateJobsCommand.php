<?php

namespace Lighthouse\CoreBundle\Command\Products;

use Lighthouse\CoreBundle\Document\TrialBalance\CostOfGoods\CostOfGoodsManager;
use Symfony\Component\Console\Command\Command;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @DI\Service("lighthouse.core.command.product.create_cost_of_goods_calculate_jobs")
 * @DI\Tag("console.command")
 */
class CreateCostOfGoodsCalculateJobsCommand extends Command
{
    /**
     * @var CostOfGoodsManager
     */
    protected $costOfGoodsManager;

    /**
     * @DI\InjectParams({
     *      "costOfGoodsManager" = @DI\Inject("lighthouse.core.document.trial_balance.cost_of_goods.manager"),
     * })
     *
     * @param CostOfGoodsManager $costOfGoodsManager
     */
    public function __construct(CostOfGoodsManager $costOfGoodsManager)
    {
        parent::__construct("lighthouse:products:create_cost_of_goods_calculate_jobs");

        $this->costOfGoodsManager = $costOfGoodsManager;
    }

    /**
     *
     */
    protected function configure()
    {
        $this->setDescription('Create jobs for calculating cost of goods in TrialBalances');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<info>Creating jobs started</info>");

        $count = $this->costOfGoodsManager->createCalculateJobsForUnprocessed();

        $output->writeln("<info>Creating jobs finished, created $count jobs</info>");

        return 0;
    }
}
