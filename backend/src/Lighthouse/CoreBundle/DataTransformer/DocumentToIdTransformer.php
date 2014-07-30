<?php

namespace Lighthouse\CoreBundle\DataTransformer;

use Lighthouse\CoreBundle\Document\DocumentRepository;
use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Document\NullObjectInterface;
use Lighthouse\CoreBundle\Versionable\VersionRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class DocumentToIdTransformer implements DataTransformerInterface
{
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
     * @param DocumentRepository $documentRepository
     * @param bool $returnNullObjectOnNotFound
     */
    public function __construct(
        DocumentRepository $documentRepository,
        $returnNullObjectOnNotFound = false
    ) {
        $this->repository = $documentRepository;
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

        $dm = $this->repository->getDocumentManager();
        $metadata = $dm->getDocumentMetadata($document);
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
