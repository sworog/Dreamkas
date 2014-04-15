<?php

namespace Lighthouse\CoreBundle\Document;

use Doctrine\MongoDB\Cursor;
use Doctrine\ODM\MongoDB\DocumentRepository as BaseRepository;
use Doctrine\ODM\MongoDB\LockMode;
use Doctrine\ODM\MongoDB\MongoDBException;
use Doctrine\ODM\MongoDB\Proxy\Proxy;
use Doctrine\MongoDB\Collection;
use MongoCollection;
use MongoCursor;
use MongoId;

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
     * @return int
     */
    public function count()
    {
        /* @var Cursor $cursor */
        $cursor = $this->findAll();
        return $cursor->count();
    }

    /**
     * @return array
     */
    public function findAllIds()
    {
        $qb = $this->createQueryBuilder()
            ->hydrate(false)
            ->select('_id');
        $result = $qb->getQuery()->execute();
        $ids = array();
        foreach ($result as $row) {
            $ids[] = $row['_id'];
        }
        return $ids;
    }

    /**
     * @param array $criteria
     * @param array $sort
     * @param array $hints
     * @return null|object
     */
    public function findOneBy(array $criteria, array $sort = array(), array $hints = array())
    {
        return $this->uow->getDocumentPersister($this->documentName)->load(
            $criteria,
            null,
            $hints,
            LockMode::NONE,
            $sort
        );
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
     * @param string $id
     * @return NullObjectInterface
     */
    public function getNullObject($id)
    {
        throw new \RuntimeException('Object is not defined');
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

        if (isset($backupTimeout)) {
            MongoCursor::$timeout = $backupTimeout;
        }

        if (1 == $result['ok']) {
            return $result['result'];
        }

        throw new MongoDBException($result['errmsg'], $result['code']);
    }

    /**
     * @param array $ids
     * @return MongoId[]
     */
    protected function convertToMongoIds(array $ids)
    {
        return array_map(
            function ($id) {
                return new MongoId((string) $id);
            },
            $ids
        );
    }

    /**
     * @param object $document
     * @return string
     */
    public function getDocumentIdentifierValue($document)
    {
        return $this->dm->getClassMetadata(get_class($document))->getIdentifierValue($document);
    }
}
