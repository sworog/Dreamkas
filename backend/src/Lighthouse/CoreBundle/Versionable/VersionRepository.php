<?php

namespace Lighthouse\CoreBundle\Versionable;

use Lighthouse\CoreBundle\Document\DocumentRepository;

class VersionRepository extends DocumentRepository
{
    /**
     * @var VersionFactory
     */
    protected $versionFactory;

    /**
     * @param VersionFactory $versionFactory
     */
    public function setVersionFactory(VersionFactory $versionFactory)
    {
        $this->versionFactory = $versionFactory;
    }

    /**
     * @param $document
     * @return VersionInterface|object
     */
    public function findOrCreateByDocument($document)
    {
        $documentVersion = $this->versionFactory->getDocumentVersion($document);
        return $this->findByDocumentVersion($documentVersion);
    }

    public function findByDocumentVersion(VersionInterface $documentVersion)
    {
        $criteria = array(
            'version' => $documentVersion->getVersion(),
        );
        $foundDocumentVersion = $this->findOneBy($criteria);
        if (!$foundDocumentVersion) {
            $foundDocumentVersion = $documentVersion;
        }
        return $foundDocumentVersion;
    }
}
