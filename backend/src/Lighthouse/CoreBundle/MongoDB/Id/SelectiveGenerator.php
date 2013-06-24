<?php

namespace Lighthouse\CoreBundle\MongoDB\Id;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Id\AbstractIdGenerator;

class SelectiveGenerator extends AbstractIdGenerator
{
    /**
     * Generates an identifier for a document.
     *
     * @param \Doctrine\ODM\MongoDB\DocumentManager $dm
     * @param object $document
     * @return string
     */
    public function generate(DocumentManager $dm, $document)
    {
        $classMetaData = $dm->getClassMetadata(get_class($document));
        $id = $classMetaData->getIdentifierValue($document);

        if (null === $id) {
            $id = (string) new \MongoId();
        }

        return $id;
    }
}
