<?php

namespace Lighthouse\CoreBundle\Document;

use Doctrine\MongoDB\Cursor;
use Doctrine\ODM\MongoDB\DocumentRepository as BaseRepository;
use Doctrine\ODM\MongoDB\Proxy\Proxy;

class DocumentRepository extends BaseRepository
{
    const SORT_ASC = 1;
    const SORT_DESC = -1;

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

    /**
     * @return bool
     */
    public function isCollectionEmpty()
    {
        /* @var Cursor $cursor */
        $cursor = $this->findAll();
        return 0 == $cursor->limit(1)->count();
    }

    /**
     * @param string $id
     * @return Proxy
     */
    public function getReference($id)
    {
        return $this->getDocumentManager()->getReference($this->getClassName(), $id);
    }
}
