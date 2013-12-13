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
     * @var int
     */
    protected $currentWidth = 0;

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
     * @param string|null $style
     */
    public function write($dot = null, $style = null)
    {
        $message = $this->prepareDot($dot);
        if ($style) {
            $message = "<{$style}>" . $message . "</{$style}>";
        }
        $this->output->write($message);
        $this->position++;
        if (0 == ++$this->currentWidth % $this->width) {
            $this->writePosition();
        }
    }

    protected function writePosition()
    {
        $this->output->writeln('   ' . $this->position);
    }

    /**
     * @param string|null $dot
     * @return string
     */
    protected function prepareDot($dot = null)
    {
        $dot = (null !== $dot) ? $dot : $this->defaultSymbol;
        $dot = substr($dot, 0, 1);
        return $dot;
    }

    /**
     * @param string $dot
     */
    public function writeError($dot = null)
    {
        $this->write($dot, 'error');
    }

    /**
     * @param string $dot
     */
    public function writeInfo($dot = null)
    {
        $this->write($dot, 'info');
    }

    /**
     * @param string $dot
     */
    public function writeComment($dot = null)
    {
        $this->write($dot, 'comment');
    }

    /**
     * @param string $dot
     */
    public function writeQuestion($dot = null)
    {
        $this->write($dot, 'question');
    }

    /**
     * @param bool $resetPosition
     */
    public function end($resetPosition = true)
    {
        $currentWidth = $this->currentWidth % $this->width;
        if ($currentWidth > 0) {
            $missingDots = $this->width - $currentWidth;
            $this->output->write(str_repeat(' ', $missingDots));
            $this->writePosition();
            $this->currentWidth = 0;
        }

        if ($resetPosition) {
            $this->reset();
        }
    }

    public function reset()
    {
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
