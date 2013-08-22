<?php

namespace Lighthouse\CoreBundle\Document;

use Doctrine\ODM\MongoDB\DocumentRepository as BaseRepository;

class DocumentRepository extends BaseRepository
{
    /**
     * @return AbstractDocument
     */
    public function createNew()
    {
        return new $this->documentName;
    }

    /**
     * @param $document
     */
    public function save($document)
    {
        $this->getDocumentManager()->persist($document);
        $this->getDocumentManager()->flush($document);
    }
}
