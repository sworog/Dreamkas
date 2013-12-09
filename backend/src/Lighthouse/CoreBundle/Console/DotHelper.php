<?php

namespace Lighthouse\CoreBundle\Console;

use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Output\OutputInterface;

class DotHelper extends Helper
{
    /**
     * @var int
     */
    protected $width = 50;

    /**
     * @var string
     */
    protected $defaultSymbol = '.';

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var int
     */
    protected $position = 0;

    /**
     * @param OutputInterface $output
     */
    public function __construct(OutputInterface $output = null)
    {
        if ($output) {
            $this->setOutput($output);
        }
    }

    /**
     * @param OutputInterface $output
     */
    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * @return OutputInterface
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @param string|null $dot
     */
    public function write($dot = null)
    {
        $dot = (null !== $dot) ? $dot : $this->defaultSymbol;
        $this->output->write($dot);
        if (0 == ++$this->position % $this->width) {
            $this->output->writeln('   ' . $this->position);
        }
    }

    public function end()
    {
        $missingDots = $this->width - ($this->position % $this->width);
        $this->output->writeln(str_repeat(' ', $missingDots) . '   ' . $this->position);
        $this->position = 0;
    }

    /**
     * @return string|void
     */
    public function getName()
    {
        return 'dot';
    }
}
