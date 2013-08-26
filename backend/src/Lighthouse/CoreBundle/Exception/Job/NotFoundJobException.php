<?php

namespace Lighthouse\CoreBundle\Exception\Job;

class NotFoundJobException extends JobException
{
    /**
     * @param string $jobId
     */
    public function __construct($jobId)
    {
        $message = sprintf('Job with id #%s not found', $jobId);
        parent::__construct($message);
    }
}
