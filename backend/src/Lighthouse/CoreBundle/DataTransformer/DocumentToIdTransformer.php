<?php

namespace Lighthouse\CoreBundle\DataTransformer;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Document\NullObjectInterface;
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
     * @var DocumentRepository|VersionRepository
     */
    protected $repository;

    /**
     * Useful for partial validation, so skipped field would not affect
     * Use Reference constraint to validate
     * @var bool
     */
    protected $returnNullObjectOnNotFound;

    /**
     * @param DocumentManager $documentManager
     * @param string $class
     * @param VersionFactory $versionFactory
     * @param bool $returnNullObjectOnNotFound
     */
    public function __construct(
        DocumentManager $documentManager,
        $class,
        VersionFactory $versionFactory,
        $returnNullObjectOnNotFound = false
    ) {
        $this->documentManager = $documentManager;
        $this->repository = $documentManager->getRepository($class);
        if ($this->repository instanceof VersionRepository) {
            $this->repository->setVersionFactory($versionFactory);
        }
        $this->returnNullObjectOnNotFound = $returnNullObjectOnNotFound;
    }

    /**
     * @param AbstractDocument $document
     * @return string
     */
    public function transform($document)
    {
        if (null === $document) {
            return null;
        } elseif ($document instanceof NullObjectInterface) {
            return $document->getNullObjectIdentifier();
        }

        $metadata = $this->documentManager->getClassMetadata(get_class($document));
        $id = $metadata->getIdentifierValue($document);

        return $id;
    }

    /**
     * @param string $value
     * @throws \Symfony\Component\Form\Exception\TransformationFailedException
     * @return AbstractDocument|NullObjectInterface
     */
    public function reverseTransform($value)
    {
        if (null === $value || '' === $value) {
            return null;
        } elseif ($this->repository instanceof VersionRepository) {
            $document = $this->repository->findOrCreateByDocumentId($value);
        } else {
            $document = $this->repository->find($value);
        }

        if (null === $document) {
            if ($this->returnNullObjectOnNotFound) {
                $document = $this->repository->getNullObject($value);
            } else {
                throw new TransformationFailedException('Document ' . $this->repository->getClassName() . ' not found');
            }
        }

        return $document;
    }
}
