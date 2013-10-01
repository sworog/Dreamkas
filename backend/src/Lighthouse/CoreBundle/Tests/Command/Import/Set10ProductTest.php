<?php

namespace Lighthouse\CoreBundle\Tests\Command\Import;

use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class Set10ProductTest extends ContainerAwareTestCase
{
    public function testExecute()
    {
        $this->clearMongoDb();

        $command = $this->getContainer()->get('lighthouse.core.command.import.set10_product');
        $commandTester = new CommandTester($command);

        $input = array(
            'file' => __DIR__ . "/../../Fixtures/Integration/Set10/Import/goods.xml",
        );

        $exitCode = $commandTester->execute($input);

        $this->assertEquals(0, $exitCode);

        $display = $commandTester->getDisplay();

        $this->assertContains("Starting import", $display);

        $this->assertContains("Flushing", $display);

        $this->assertContains('Persist product "Шар Чупа Чупс Смешарики Машина времени шокол.25г"', $display);
        $this->assertContains('Persist product "Шарики Брюгген Чокин Крипс рисовые шок.250г"', $display);
        $this->assertContains('Persist product "Шашлык из креветок пр-во Лэнд"', $display);
        $this->assertContains('Persist product "Шашлык из курицы (филе) п/ф Кулинария"', $display);

        $this->assertContains("Done", $display);


        $exitCode = $commandTester->execute($input);

        $this->assertEquals(0, $exitCode);

        $display = $commandTester->getDisplay();

        $this->assertContains("Starting import", $display);

        $this->assertContains('sku: Такой артикул уже есть', $display);

        $this->assertNotContains('Persist product "Шар Чупа Чупс Смешарики Машина времени шокол.25г"', $display);
        $this->assertNotContains('Persist product "Шарики Брюгген Чокин Крипс рисовые шок.250г"', $display);
        $this->assertNotContains('Persist product "Шашлык из креветок пр-во Лэнд"', $display);
        $this->assertNotContains('Persist product "Шашлык из курицы (филе) п/ф Кулинария"', $display);

        $this->assertContains("Done", $display);
    }
}
