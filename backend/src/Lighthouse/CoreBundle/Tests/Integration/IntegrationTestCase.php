<?php

namespace Lighthouse\CoreBundle\Tests\Integration;

use Lighthouse\CoreBundle\Integration\Set10\ImportSales\ImportSalesXmlParser;
use Lighthouse\CoreBundle\Integration\Set10\ImportSales\SalesImporter;
use Lighthouse\CoreBundle\Test\TestOutput;
use Lighthouse\CoreBundle\Test\WebTestCase;
use Symfony\Component\Console\Output\OutputInterface;

class IntegrationTestCase extends WebTestCase
{
    /**
     * @param string $xmlFile
     * @param OutputInterface $output
     * @param int $batchSize
     * @return SalesImporter
     */
    protected function import($xmlFile, OutputInterface $output = null, $batchSize = null)
    {
        $importer = $this->getContainer()->get('lighthouse.core.integration.set10.import_sales.importer');
        $xmlFile = $this->getFixtureFilePath($xmlFile);
        $parser = new ImportSalesXmlParser($xmlFile);
        $output = ($output) ? : new TestOutput();
        $importer->import($parser, $output, $batchSize);

        return $importer;
    }
}
