<?php

namespace Lighthouse\JobBundle\QueueCommand;

use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Output\Output as BaseOutput;

class Output extends BaseOutput
{
    /**
     * @var Replier
     */
    protected $replier;

    /**
     * @param Replier $replier
     * @param bool|int $verbosity
     * @param bool $decorated
     * @param OutputFormatterInterface $formatter
     */
    public function __construct(
        Replier $replier,
        $verbosity = self::VERBOSITY_NORMAL,
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
