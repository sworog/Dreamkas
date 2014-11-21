<?php

namespace Lighthouse\JobBundle\QueueCommand\Console;

use Lighthouse\JobBundle\QueueCommand\StatusReplier;
use Lighthouse\JobBundle\QueueCommand\Status;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Output\Output as BaseOutput;

class Output extends BaseOutput
{
    /**
     * @var StatusReplier
     */
    protected $replier;

    /**
     * @param StatusReplier $replier
     * @param bool|int $verbosity
     * @param bool $decorated
     * @param OutputFormatterInterface $formatter
     */
    public function __construct(
        StatusReplier $replier,
        $verbosity = BaseOutput::VERBOSITY_NORMAL,
        $decorated = false,
        OutputFormatterInterface $formatter = null
    ) {
        parent::__construct($verbosity, $decorated, $formatter);

        $this->replier = $replier;
    }

    /**
     * Writes a message to the output.
     *
     * @param string $message A message to write to the output
     * @param bool $newline Whether to add a newline or not
     */
    protected function doWrite($message, $newline)
    {
        $message.= ($newline ? PHP_EOL : '');
        $this->replier->sendStatus(Status::STATUS_PROCESSING, $message);
    }
}
