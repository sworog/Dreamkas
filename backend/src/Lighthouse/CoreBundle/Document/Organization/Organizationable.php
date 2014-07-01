<?php

namespace Lighthouse\CoreBundle\Document\Organization;

use Lighthouse\CoreBundle\Document\LegalDetails\LegalDetails;

interface Organizationable
{
    /**
     * @return LegalDetails
     */
    public function getLegalDetails();

    /**
     * @param LegalDetails $legalDetails
     * @return $this
     */
    public function setLegalDetails(LegalDetails $legalDetails);
}
