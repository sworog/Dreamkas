<?php

namespace Lighthouse\CoreBundle\DataTransformer;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Lighthouse\CoreBundle\Document\AbstractDocument;
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
     */
    public function __construct(DocumentManager $documentManager, $class)
    {
        $this->documentManager = $documentManager;
        $this->repository = $documentManager->getRepository($class);
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
     * @return AbstractDocument
     */
    public function reverseTransform($value)
    {
        $document = $this->repository->find($value);

        if (null === $document) {
            throw new TransformationFailedException('Document ' . $this->repository->getClassName() . ' not found');
        }

        return $document;
    }
}
