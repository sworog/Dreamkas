<?php

namespace Lighthouse\JobBundle\QueueCommand\Console;

use Lighthouse\JobBundle\QueueCommand\Reply\Replier;
use Lighthouse\JobBundle\QueueCommand\Reply\Reply;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\Output as BaseOutput;
use Symfony\Component\Console\Output\OutputInterface;

class Output extends BaseOutput
{
    /**
     * @var Replier
     */
    protected $replier;

    /**
     * @var BaseOutput
     */
    protected $outerOutput;

    /**
     * @param Replier $replier
     * @param OutputInterface $outerOutput
     * @param bool|int|null|OutputFormatterInterface $verbosity
     * @param bool $decorated
     * @param OutputFormatterInterface $formatter
     */
    public function __construct(
        Replier $replier,
        BaseOutput $outerOutput = null,
        $verbosity = BaseOutput::VERBOSITY_NORMAL,
        $decorated = false,
        OutputFormatterInterface $formatter = null
    ) {
        parent::__construct($verbosity, $decorated, $formatter);

        $this->replier = $replier;
        $this->outerOutput = ($outerOutput) ?: new NullOutput();
    }

    /**
     * Writes a message to the output.
     *
     * @param string $message A message to write to the output
     * @param bool $newline Whether to add a newline or not
     */
    protected function doWrite($message, $newline)
    {
        $data = $message . ($newline ? PHP_EOL : '');
        $this->replier->reply(Reply::STATUS_PROCESSING, $data);

        if ($this->outerOutput->isVerbose()) {
            $this->outerOutput->write($message, $newline);
            if ($newline) {
                $this->outerOutput->write('>>> ');
            }
        }
    }
}
