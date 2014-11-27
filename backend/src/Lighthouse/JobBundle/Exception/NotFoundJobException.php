<?php

namespace Lighthouse\JobBundle\Exception;

class NotFoundJobException extends JobException
{
    /**
     * @param string $jobId
     * @param string $tubeJobId
     */
    public function __construct($jobId, $tubeJobId)
    {
        $message = sprintf('Job with id #%s not found. Tube id #%s', $jobId, $tubeJobId);
        parent::__construct($message);
    }
}
