<?php

namespace Lighthouse\CoreBundle\Document\TrialBalance;

interface Reasonable
{
    /**
     * @return string
     */
    public function getReasonId();

    /**
     * @return string
     */
    public function getReasonType();
}
