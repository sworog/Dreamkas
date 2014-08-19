<?php

namespace Lighthouse\CoreBundle\Document;

use DateTime;

/**
 * @property DateTime $deletedAt
 */
interface SoftDeleteableDocument
{
    /**
     * @return DateTime
     */
    public function getDeletedAt();

    /**
     * @return string|null
     */
    public function getSoftDeleteableName();
}
