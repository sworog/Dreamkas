<?php

namespace Lighthouse\CoreBundle\Tests\Console;

use Lighthouse\CoreBundle\Console\DotHelper;
use Lighthouse\CoreBundle\Test\TestCase;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Output\NullOutput;

class DotHelperTest extends TestCase
{
    public function testGetName()
    {
        $dotHelper = new DotHelper();
        $helperSet = new HelperSet();
        $helperSet->set($dotHelper);
        $helper = $helperSet->get('dot');
        $this->assertSame($dotHelper, $helper);
    }

    public function testConstructWithOutput()
    {
        $output = new NullOutput();
        $dotHelper = new DotHelper($output);
        $this->assertSame($output, $dotHelper->getOutput());
    }
}
