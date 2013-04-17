<?php

namespace Lighthouse\CoreBundle\Document;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Lighthouse\CoreBundle\Types\Money;

class ProductRepository extends DocumentRepository
{
    /**
     * @param string $property
     * @param string $entry
     * @return LoggableCursor
     */
    public function searchEntry($property, $entry)
    {
        return $this->findBy(array($property => new \MongoRegex("/".preg_quote($entry, '/')."/i")));
    }
}
