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
            ->with($this->equalTo('/tmp/filepath'), $this->equalTo(100), $this->anything());


        $command = new OneCInvoicesImport($importerMock);

        $commandTester = new CommandTester($command);

        $input = array('file' => '/tmp/filepath');

        $exitCode = $commandTester->execute($input);

        $this->assertEquals(0, $exitCode);
    }
}
