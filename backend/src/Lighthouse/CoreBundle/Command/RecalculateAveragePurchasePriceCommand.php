<?php

namespace Lighthouse\CoreBundle\Command;

use Lighthouse\CoreBundle\Service\AveragePriceService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @DI\Service("lighthouse.core.command.recalculate_average_purchase_price")
 * @DI\Tag("console.command")
 */
class RecalculateAveragePurchasePriceCommand extends ContainerAwareCommand
{
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
    protected function getAveragePriceService()
    {
        return $this->getContainer()->get('lighthouse.core.service.average_price');
    }
}
