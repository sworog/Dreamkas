<?php

namespace Lighthouse\CoreBundle\Exception\Job;

class NotFoundJobException extends JobException
{
    /**
     * @param string $jobId
     */
    public function __construct($jobId, $tubeJobId)
    {
        $message = sprintf('Job with id #%s not found. Tube id #%s', $jobId, $tubeJobId);
        parent::__construct($message);
    }
}
