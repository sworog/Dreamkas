<?php

namespace Lighthouse\CoreBundle\Command;

use Lighthouse\CoreBundle\Service\AveragePriceService;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @DI\Service("lighthouse.core.command.recalculate_average_purchase_price")
 * @DI\Tag("console.command")
 */
class RecalculateAveragePurchasePriceCommand extends Command
{
    /**
     * @var AveragePriceService
     */
    protected $averagePriceService;

    /**
     *
     */
    protected function configure()
    {
        $this
            ->setName('lighthouse:recalculate-average-purchase-price')
            ->setDescription('Recalculate average purchase price');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<info>Recalculate started</info>");

        $this->getAveragePriceService()->recalculateAveragePrice();

        $output->writeln("<info>Recalculate finished</info>");

        return 0;
    }

    /**
     * @return AveragePriceService
     */
    public function getAveragePriceService()
    {
        return $this->averagePriceService;
    }

    /**
     * @DI\InjectParams({
     *      "averagePriceService"=@DI\Inject("lighthouse.core.service.average_price")
     * })
     * @param AveragePriceService $averagePriceService
     */
    public function setAveragePriceService(AveragePriceService $averagePriceService)
    {
        $this->averagePriceService = $averagePriceService;
    }
}
