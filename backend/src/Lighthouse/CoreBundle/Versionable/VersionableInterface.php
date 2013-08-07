<?php

namespace Lighthouse\CoreBundle\Versionable;

interface VersionableInterface
{
    /**
     * @return string
     */
    public function getVersionClass();
}
