<?php

namespace Lighthouse\CoreBundle\Document;

use Doctrine\MongoDB\Cursor;
use Doctrine\ODM\MongoDB\DocumentRepository as BaseRepository;
use Doctrine\ODM\MongoDB\MongoDBException;
use Doctrine\ODM\MongoDB\Proxy\Proxy;
use Doctrine\MongoDB\Collection;
use MongoCollection;
use MongoCursor;

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

    /**
     * @return Collection
     */
    protected function getDocumentCollection()
    {
        return $this->getDocumentManager()->getDocumentCollection($this->getClassName());
    }

    /**
     * @return MongoCollection
     */
    protected function getMongoCollection()
    {
        return $this->getDocumentCollection()->getMongoCollection();
    }

    /**
     * @param array $ops
     * @param int|null $timeout
     * @return array
     * @throws MongoDBException
     */
    protected function aggregate(array $ops, $timeout = -1)
    {
        if (null !== $timeout) {
            $backupTimeout = MongoCursor::$timeout;
            MongoCursor::$timeout = $timeout;
        }

        $result = $this->getMongoCollection()->aggregate($ops);

        if (null !== $timeout) {
            MongoCursor::$timeout = $backupTimeout;
        }

        if (1 == $result['ok']) {
            return $result['result'];
        }

        throw new MongoDBException($result['errmsg'], $result['code']);
    }
}
