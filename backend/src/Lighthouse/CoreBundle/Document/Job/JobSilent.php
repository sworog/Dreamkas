<?php

namespace Lighthouse\CoreBundle\Document\Job;

abstract class JobSilent extends Job
{
    /**
     * @var bool
     */
    protected $silent = true;
}
