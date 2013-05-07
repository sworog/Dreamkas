<?php

namespace Lighthouse\CoreBundle\Document;

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
