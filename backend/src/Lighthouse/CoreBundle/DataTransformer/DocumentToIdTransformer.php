<?php

namespace Lighthouse\CoreBundle\DataTransformer;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Versionable\VersionFactory;
use Lighthouse\CoreBundle\Versionable\VersionRepository;
use Symfony\Component\Form\DataTransformerInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * @DI\Service("lighthouse.core.data_transformer.document_to_id")
 */
class DocumentToIdTransformer implements DataTransformerInterface
{
    /**
     * @var DocumentManager
     */
    protected $documentManager;

    /**
     * @var DocumentRepository
     */
    protected $repository;

    /**
     * @param DocumentManager $documentManager
     * @param string $class
     * @param VersionFactory $versionFactory
     */
    public function __construct(DocumentManager $documentManager, $class, VersionFactory $versionFactory)
    {
        $this->documentManager = $documentManager;
        $this->repository = $documentManager->getRepository($class);
        if ($this->repository instanceof VersionRepository) {
            $this->repository->setVersionFactory($versionFactory);
        }
    }

    /**
     * @param AbstractDocument $document
     * @return string
     */
    public function transform($document)
    {
        if (null === $document) {
            return null;
        }

        return $document->id;
    }

    /**
     * @param string $value
     * @throws \Symfony\Component\Form\Exception\TransformationFailedException
     * @return AbstractDocument
     */
    public function reverseTransform($value)
    {
        if ($this->repository instanceof VersionRepository) {
            $document = $this->repository->findOrCreateByDocumentId($value);
        } else {
            $document = $this->repository->find($value);
        }

        if (null === $document) {
            throw new TransformationFailedException('Document ' . $this->repository->getClassName() . ' not found');
        }

        return $document;
    }
}
