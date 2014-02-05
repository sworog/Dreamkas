<?php

namespace Lighthouse\CoreBundle\Tests\Command\Import;

use Lighthouse\CoreBundle\Command\Import\OneCInvoicesImport;
use Lighthouse\CoreBundle\Integration\OneC\Import\Invoices\InvoicesImporter;
use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class OneCInvoicesImportTest extends ContainerAwareTestCase
{
    public function testExecute()
    {
        /* @var InvoicesImporter|\PHPUnit_Framework_MockObject_MockObject $importerMock */
        $importerMock = $this->getMock(
            'Lighthouse\\CoreBundle\\Integration\\OneC\\Import\\Invoices\\InvoicesImporter',
            array(),
            array(),
            '',
            false
        );
        $importerMock
            ->expects($this->once())
            ->method('import')
            ->with(
                $this->equalTo('/tmp/filepath'),
                $this->equalTo(100),
                $this->isInstanceOf('Symfony\\Component\\Console\\Output\\OutputInterface'),
                $this->isNull()
            );


        $command = new OneCInvoicesImport($importerMock);

        $commandTester = new CommandTester($command);

        $input = array('file' => '/tmp/filepath');

        $exitCode = $commandTester->execute($input);

        $this->assertEquals(0, $exitCode);
    }

    public function testExecuteWithDates()
    {
        /* @var InvoicesImporter|\PHPUnit_Framework_MockObject_MockObject $importerMock */
        $importerMock = $this->getMock(
            'Lighthouse\\CoreBundle\\Integration\\OneC\\Import\\Invoices\\InvoicesImporter',
            array(),
            array(),
            '',
            false
        );
        $importerMock
            ->expects($this->once())
            ->method('import')
            ->with(
                $this->equalTo('/tmp/filepath'),
                $this->equalTo(100),
                $this->isInstanceOf('Symfony\\Component\\Console\\Output\\OutputInterface'),
                $this->isInstanceOf('Lighthouse\\CoreBundle\\Types\\Date\\DatePeriod')
            );


        $command = new OneCInvoicesImport($importerMock);

        $commandTester = new CommandTester($command);

        $input = array(
            'file' => '/tmp/filepath',
            '--original-date' => '2013-02-01',
            '--import-date' => '2014-01-01'
        );

        $exitCode = $commandTester->execute($input);

        $this->assertEquals(0, $exitCode);
    }
}
