<?php

namespace Lighthouse\CoreBundle\Versionable;

use Lighthouse\CoreBundle\Document\DocumentRepository;
use JMS\DiExtraBundle\Annotation as DI;

class VersionRepository extends DocumentRepository
{
    /**
     * @var VersionFactory
     */
    protected $versionFactory;

    /**
     * @DI\InjectParams({
     *      "versionFactory" = @DI\Inject("lighthouse.core.versionable.factory")
     * })
     * @param VersionFactory $versionFactory
     */
    public function setVersionFactory(VersionFactory $versionFactory)
    {
        $this->versionFactory = $versionFactory;
    }

    /**
     * @param VersionableInterface $document
     * @return VersionInterface|object
     */
    public function findOrCreateByDocument(VersionableInterface $document)
    {
        $documentVersion = $this->versionFactory->createDocumentVersion($document);
        return $this->findByDocumentVersion($documentVersion);
    }

    /**
     * @param string $id
     * @return VersionInterface|null
     */
    public function findOrCreateByDocumentId($id)
    {
        /* @var VersionableInterface $document */
        $document = $this->getObjectRepository()->find($id);
        if ($document) {
            return $this->findOrCreateByDocument($document);
        } else {
            return null;
        }
    }

    /**
     * @param VersionInterface $documentVersion
     * @return VersionInterface
     */
    public function findByDocumentVersion(VersionInterface $documentVersion)
    {
        $foundDocumentVersion = $this->find($documentVersion->getVersion());
        if (!$foundDocumentVersion) {
            $foundDocumentVersion = $documentVersion;
        }
        return $foundDocumentVersion;
    }

    /**
     * @param string $documentId
     * @return \Doctrine\ODM\MongoDB\Cursor
     */
    public function findAllByDocumentId($documentId)
    {
        return $this->findBy(
            array('object' => $documentId),
            array('createdDate' => -1)
        );
    }

    /**
     * @return \Doctrine\ODM\MongoDB\DocumentRepository
     */
    public function getObjectRepository()
    {
        $parentClass = $this->class->parentClasses[0];
        return $this->dm->getRepository($parentClass);
    }
}
