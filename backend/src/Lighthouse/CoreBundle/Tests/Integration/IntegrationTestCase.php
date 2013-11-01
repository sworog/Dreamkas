<?php

namespace Lighthouse\CoreBundle\Tests\Integration;

use Lighthouse\CoreBundle\Integration\Set10\ImportCheques\ImportChequesXmlParser;
use Lighthouse\CoreBundle\Integration\Set10\ImportCheques\ChequesImporter;
use Lighthouse\CoreBundle\Test\TestOutput;
use Lighthouse\CoreBundle\Test\WebTestCase;
use Symfony\Component\Console\Output\OutputInterface;

class IntegrationTestCase extends WebTestCase
{
    /**
     * @param string $xmlFile
     * @param OutputInterface $output
     * @param int $batchSize
     * @return ChequesImporter
     */
    protected function import($xmlFile, OutputInterface $output = null, $batchSize = null)
    {
        $importer = $this->getContainer()->get('lighthouse.core.integration.set10.import_cheques.importer');
        $xmlFile = $this->getFixtureFilePath($xmlFile);
        $parser = new ImportChequesXmlParser($xmlFile);
        $output = ($output) ? : new TestOutput();
        $importer->import($parser, $output, $batchSize);

        return $importer;
    }
}
