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
}
